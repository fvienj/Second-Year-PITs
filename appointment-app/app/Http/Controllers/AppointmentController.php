<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        // Start a database transaction for data safety
        DB::beginTransaction();

        try {
            // Save the patient details to the database
            $patient = Patient::create([
                'patient_firstname' => $request->input('first_name'),
                'patient_lastname'  => $request->input('last_name'),
                'patient_phone'     => $request->input('contact'),
                'patient_dob'       => $request->input('dob'),
            ]);

            //  Save the appointment details linked to the patient
            $appointment = Appointment::create([
                'patient_id'       => $patient->getKey(),
                'dentist_id'       => $request->input('dentist'),
                'appointment_name' => 'New Booking via Web',
                'appointment_date' => $request->input('appointment_date'),
                'appointment_time' => $request->input('appointment_time'),
            ]);

            //  Look up service details from the database
            $serviceId = $request->input('service');
            $serviceDetails = DB::table('services')->where('services_id', $serviceId)->first();
            
            $serviceName = $serviceDetails ? $serviceDetails->service_name : 'Standard Dental Consultation';
            $amountDue = $serviceDetails ? $serviceDetails->service_cost : 0.00;

            //  Insert into the patient_services table
            DB::table('patient_services')->insert([
                'patient_id' => $patient->getKey(),
                'service_id' => $serviceId
            ]);

            // Generate the appointment invoice records
            DB::table('appointment_invoice')->insert([
                'appointment_id'            => $appointment->getKey(),
                'service_id'                => $serviceId,
                'appointment_paymentstatus' => 'Pending',
                'appointment_amount'        => $amountDue,
                'appointment_paymentmethod' => 'Cash at Clinic'
            ]);

            // Update the dentist's schedule visibility status
            DB::table('dentist_schedule')->insert([
                'dentist_id'            => $request->input('dentist'),
                'schedule_day'          => $request->input('appointment_date'),
                'schedule_starttime'    => $request->input('appointment_time'),
                'schedule_endtime'      => date('H:i:s', strtotime($request->input('appointment_time') . ' +1 hour')),
                'schedule_availability' => 'Unavailable',
                'schedule_status'       => 'Booked',
                'schedule_created'      => now(),
                'schedule_updated'      => now()
            ]);

            // Track materials log inside the inventory ledger table
            $hasMaterial = DB::table('material')->where('material_id', $serviceId)->exists();
            $assignedMaterialId = $hasMaterial ? $serviceId : 1;
            DB::table('log')->insert([
                'usage_id'     => 1, 
                'material_id'  => $assignedMaterialId,
                'log_dateused' => $request->input('appointment_date'),
                'log_notes'    => "Automated stock allocation triggered by booking for " . $serviceName
            ]);

            // Commit everything to the database if all steps succeeded
            DB::commit();

        } catch (\Exception $e) {
            // Cancel and roll back all changes if an error happens above
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to book appointment: ' . $e->getMessage()
            ], 500);
        }

        // TEXTBEE INTEGRATION
        $messageText = "Hi {$patient->patient_firstname}! Your booking for {$serviceName} at Bag-Ang Dental Clinic is confirmed for {$appointment->appointment_date} at {$appointment->appointment_time}.";
        
        // Format the local mobile number string
        $phoneNumber = $patient->patient_phone;
        if (str_starts_with($phoneNumber, '09')) {
            $phoneNumber = '+63' . substr($phoneNumber, 1);
        }

        try {
            $deviceId = env('TEXTBEE_DEVICE_ID');
            $smsResponse = Http::withoutVerifying()
                ->withHeaders([
                    'x-api-key'    => env('TEXTBEE_API_KEY'),
                    'Content-Type' => 'application/json'
                ])
                ->post("https://api.textbee.dev/api/v1/gateway/devices/{$deviceId}/send-sms", [
                    'recipients' => [$phoneNumber],
                    'message'    => $messageText
                ]);

            // Log SMS outcome to the database
            DB::table('notification_logs')->insert([
                'patient_id'     => $patient->getKey(),
                'appointment_id' => $appointment->getKey(),
                'channel'        => 'SMS',
                'status'         => $smsResponse->successful() ? 'Delivered' : 'Failed',
                'error_message'  => $smsResponse->successful() ? null : $smsResponse->body()
            ]);
        } catch (\Exception $e) {
            DB::table('notification_logs')->insert([
                'patient_id' => $patient->getKey(), 'appointment_id' => $appointment->getKey(),
                'channel' => 'SMS', 'status' => 'Failed', 'error_message' => 'Exception: ' . substr($e->getMessage(), 0, 200)
            ]);
        }

        // GMAIL SMTP AUTOMATED EMAIL
        $patientEmail = $request->input('email');
        
        if (!empty($patientEmail)) {
            try {
                // Laravel's built-in mail sender
                \Illuminate\Support\Facades\Mail::raw($messageText, function ($message) use ($patientEmail) {
                    $message->to($patientEmail)
                            ->subject('Appointment Confirmed - Bag-Ang Dental Clinic');
                });

                // Log Email outcome to the database
                DB::table('notification_logs')->insert([
                    'patient_id'     => $patient->getKey(),
                    'appointment_id' => $appointment->getKey(),
                    'channel'        => 'Email',
                    'status'         => 'Delivered',
                    'error_message'  => null
                ]);
            } catch (\Exception $e) {
                DB::table('notification_logs')->insert([
                    'patient_id' => $patient->getKey(), 'appointment_id' => $appointment->getKey(),
                    'channel' => 'Email', 'status' => 'Failed', 'error_message' => 'Exception: ' . substr($e->getMessage(), 0, 200)
                ]);
            }
        }

        // Return the final success JSON 
        return response()->json([
            'success' => true,
            'message' => 'Appointment successfully booked!'
        ]);
    }
}
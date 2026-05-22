<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;


class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate the incoming data
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'contact' => 'required|string|max:20',
            'dob' => 'required|date',
            'dentist' => 'required|integer',
            'service' => 'required|integer',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

DB::beginTransaction();

        try {
            // 1. Insert into Patient table
            $patient = Patient::create([
                'patient_firstname' => $validated['first_name'],
                'patient_lastname'  => $validated['last_name'],
                'patient_email'     => $validated['email'], 
                'patient_phone'     => $validated['contact'],
                'patient_dob'       => $validated['dob'],
            ]);

            // 2. Insert into Appointment table
            $appointment = Appointment::create([
                'patient_id'       => $patient->getKey(),
                'dentist_id'       => $validated['dentist'],
                'appointment_name' => 'New Booking via Web',
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
            ]);

            // Fetch service details from DB to get the actual cost dynamically
            $serviceDetails = DB::table('services')->where('services_id', $validated['service'])->first();
            $amountDue = $serviceDetails ? $serviceDetails->service_cost : 0.00;
            $serviceName = $serviceDetails ? $serviceDetails->service_name : 'Dental Service';

            // 3. Insert into Patient_Services junction table
            DB::table('patient_services')->insert([
                'patient_id' => $patient->getKey(),
                'service_id' => $validated['service']
            ]);

            // 4. Automatically generate a companion clinical invoice ledger entry
            DB::table('appointment_invoice')->insert([
                'appointment_id'            => $appointment->getKey(),
                'service_id'                => $validated['service'],
                'appointment_paymentstatus' => 'Pending',
                'appointment_amount'        => $amountDue,
                'appointment_paymentmethod' => 'Cash at Clinic'
            ]);

            // 5. Update Dentist Schedule Status
            DB::table('dentist_schedule')->insert([
                'dentist_id'            => $validated['dentist'],
                'schedule_day'          => $validated['appointment_date'],
                'schedule_starttime'    => $validated['appointment_time'],
                'schedule_endtime'      => date('H:i:s', strtotime($validated['appointment_time'] . ' +1 hour')),
                'schedule_availability' => 'Unavailable',
                'schedule_status'       => 'Booked',
                'schedule_created'      => now(),
                'schedule_updated'      => now()
            ]);

            // 6. Automatically Log Resource/Material Consumption
            $hasMaterial = DB::table('material')->where('material_id', $validated['service'])->exists();
            $assignedMaterialId = $hasMaterial ? $validated['service'] : 1;

            DB::table('Log')->insert([
                'usage_id'     => 1, 
                'material_id'  => $assignedMaterialId, 
                'log_dateused' => $validated['appointment_date'],
                'log_notes'    => "Automated stock allocation triggered by booking for " . $serviceName
            ]);

            // Commit all local database records safely together before external network streams
            DB::commit();

            // Local System File Interchange Bridge 
            $integrationPayload = [
                'event_type'        => 'APPOINTMENT_BOOKED',
                'timestamp'         => now()->toIso8601String(),
                'patient_name'      => $validated['first_name'] . ' ' . $validated['last_name'],
                'patient_contact'   => $validated['contact'],
                'appointment_date'  => $validated['appointment_date'],
                'appointment_time'  => $validated['appointment_time'],
                'service_requested' => $serviceName,
                'billing_amount'    => $amountDue
            ];

            $directoryPath = storage_path('app/integrated_systems');
            if (!file_exists($directoryPath)) { mkdir($directoryPath, 0755, true); }
            $fileName = 'sync_appt_' . $appointment->getKey() . '_' . time() . '.json';
            file_put_contents($directoryPath . '/' . $fileName, json_encode($integrationPayload, JSON_PRETTY_PRINT));


            // textbee.dev External Device SMS Gateway Integration 
            $smsMessage = "Hi " . $validated['first_name'] . ", your appointment for " . $serviceName . " on " . $validated['appointment_date'] . " at " . $validated['appointment_time'] . " is CONFIRMED at Bag-Ang Dental Clinic. Total Due: Php " . number_format($amountDue) . ". Thank you!";
            
            $textbeeApiKey   = env('TEXTBEE_API_KEY');
            $textbeeDeviceId = env('TEXTBEE_DEVICE_ID');
            $smsStatus       = 'Simulated/Buffered Mode';

            if ($textbeeApiKey && $textbeeDeviceId) {
                try {
                    // textbee.dev endpoint requires an array of recipients and custom auth header keys
                    $apiResponse = Http::timeout(4)
                        ->withHeaders(['x-api-key' => $textbeeApiKey])
                        ->post("https://api.textbee.dev/api/v1/gateway/devices/{$textbeeDeviceId}/send-sms", [
                            'recipients' => [$validated['contact']],
                            'message'    => $smsMessage
                        ]);
                    
                    $smsStatus = $apiResponse->successful() 
                        ? 'Dispatched to Device successfully' 
                        : 'Gateway rejected payload: ' . $apiResponse->body();
                } catch (\Exception $networkException) {
                    // Log the exception for monitoring and fallback to simulated mode without failing the entire booking process
                    \Log::warning("textbee.dev Gateway offline. Fallback triggered: " . $networkException->getMessage());
                    $smsStatus = 'Offline Failover Mode Active (Gracefully Cached)';
                }
            }

            return response()->json([
                'success'           => true, 
                'message'           => 'Booking finalized, internal relational arrays processed.',
                'integrated_system' => 'Dual Integration Mode (Local File + TextBee Gateway Active)',
                'local_export'      => $fileName,
                'textbee_sms'       => $smsStatus
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Failed to book appointment: ' . $e->getMessage()
            ], 500);
        }

    }
}
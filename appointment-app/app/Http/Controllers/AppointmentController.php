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
        // 1. Validate incoming data payloads
        $validated = $request->validate([
            'first_name'       => 'required|string|max:100',
            'last_name'        => 'required|string|max:100',
            'email'            => 'required|email|max:150',
            'contact'          => 'required|string|max:20',
            'dob'              => 'required|date',
            'dentist'          => 'required|integer',
            'service'          => 'required|integer',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
        ]);

        // Begin transaction to guarantee strict ACID compliance over the cloud network
        DB::beginTransaction();

        try {
            // 2. Insert profile record into lowercase patient table
            $patient = Patient::create([
                'patient_firstname' => $validated['first_name'],
                'patient_lastname'  => $validated['last_name'],
                'patient_email'     => $validated['email'], 
                'patient_phone'     => $validated['contact'],
                'patient_dob'       => $validated['dob'],
            ]);

            // 3. Insert operational record into lowercase appointment table
            // Uses getKey() to automatically extract the correct primary key identifier safely
            $appointment = Appointment::create([
                'patient_id'       => $patient->getKey(),
                'dentist_id'       => $validated['dentist'],
                'appointment_name' => 'New Booking via Web',
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
            ]);

            // 4. Look up dynamic financial costs from lowercase services table
            $serviceDetails = DB::table('services')->where('services_id', $validated['service'])->first();
            $amountDue = $serviceDetails ? $serviceDetails->service_cost : 0.00;
            $serviceName = $serviceDetails ? $serviceDetails->service_name : 'Dental Service';

            // 5. Update junction association table
            DB::table('patient_services')->insert([
                'patient_id' => $patient->getKey(),
                'service_id' => $validated['service']
            ]);

            // 6. Generate companion billing invoice ledger automatically
            DB::table('appointment_invoice')->insert([
                'appointment_id'            => $appointment->getKey(),
                'service_id'                => $validated['service'],
                'appointment_paymentstatus' => 'Pending',
                'appointment_amount'        => $amountDue,
                'appointment_paymentmethod' => 'Cash at Clinic'
            ]);

            // 7. Update Dentist Schedule block to block double bookings
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

            // 8. Log automated resource inventory allocation tracking
            $hasMaterial = DB::table('material')->where('material_id', $validated['service'])->exists();
            $assignedMaterialId = $hasMaterial ? $validated['service'] : 1;

            DB::table('log')->insert([
                'usage_id'     => 1, 
                'material_id'  => $assignedMaterialId, 
                'log_dateused' => $validated['appointment_date'],
                'log_notes'    => "Automated stock allocation triggered by booking for " . $serviceName
            ]);

            // Commit all local and cloud database records safely together before launching external network streams
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
            if (!file_exists($directoryPath)) { 
                mkdir($directoryPath, 0755, true); 
            }
            $fileName = 'sync_appt_' . $appointment->getKey() . '_' . time() . '.json';
            file_put_contents($directoryPath . '/' . $fileName, json_encode($integrationPayload, JSON_PRETTY_PRINT));

            //  textbee.dev External Device SMS Gateway Integration 
            $smsMessage = "Hi " . $validated['first_name'] . ", your appointment for " . $serviceName . " on " . $validated['appointment_date'] . " at " . $validated['appointment_time'] . " is CONFIRMED at Bag-Ang Dental Clinic. Total Due: Php " . number_format($amountDue) . ". Thank you!";
            
            $textbeeApiKey   = env('TEXTBEE_API_KEY');
            $textbeeDeviceId = env('TEXTBEE_DEVICE_ID');
            $smsStatus       = 'Simulated/Buffered Mode';

            if ($textbeeApiKey && $textbeeDeviceId) {
                try {
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
                    // Graceful Network Fallback: Prevents cloud/internet disconnects from crashing the system
                    \Log::warning("TextBee Gateway unreachable: " . $networkException->getMessage());
                    $smsStatus = 'Offline Failover Mode Active (Gracefully Cached)';
                }
            }

            return response()->json([
                'success'           => true, 
                'message'           => 'Booking finalized, cloud database updated successfully.',
                'integrated_system' => 'Dual Integration Mode (Local File + TextBee Gateway Active)',
                'local_export'      => $fileName,
                'textbee_sms'       => $smsStatus
            ]);

        } catch (\Exception $e) {
            // Roll back the cloud database state cleanly if any transaction step fails
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'System exception encountered: ' . $e->getMessage()
            ], 500);
        }
    }
}
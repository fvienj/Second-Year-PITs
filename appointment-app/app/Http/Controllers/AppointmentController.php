<?php

namespace App\Http\Controllers;

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

        // Begin transaction so both inserts succeed, or neither do
        DB::beginTransaction();

        try {
            // 2. Insert into Patient table
            $patient = Patient::create([
                'patient_firstname' => $validated['first_name'],
                'patient_lastname'  => $validated['last_name'],
                'patient_email'     => $validated['email'], // Ensure your DB has this column!
                'patient_phone'     => $validated['contact'],
                'patient_dob'       => $validated['dob'],
            ]);

            // 3. Insert into Appointment table
            Appointment::create([
                'patient_id'       => $patient->patient_id,
                'dentist_id'       => $validated['dentist'],
                'appointment_name' => 'New Booking',
                'appointment_date' => $validated['appointment_date'],
                'appointment_time' => $validated['appointment_time'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Appointment booked successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    public function handleChat(Request $request)
    {
        try {
            $userMessage = $request->input('message');
            $apiKey = env('GEMINI_API_KEY');
            
            // Looks at DB and the next 7 days of appointment
            $bookedAppointments = DB::table('appointment')
                ->join('dentist', 'appointment.dentist_id', '=', 'dentist.dentist_id')
                ->where('appointment_date', '>=', now()->toDateString())
                ->where('appointment_date', '<=', now()->addDays(7)->toDateString())
                ->get(['appointment_date', 'appointment_time', 'dentist_lastname']);

            $scheduleContext = "LIVE BOOKED SLOTS (Next 7 Days):\n";
            if ($bookedAppointments->isEmpty()) {
                $scheduleContext .= "- The calendar is completely open. All standard slots are available.\n";
            } else {
                foreach ($bookedAppointments as $appt) {
                    $time = date('h:i A', strtotime($appt->appointment_time));
                    $scheduleContext .= "- {$appt->appointment_date} at {$time} with Dr. {$appt->dentist_lastname} is BOOKED.\n";
                }
            }

            // CLINIC RULES FOR THE AI
            $systemInstruction = "You are 'ToothBuddy', the helpful AI assistant for Bag-Ang Dental Clinic.\n"
                . "Keep your responses short, friendly, and limited to a maximum of 2 sentences.\n"
                . "Answer questions using ONLY these official clinic facts:\n\n"
                . "1. LOCATION: Located at the USTP Campus, C.M. Recto, Lapasan, Cagayan de Oro City (CDO), Misamis Oriental 9000.\n"
                . "2. HOURS: Monday to Saturday, 8:00 AM to 5:00 PM. We are completely CLOSED on Sundays.\n"
                . "3. DENTISTS:\n"
                . "   - Dr. Monica Empleo: Lead Orthodontist, focuses on smile architecture and precision aligners. License: DENT-102948.\n"
                . "   - Dr. Fvienj Nopuente: Clinical Aesthetic & Oral Surgery Dentist, focuses on complex surgical extractions. License: DENT-207531.\n"
                . "4. FIXED PRICE MENU:\n"
                . "   - Dental Bonding: Php 150,000\n"
                . "   - Dental Crowns: Php 20,000 - Php 40,000\n"
                . "   - Dentures: Php 5,000 - Php 12,000\n"
                . "   - Teeth Cleaning: Php 800 - Php 1,200\n"
                . "   - Tooth Extractions: Php 500 - Php 1,500\n"
                . "   - Orthodontic Braces: Php 28,000 - Php 300,000\n\n"
                . "5. BOOKING METHOD: If a user wants to book or schedule an appointment, politely instruct them to use the 'Book Appointment' tab or form on our website so it saves directly to our database.\n\n"
                . "If a user asks about anything outside of these facts, politely reply: 'I'm sorry, I can only assist with official Bag-Ang Dental Clinic details. Please contact the front desk directly.'"
                . "6. LIVE APPOINTMENT CHECKING:\n"
                . $scheduleContext . "\n"
                . "If a user asks if a time is available, check the LIVE BOOKED SLOTS above. If their requested time is listed above, say it is BOOKED and suggest they pick another time. If it is NOT listed above (and is Mon-Sat 8AM-5PM), confidently tell them the slot is AVAILABLE.\n\n"
                . "If a user asks about anything outside of these facts, politely reply: 'I'm sorry, I can only assist with official Bag-Ang Dental Clinic details. Please contact the front desk directly.'";;
            // USING GEMINI Google API 
            $response = Http::withoutVerifying()->post(
                "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key={$apiKey}", 
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => "Patient Question: " . $userMessage]
                            ]
                        ]
                    ],
                    // put system instruction intothe parts/ their response
                    'systemInstruction' => [
                        'parts' => [
                            ['text' => $systemInstruction]
                        ]
                    ]
                ]
            );

            $data = $response->json();
            $aiReply = $data['candidates'][0]['content']['parts'][0]['text'] ?? "I am having trouble connecting to the front desk right now.";

            return response()->json(['reply' => $aiReply]);

        } catch (\Throwable $e) {
            return response()->json([
                'reply' => "Sorry, I'm having trouble connecting to the front desk right now. Please try again!"
            ]);
        }
    }
}
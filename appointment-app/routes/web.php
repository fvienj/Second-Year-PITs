<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ChatbotController;

// View Routes
Route::get('/', function () { return view('home'); });
Route::get('/home', function () { return view('home'); });
Route::get('/about', function () { return view('about'); });
Route::get('/services', function () { return view('services'); });
Route::get('/contact', function () { return view('contact'); });

// Dynamic Book View
Route::get('/book', function () {
    $dentists = DB::table('dentist')->get(); 
    $services = DB::table('services')->get();
    return view('book', compact('dentists', 'services'));
});

// Checking Availability
Route::get('/check-availability', function (Request $request) {
    $date = $request->query('date');
    $dentistId = $request->query('dentist_id');
    
    // Fetch raw times from PostgreSQL and format them using PHP
    $bookedTimes = DB::table('appointment')
        ->where('appointment_date', $date)
        ->where('dentist_id', $dentistId)
        ->pluck('appointment_time')
        ->map(function ($time) {
            // Converts PostgreSQL's "08:00:00" to "08:00" 
            return date('H:i', strtotime($time)); 
        })
        ->toArray();
        
    return response()->json($bookedTimes);
});

// Core Logic Routes
Route::post('/book-appointment', [AppointmentController::class, 'store']);
Route::post('/ask-ai', [ChatbotController::class, 'handleChat']);
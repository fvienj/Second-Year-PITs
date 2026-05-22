<?php

//use App\Http\Controllers\Teams\TeamInvitationController;
//use App\Http\Middleware\EnsureTeamMembership;
//use Laravel\Fortify\Features;

/*Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::prefix('{current_team}')
    ->middleware(['auth', 'verified', EnsureTeamMembership::class])
    ->group(function () {
        Route::inertia('dashboard', 'dashboard')->name('dashboard');
    });

Route::middleware(['auth'])->group(function () {
    Route::get('invitations/{invitation}/accept', [TeamInvitationController::class, 'accept'])->name('invitations.accept');
});

require __DIR__.'/settings.php';*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::redirect('/', '/home'); 
//view main pages
Route::view('/home', 'home');
Route::view('/book', 'book');
Route::view('/about', 'about');
Route::view('/contact', 'contact');
Route::view('/services', 'services');

Route::post('/book-appointment', [AppointmentController::class, 'store']);

Route::get('/book', function () {
    // Get active dentists and services directly from the database
    $dentists = DB::table('dentist')->where('dentist_status', 'Active')->get();
    $services = DB::table('services')->get();

    // Send data straight into your book.blade.php view
    return view('book', compact('dentists', 'services'));
});

Route::get('/check-availability', function (Request $request) {
    $date = $request->query('date');
    $dentistId = $request->query('dentist_id');

    //Appointment table and format the time directly in SQL
    $bookedTimes = DB::table('Appointment')
        ->where('appointment_date', $date)
        ->where('dentist_id', $dentistId)
        // TIME_FORMAT(%H:%i)turning "08:00:00" into "08:00"
        ->select(DB::raw("TIME_FORMAT(appointment_time, '%H:%i') as formatted_time"))
        ->pluck('formatted_time') // Pulls clean values like ["08:00", "09:00"]
        ->toArray();

    return response()->json($bookedTimes);
});
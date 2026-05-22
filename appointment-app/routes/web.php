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

// Main standard views
Route::view('/home', 'home');
Route::view('/about', 'about');
Route::view('/contact', 'contact');
Route::view('/services', 'services');

// Fetch dynamic lookup arrays from Supabase lowercase tables
Route::get('/book', function () {
    $dentists = DB::table('dentist')->where('dentist_status', 'Active')->get();
    $services = DB::table('services')->get();
    
    return view('book', compact('dentists', 'services'));
});

// Asynchronous checking hook for real-time frontend slot validation
Route::get('/check-availability', function (Request $request) {
    $date = $request->query('date');
    $dentistId = $request->query('dentist_id');

    if (!$date || !$dentistId) {
        return response()->json([]);
    }

    // Casts the TIME column to a HH:MI string format
    $bookedTimes = DB::table('appointment')
        ->where('appointment_date', $date)
        ->where('dentist_id', $dentistId)
        ->select(DB::raw("TO_CHAR(appointment_time, 'HH24:MI') as formatted_time"))
        ->pluck('formatted_time')
        ->toArray();

    return response()->json($bookedTimes);
});

Route::post('/book-appointment', [AppointmentController::class, 'store']);
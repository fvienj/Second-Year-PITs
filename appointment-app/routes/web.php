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

//view main pages
Route::view('/home', 'home');
Route::view('/book', 'book');
Route::view('/about', 'about');
Route::view('/contact', 'contact');
Route::view('/services', 'services');



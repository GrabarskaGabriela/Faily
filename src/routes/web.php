<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\RideRequestController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/add_event', function () {
    return view('add_event');
});

Route::get('/account', function () {
    return view('account');
});

Route::get('/event_list', function () {
    return view('event_list');
});

Route::get('/main', function () {
    return view('main');
});

Route::get('/password_reminder', function () {
    return view('password_reminder');
});

Route::get('/settings', function () {
    return view('settings');
});

Route::get('/event', function () {
    return view('event');
});

Route::get('/mapa', function () {
    return view('mapa');
});

Route::middleware(['auth'])->group(function () {
    // Profil użytkownika
    Route::get('/profile/dashboard', function () {return view('profile.dashboard');})->name('profile.dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/photo', [ProfileController::class, 'editPhoto'])->name('profile.edit-photo');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');
    Route::post('/profile/toggle-2fa', [ProfileController::class, 'toggle2FA'])->name('profile.toggle-2fa');
    Route::post('/profile/toggle-notifications', [ProfileController::class, 'toggleNotifications'])->name('profile.toggle-notifications');
});

Route::resource('events', EventController::class);
Route::resource('rides', RideController::class);
Route::resource('ride-requests', RideRequestController::class);


require __DIR__.'/auth.php';

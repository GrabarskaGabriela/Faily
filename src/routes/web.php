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
Route::get('/about', function () {
    return view('about');
});
Route::get('/help', function () {
    return view('help');
});
Route::middleware(['auth'])->group(function () {
    // Grupa routów profilu użytkownika
    Route::prefix('profile')->name('profile.')->group(function () {
        // Dashboard
        Route::get('/dashboard', function () {
            return view('profile.dashboard');
        })->name('dashboard');

        // Podstawowe operacje profilu
        Route::get('/', [ProfileController::class, 'show'])->name('show');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');

        // Edycja profilu
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');

        // Zarządzanie zdjęciem profilowym
        Route::prefix('photo')->group(function () {
            Route::get('/', [ProfileController::class, 'editPhoto'])->name('edit-photo');
            Route::post('/', [ProfileController::class, 'updatePhoto'])->name('update-photo');
        });

        // Ustawienia
        Route::post('/toggle-2fa', [ProfileController::class, 'toggle2FA'])->name('toggle-2fa');
        Route::post('/toggle-notifications', [ProfileController::class, 'toggleNotifications'])->name('toggle-notifications');
    });
});

Route::resource('events', EventController::class);
Route::resource('rides', RideController::class);
Route::resource('ride-requests', RideRequestController::class);


require __DIR__.'/auth.php';

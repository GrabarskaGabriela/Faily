<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\RideRequestController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/add_event', function () {
    return view('events.add_event');
});

Route::get('/account', function () {
    return view('account');
});

Route::get('/main', function () {
    return view('main');
});

Route::get('/password_reminder', function () {
    return view('password_reminder');
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

    Route::resource('events', EventController::class)->names('events');
    Route::resource('rides', RideController::class)->names('rides');
    Route::resource('ride-requests', RideRequestController::class)->names('ride-requests');

    Route::get('/my-events', function () {
        $events = App\Models\Event::with(['user', 'photos'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(9);

        return view('events.list', compact('events'));
    })->name('my.events');

});



require __DIR__.'/auth.php';

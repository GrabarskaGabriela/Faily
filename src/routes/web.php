<?php

use App\Http\Controllers\EventAttendeeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\RideRequestController;
use App\Http\Controllers\TestController;


Route::get('/', function () {
    return view('welcome');
})->name('welcome');


Route::get('/password_reminder', function () {
    return view('password_reminder');
});

Route::get('/about', function () {
    return view('about');
});

Route::get('/test', [\App\Http\Controllers\TestController::class, 'test']);

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/dashboard', function () {return view('profile.dashboard');})->name('profile.dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::match(['put', 'patch'],'/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/photo', [ProfileController::class, 'editPhoto'])->name('profile.edit-photo');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');

    Route::get('/feed', [EventController::class, 'feed'])->name('events.feed');

    Route::resource('events', EventController::class)->names('events');
    Route::resource('rides', RideController::class)->names('rides');
    Route::resource('ride-requests', RideRequestController::class)->names('ride-requests');

    Route::get('/my_events', function () {
        $events = App\Models\Event::with(['user', 'photos'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(9);

        return view('events.event_list', compact('events'));
    })->name('my.events');

    Route::get('/events/{event}/attendees', [EventAttendeeController::class, 'index'])->name('events.attendees.index');
    Route::get('/events/{event}/attend', [EventAttendeeController::class, 'create'])->name('events.attendees.create');
    Route::post('/events/{event}/attend', [EventAttendeeController::class, 'store'])->name('events.attendees.store');
    Route::patch('/events/{event}/attendees/{attendee}', [EventAttendeeController::class, 'update'])->name('events.attendees.update');
    Route::delete('/events/{event}/attendees/{attendee}', [EventAttendeeController::class, 'destroy'])->name('events.attendees.destroy');

    Route::get('/mapa', function () {
        return view('mapa');
    });
});



require __DIR__.'/auth.php';

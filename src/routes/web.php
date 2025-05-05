<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventAttendeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MainMapController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\RideRequestController;
use App\Http\Controllers\LanguageController;


<<<<<<< HEAD
Route::get('/', function () {return view('welcome');})->name('welcome');
Route::get('/about', function () {return view('about');});
Route::get('language/{locale}', [LanguageController::class, 'changeLanguage'])->name('language.change');
=======
>>>>>>> origin/wodzu

Route::get('/', function () {
    return view('welcome');
})->name('welcome')->middleware('locale');

Route::get('/about', function () {
    return view('about');
})->middleware('locale');

Route::get('language/{locale}', [LanguageController::class, 'changeLanguage'])
    ->name('language.change')->
    middleware('locale');

Route::middleware(['auth', 'verified', 'locale'])->group(function ()
{
<<<<<<< HEAD
=======
    //Route::get('/', function () { return view('welcome'); })->name('afterlogin');
>>>>>>> origin/wodzu

    Route::get('/profile/dashboard', function () {return view('profile.dashboard');})->name('profile.dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::match(['put', 'patch'],'/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/photo', [ProfileController::class, 'editPhoto'])->name('profile.edit-photo');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update-photo');

    Route::get('/events/feed', [EventController::class, 'index'])->name('events.feed');

    Route::resource('events', EventController::class)->names('events');
    Route::resource('rides', RideController::class)->names('rides');
    Route::resource('ride-requests', RideRequestController::class)->names('ride-requests');
    Route::get('/ride_requests', [RideRequestController::class, 'index'])->name('ride_requests.index');
    Route::get('/ride_requests/create', [RideRequestController::class, 'create'])->name('ride_requests.create');
<<<<<<< HEAD
=======


>>>>>>> origin/wodzu

    Route::get('/events/{event}/attendees', [EventAttendeeController::class, 'index'])->name('events.attendees.index');
    Route::get('/events/{event}/attend', [EventAttendeeController::class, 'create'])->name('events.attendees.create');
    Route::post('/events/{event}/attend', [EventAttendeeController::class, 'store'])->name('events.attendees.store');
    Route::patch('/events/{event}/attendees/{attendee}', [EventAttendeeController::class, 'update'])->name('events.attendees.update');
    Route::delete('/events/{event}/attendees/{attendee}', [EventAttendeeController::class, 'destroy'])->name('events.attendees.destroy');

    Route::get('/my_events', [EventController::class, 'myEvents'])->name('my_events');
    Route::get('/event_list', [EventController::class, 'index'])->name('event_list');







    Route::delete('/photos/{photo}', [PhotoController::class, 'destroy'])->name('photos.destroy');

    Route::get('/add_event', function () {return view('events.create');});
    Route::get('/account', function () {return view('account');});
    Route::get('/map', [MainMapController::class, 'showMap']);
    Route::get('/help', function () {return view('help');});
    Route::get('/my-attendances', [UserAttendancesController::class, 'index'])->name('user.attendances');
});



require __DIR__.'/auth.php';
require __DIR__ . '/api.php';

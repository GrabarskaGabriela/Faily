<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventAttendeeController;
use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\Api\RideController;
use App\Http\Controllers\Api\RideRequestController;
use App\Http\Controllers\Api\TestApiController;

Route::prefix('api')->name('api.')->group(function () {
    Route::get("/test", [TestApiController::class, "test"]);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        // Auth
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'me']);
            Route::put('/', [UserController::class, 'update']);
            Route::post('/photo', [UserController::class, 'updatePhoto']);
            Route::put('/password', [UserController::class, 'updatePassword']);
            Route::put('/settings', [UserController::class, 'updateSettings']);
        });

        Route::apiResource('events', EventController::class);
        Route::get('/events/popular', [EventController::class, 'popular']);
        Route::get('/events/upcoming', [EventController::class, 'upcoming']);

        Route::get('/events/{event}/attendees', [EventAttendeeController::class, 'index']);
        Route::post('/events/{event}/attendees', [EventAttendeeController::class, 'store']);
        Route::patch('/events/{event}/attendees/{attendee}', [EventAttendeeController::class, 'update']);
        Route::delete('/events/{event}/attendees/{attendee}', [EventAttendeeController::class, 'destroy']);
        Route::get('/my-attending', [EventAttendeeController::class, 'myEvents']);
        Route::get('/my-attendees', [EventAttendeeController::class, 'myAttendees']);

        Route::get('/events/{event}/photos', [PhotoController::class, 'index']);
        Route::post('/photos', [PhotoController::class, 'store']);
        Route::delete('/photos/{photo}', [PhotoController::class, 'destroy']);

        Route::apiResource('rides', RideController::class);

        Route::apiResource('ride-requests', RideRequestController::class)->except(['show', 'edit']);
        Route::get('/my-ride-requests', [RideRequestController::class, 'myRequests']);
    });
});



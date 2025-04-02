<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\RideRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('events', EventController::class);
Route::resource('rides', RideController::class);
Route::resource('ride-requests', RideRequestController::class);

#settings
Route::get('/settings', [UserSettingsController::class, 'edit'])->name('user.settings.edit');
Route::put('/settings', [UserSettingsController::class, 'update'])->name('user.settings.update');
Route::get('/settings/delete', [UserSettingsController::class, 'showDeleteForm'])->name('user.settings.delete.form');
Route::delete('/settings', [UserSettingsController::class, 'destroy'])->name('user.settings.destroy');

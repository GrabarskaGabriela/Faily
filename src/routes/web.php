<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;
use App\Http\Controllers\RideController;
use App\Http\Controllers\RideRequestController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('/welcome', function () {
    return view('welcome');
});
Route::get('/dodawanie_wydarzen', function () {
    return view('dodawanie_wydarzen');
});
Route::get('/logowanie', function () {
    return view('logowanie');
});
Route::get('/konto', function () {
    return view('konto');
});
Route::get('/lista_wydarzen', function () {
    return view('lista_wydarzen');
});
Route::get('/main', function () {
    return view('main');
});
Route::get('/przypomnij_haslo', function () {
    return view('przypomnij_haslo');
});
Route::get('/rejestracja', function () {
    return view('rejestracja');
});
Route::get('/ustawienia', function () {
    return view('ustawienia');
});
Route::get('/wydarzenie', function () {
    return view('wydarzenie');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('events', EventController::class);
Route::resource('rides', RideController::class);
Route::resource('ride-requests', RideRequestController::class);


require __DIR__.'/auth.php';

<?php

use App\Http\Middleware\AuthCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::get('/airport', [\App\Http\Controllers\AirportController::class, 'search']);
Route::get('/flight', [\App\Http\Controllers\FlightController::class, 'search']);

Route::post('/booking', [\App\Http\Controllers\BookingController::class, 'store']);
Route::get('/booking/{code}', [\App\Http\Controllers\BookingController::class, 'getByCode']);
Route::get('/booking/{code}/seat', [\App\Http\Controllers\BookingController::class, 'getSeatedPlaces']);

Route::patch('/booking/{code}/seat', [\App\Http\Controllers\BookingController::class, 'replaceSeat']);

Route::middleware(AuthCheck::class)->group(function () {
    Route::get('/user/booking', [\App\Http\Controllers\UserController::class, 'getBookings']);
    Route::get('/user', [\App\Http\Controllers\UserController::class, 'index']);
});

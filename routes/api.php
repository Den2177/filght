<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('/airport', [\App\Http\Controllers\AirportController::class, 'search']);
Route::get('/flight', [\App\Http\Controllers\FlightController::class, 'search']);

Route::post('/booking', [\App\Http\Controllers\BookingController::class, 'store']);
Route::get('/booking/{code}', [\App\Http\Controllers\BookingController::class, 'getByCode']);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/status', function () {
    return response()->json(['status' => 'API is working']);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/getCountries', [AuthController::class, 'getCountries']);
Route::post('/getStates', [AuthController::class, 'getStates']);
Route::post('/getCity', [AuthController::class, 'getCity']);


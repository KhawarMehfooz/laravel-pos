<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

// Health check route
Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => 'hello world',
        'version' => 'v1'
    ]);
});

// Public auth routes
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/logout', 'logout');
        Route::get('/me', 'me');
    });
});
<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::prefix('parking')->group(function () {
    // Route::get('closest', [ParkingController::class, 'closest']);
    // Route::post('create', [ParkingController::class, 'create']);
    // Route::put('update/{id}', [ParkingController::class, 'update']);
    });
});

Route::fallback(function () {
    return response()->json(['message' => 'Route not found'], 404);
});

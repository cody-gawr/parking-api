<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParkingController;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware([
    ForceJsonResponse::class
])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // Closest parking lookup
        Route::get('parkings/closest', [ParkingController::class, 'closest']);
        // Parking CRUD
        Route::apiResource('parkings', ParkingController::class);
    });

    Route::fallback(function () {
        return response()->json(['message' => 'Route not found'], 404);
    });

});

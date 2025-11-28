<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\ProviderController;
use Illuminate\Support\Facades\Route;

// Authenticated API Routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Customer Appointment Routes
    Route::prefix('appointments')->group(function () {
        Route::get('/', [AppointmentController::class, 'index']);
        Route::post('/', [AppointmentController::class, 'store']);
        Route::get('/{appointment}', [AppointmentController::class, 'show']);
        Route::post('/{appointment}/cancel', [AppointmentController::class, 'cancel']);
    });

    // Get available slots for a provider
    Route::get('/providers/{provider}/available-slots', [AppointmentController::class, 'availableSlots']);

    // Provider Routes
    Route::prefix('provider')->middleware('role:provider')->group(function () {
        Route::get('/statistics', [ProviderController::class, 'statistics']);
        Route::get('/appointments', [ProviderController::class, 'appointments']);
        Route::post('/appointments/{appointment}/status', [ProviderController::class, 'updateAppointmentStatus']);
        Route::get('/wallet', [ProviderController::class, 'wallet']);
        Route::get('/wallet/transactions', [ProviderController::class, 'walletTransactions']);
        Route::get('/reviews', [ProviderController::class, 'reviews']);
    });
});

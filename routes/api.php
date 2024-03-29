<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {

    Route::get('cars', [CarController::class, 'availableCars'])
        ->name('available_cars');

    Route::prefix('bookings')->group(function () {

        Route::post('', [BookingController::class, 'store'])
            ->name('create_booking');
        Route::patch('/{booking}', [BookingController::class, 'update'])
            ->name('update_booking');
        Route::delete('/{booking}', [BookingController::class, 'destroy'])
            ->name('delete_booking');

    });

});
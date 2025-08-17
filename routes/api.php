<?php

use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/me', [UserController::class, 'me']);

    Route::get('/hotels', [HotelController::class, 'index']);
    Route::get('/hotels/{id}', [HotelController::class, 'show']);


    Route::get('/hotels/{hotelId}/rooms', [RoomController::class, 'index']);
    Route::get('/rooms/{id}', [RoomController::class, 'show']);

    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::middleware('role:customer')->group(function () {
        Route::post('/reservations', [ReservationController::class, 'store']);
    });
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);
    Route::put('/reservations/{id}', [ReservationController::class, 'update']);

    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);

    Route::get('/hotels/{hotelId}/available-rooms', [ReservationController::class, 'availableRooms']);

    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        // Hotels
        Route::post('/hotels', [HotelController::class, 'store']);
        Route::put('/hotels/{id}', [HotelController::class, 'update']);
        Route::delete('/hotels/{id}', [HotelController::class, 'destroy']);

        // Rooms
        Route::post('/hotels/{hotelId}/rooms', [RoomController::class, 'store']);
        Route::put('/rooms/{id}', [RoomController::class, 'update']);
        Route::delete('/rooms/{id}', [RoomController::class, 'destroy']);

        // Reservations
        Route::put('/reservations/{id}/start', [ReservationController::class, 'startReservation']);
        Route::put('/reservations/{id}/close', [ReservationController::class, 'closeReservation']);
    });
});

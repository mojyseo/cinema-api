<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoomController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReservationController;

//API routes
Route::apiResource('rooms', RoomController::class);
Route::apiResource('movies', MovieController::class);

Route::post('/reservations/book', [ReservationController::class, 'bookSeat']);
Route::get('/reservations/{room_id}/{movie_id}/unavailable-seats', [ReservationController::class, 'getUnavailableSeats']);
Route::get('/reservation/rooms/{roomId}', [ReservationController::class, 'availableMoviesForToday']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

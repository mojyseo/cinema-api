<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Movie;
use App\Models\Reservation;

class ReservationController extends Controller
{

    public function availableMoviesForToday($roomId)
    {
        $today = Carbon::today();

        $todayMovies = Movie::with('room:id,name')
            ->where('room_id', $roomId)
            ->whereDate('show_datetime', $today)
            ->get();

        if ($todayMovies->isEmpty()) {
            return response()->json(['message' => 'Sorry, No Movies Are Available Today for This Room.'], 200);
        }

        return response()->json($todayMovies);
    }

    // Book a seat
    public function bookSeat(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'movie_id' => 'required|exists:movies,id',
            'row_number' => 'required|integer|min:0|max:9', //  10 rows
            'seat_number' => 'required|integer|min:0|max:7', //  8 seats per row
        ]);

        // Check if seat is already taken
        $existingReservation = Reservation::where('room_id', $validated['room_id'])
            ->where('movie_id', $validated['movie_id'])
            ->where('row_number', $validated['row_number'])
            ->where('seat_number', $validated['seat_number'])
            ->first();

        if ($existingReservation) {
            return response()->json(['message' => 'Seat already taken'], 400);
        }

        $reservation = Reservation::create($validated);
        return response()->json($reservation, 201);
    }

    // Get unavailable seats for a movie in a specific room
    public function getUnavailableSeats($roomId, $movieId)
    {
        // Get the reserved seats for the specified room and movie
        $takenSeats = Reservation::where('room_id', $roomId)
            ->where('movie_id', $movieId)
            ->get(['row_number', 'seat_number']);

        $seatsArray = array_fill(0, 10, array_fill(0, 8, false));

        // Mark the taken seats as true in the seats array
        foreach ($takenSeats as $seat) {
            $seatsArray[$seat->row_number][$seat->seat_number] = true;
        }

        return response()->json($seatsArray);
    }
}

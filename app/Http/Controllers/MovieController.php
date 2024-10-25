<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        return Movie::with('room:id,name')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'poster_url' => 'required|url',
            'room_id' => 'required|exists:rooms,id',
            'show_datetime' => 'required',
        ]);

        $movie = Movie::create($request->all());
        $addedMovie = Movie::with('room:id,name')->find($movie->id);
        return response()->json($addedMovie, 201);
    }

    public function show(Movie $movie)
    {
        return $movie->load('room');
    }

    public function update(Request $request, Movie $movie)
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'poster_url' => 'sometimes|url',
            'room_id' => 'sometimes|exists:rooms,id',
            'show_datetime' => 'sometimes|required',
        ]);

        $movie->update($request->all());
        $updatedMovie = Movie::with('room:id,name')->find($movie->id);

        return response()->json($updatedMovie, 200);
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();
        return response()->json(null, 204);
    }
}

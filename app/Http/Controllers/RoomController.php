<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return Room::with('movies')->get();
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:rooms', 'color' => 'required|string|max:255']);

        $room = Room::create($request->all());
        return response()->json($room, 201);
    }

    public function show(Room $room)
    {
        return $room->load('movies');
    }

    public function update(Request $request, Room $room)
    {
        $request->validate(['name' => 'string|max:255']);

        $room->update($request->all());
        return response()->json($room, 200);
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return response()->json(null, 204);
    }
}

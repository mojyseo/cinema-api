<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['room_id', 'movie_id', 'row_number', 'seat_number', 'user_id'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

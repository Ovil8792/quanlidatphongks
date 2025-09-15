<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room_reservation extends Model
{
    protected $fillable = [
        'room_id',
        'user_id',
        'start_time',
        'end_time',
        'reserved_quantity',
        'total_price',
        'status',
        'special_requests',
        'guest_name',
        'guest_email',
        'guest_phone',
        'temp_uid',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

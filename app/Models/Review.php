<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'userid',
        'roomid',
        'rating',
        'comment',
    ];
    public function room()
    {
        return $this->belongsTo(Room::class, 'roomid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userid');
    }
}

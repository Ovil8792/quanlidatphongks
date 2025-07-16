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
    public function Review()  {
        return $this->belongsTo(Room::class);
    }
}

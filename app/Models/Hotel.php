<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $fillable = ["name","address","description","rooms","pimage"];
    public function rooms()
    {
        return $this->hasMany(Room::class, 'hotel_id');
    }
}

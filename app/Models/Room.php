<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ["name","code","floor","requirements", "category_id", "description", "amenities", "base_price", "pimage","old_img", "room_area", "bathroom_area", "max_guests", "bed_count", "status"];
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    


    public function reservations()
    {
        return $this->hasMany(Room_reservation::class);
    }
}

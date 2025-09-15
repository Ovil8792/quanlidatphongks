<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempUser extends Model
{
    protected $fillable = [
        'temp_uid',
        'user_agent',
        'ip_address',
        'booking_data',
    ];

    protected $casts = [
        'booking_data' => 'array',
    ];
}



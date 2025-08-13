<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $fillable = [
        'user_id',
        'total',
        'status',
        'note',
        'payment_date',
    ];

    // Quan hệ: Hóa đơn thuộc về 1 user (khách hàng)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    public function details()
    {
        return $this->hasMany(DetailedBill::class, 'id_bill');
    }
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    // Định dạng thời gian nếu muốn (không bắt buộc)
    protected $casts = [
        'payment_date' => 'datetime',
        'booking_date' => 'datetime',
        'checkin' => 'datetime',
        'checkout' => 'datetime',
    ];
}

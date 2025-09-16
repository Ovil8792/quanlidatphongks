<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistical extends Model
{
    protected $fillable = [
        'date',
        'total_bookings',
        'total_customers',
        'total_revenue',
        'rooms_occupied',
        'rooms_available',
    ];
    protected $primaryKey = 'id';
    protected $table = 'statisticals';

    public function scopeDaily($query)
    {
        return $query->whereDate('date', now()->today());
    }
    public function scopeMonthly($query)
    {
        return $query->whereMonth('date', now()->month);
    }
    public function scopeYearly($query)
    {
        return $query->whereYear('date', now()->year);
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    public function Admin() {
        return $this->belongsTo(Category::class);
    }
}

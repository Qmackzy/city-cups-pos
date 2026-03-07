<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'user_id', 'start_time', 'end_time', 
        'starting_cash', 'total_cash_expected', 
        'total_cash_actual', 'difference', 'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waste extends Model
{
    use HasFactory;

    // Tambahkan kolom-kolom yang boleh diisi di sini
    protected $fillable = [
        'ingredient_id', 
        'amount', 
        'reason', 
        'waste_date', 
        'loss_amount', 
        'user_id'
    ];

    /**
     * Relasi ke Bahan Baku
     */
    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    /**
     * Relasi ke User (siapa yang mencatat)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
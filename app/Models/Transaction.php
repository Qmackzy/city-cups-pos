<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    protected $fillable = [
    'user_id', 
    'invoice_number', 
    'payment_method', // Pastikan ini ada
    'total_price', 
    'pay_amount', 
    'change_amount'
];

    // TAMBAHKAN INI: Relasi ke User (Kasir)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // TAMBAHKAN INI: Relasi ke Detail Transaksi
    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }
}

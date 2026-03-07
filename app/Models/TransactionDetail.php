<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionDetail extends Model
{
    protected $fillable = ['transaction_id', 'product_id', 'qty', 'price', 'cost_price', 'subtotal'];

    // TAMBAHKAN KODE INI
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }   
    // Relasi ke Produk
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

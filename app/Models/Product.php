<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
    'name',
    'category_id',
    'price',
    'cost_price', // <--- PASTIKAN INI ADA
    'stock',
    'image',
    'is_active',
];

    // Relasi ke Kategori
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // TAMBAHKAN INI: Relasi ke Detail Transaksi
    // Ini penting agar kita bisa mengecek riwayat transaksi sebelum menghapus
    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function ingredients()
{
    return $this->belongsToMany(Ingredient::class)->withPivot('amount');
}
}

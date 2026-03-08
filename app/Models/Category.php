<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    // Relasi ke Produk (agar index tidak error)
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

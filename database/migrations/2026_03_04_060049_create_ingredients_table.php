<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('ingredients', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Contoh: Biji Kopi Arabica
        $table->integer('stock'); // Stok dalam satuan terkecil (gram/ml)
        $table->string('unit'); // g, ml, pcs
        $table->integer('min_stock')->default(500); // Batas peringatan stok menipis
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};

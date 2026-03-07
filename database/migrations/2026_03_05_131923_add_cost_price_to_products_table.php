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
    Schema::table('products', function (Blueprint $table) {
        // Menambahkan kolom harga modal (cost_price) setelah kolom price
        // Kita beri default 0 agar tidak error pada data yang sudah ada
        $table->decimal('cost_price', 15, 2)->after('price')->default(0);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};

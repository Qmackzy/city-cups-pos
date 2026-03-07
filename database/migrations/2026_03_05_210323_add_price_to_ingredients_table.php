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
    Schema::table('ingredients', function (Blueprint $table) {
        $table->decimal('purchase_price', 15, 2)->default(0); // Harga beli kemasan
        $table->decimal('unit_amount', 15, 2)->default(1);   // Isi per kemasan (ml/gr)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            //
        });
    }
};

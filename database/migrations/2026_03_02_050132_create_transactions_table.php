<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained(); // Kasir yang bertugas
    $table->string('invoice_number')->unique();
    $table->integer('total_price');
    $table->integer('pay_amount');
    $table->integer('change_amount');
    $table->timestamps(); // Ini akan mencatat tgl transaksi untuk laporan
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

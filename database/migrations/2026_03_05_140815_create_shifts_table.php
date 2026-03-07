<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
    Schema::create('shifts', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained();
        $table->dateTime('start_time');
        $table->dateTime('end_time')->nullable();
        $table->decimal('starting_cash', 15, 2); // Uang modal di laci
        $table->decimal('total_cash_expected', 15, 2)->default(0); // Hitungan sistem
        $table->decimal('total_cash_actual', 15, 2)->nullable(); // Input manual kasir saat tutup
        $table->decimal('difference', 15, 2)->default(0); // Selisih (jika ada)
        $table->enum('status', ['open', 'closed'])->default('open');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};

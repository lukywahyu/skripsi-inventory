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
        $table->foreignId('inventory_stock_id')->constrained('inventory_stocks'); // Ambil dari stok mana
        $table->date('tanggal_keluar');
        $table->float('jumlah_keluar'); // Berapa Kg
        $table->string('tujuan')->nullable(); // Ke Pasar, Supermarket, dll
        $table->timestamps();
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

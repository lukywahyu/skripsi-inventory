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
    Schema::create('inventory_stocks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('vegetable_id')->constrained('vegetables');
        $table->foreignId('grading_id')->constrained('gradings'); // Agar bisa dilacak ini dari panen kapan
        $table->string('kode_batch')->unique(); // Kode unik, misal: TOMAT-20251226-A
        $table->enum('kualitas', ['Grade A', 'Grade B']);
        $table->float('stok_awal');      // Stok awal hasil grading
        $table->float('stok_saat_ini');  // Stok yang tersisa (berkurang saat terjual)
        $table->date('tanggal_kadaluwarsa'); // KUNCI UTAMA ALGORITMA FEFO
        $table->enum('status', ['available', 'expired', 'sold_out'])->default('available');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_stocks');
    }
};

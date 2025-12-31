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
    Schema::create('incoming_stocks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
        $table->foreignId('vegetable_id')->constrained('vegetables')->onDelete('cascade');
        $table->date('tanggal_masuk');
        $table->float('berat_total_abres'); // Berat kotor sebelum disortir
        $table->enum('status_grading', ['belum', 'sudah'])->default('belum'); // Penanda agar tidak digrading 2x
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_stocks');
    }
};

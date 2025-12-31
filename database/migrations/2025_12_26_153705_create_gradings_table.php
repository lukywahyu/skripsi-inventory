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
    Schema::create('gradings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('incoming_stock_id')->constrained('incoming_stocks')->onDelete('cascade');
        $table->date('tanggal_grading');
        $table->float('berat_grade_a')->default(0);
        $table->float('berat_grade_b')->default(0);
        $table->float('berat_reject')->default(0); // Barang busuk saat datang
        $table->float('penyusutan_loss')->default(0); // Selisih timbangan (misal karena kotoran/air)
        $table->text('catatan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gradings');
    }
};

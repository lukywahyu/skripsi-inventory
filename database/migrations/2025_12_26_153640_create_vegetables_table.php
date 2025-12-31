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
    Schema::create('vegetables', function (Blueprint $table) {
        $table->id();
        $table->string('nama_sayur'); // Contoh: Tomat Beef
        $table->string('satuan');     // Contoh: Kg
        $table->integer('masa_simpan_hari'); // Input default untuk FEFO (misal: 3 hari)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vegetables');
    }
};

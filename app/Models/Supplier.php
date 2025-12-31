<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // Kolom mana saja yang boleh diisi lewat formulir
    protected $fillable = ['nama_petani', 'no_hp', 'alamat'];

    // Relasi: Satu petani bisa punya banyak kiriman stok masuk
    public function incomingStocks()
    {
        return $this->hasMany(IncomingStock::class);
    }
}
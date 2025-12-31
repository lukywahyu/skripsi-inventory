<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vegetable extends Model
{
    use HasFactory;

  protected $fillable = ['nama_sayur', 'satuan', 'masa_simpan_hari', 'image'];

    // Relasi ke stok masuk
    public function incomingStocks()
    {
        return $this->hasMany(IncomingStock::class);
    }

    // Relasi ke stok inventory siap jual
    public function inventoryStocks()
    {
        return $this->hasMany(InventoryStock::class);
    }
}
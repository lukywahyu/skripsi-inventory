<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException; // Untuk pesan error

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['inventory_stock_id', 'tanggal_keluar', 'jumlah_keluar', 'tujuan'];

    public function inventoryStock()
    {
        return $this->belongsTo(InventoryStock::class);
    }

    // --- LOGIKA PENGURANGAN STOK ---
    protected static function booted()
    {
        static::created(function ($transaction) {
            $stok = $transaction->inventoryStock;

            // Cek apakah stok cukup? (Validasi Backend)
            if ($transaction->jumlah_keluar > $stok->stok_saat_ini) {
                throw ValidationException::withMessages([
                    'jumlah_keluar' => 'Stok tidak cukup! Sisa hanya ' . $stok->stok_saat_ini . ' Kg',
                ]);
            }

            // Kurangi stok
            $stok->decrement('stok_saat_ini', $transaction->jumlah_keluar);

            // Jika stok habis (0), ubah status jadi sold_out
            if ($stok->refresh()->stok_saat_ini <= 0) {
                $stok->update(['status' => 'sold_out']);
            }
        });
    }
}
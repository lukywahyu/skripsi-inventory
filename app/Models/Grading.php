<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Untuk olah tanggal

class Grading extends Model
{
    use HasFactory;

    protected $fillable = [
        'incoming_stock_id',
        'tanggal_grading',
        'berat_grade_a',
        'berat_grade_b',
        'berat_reject',
        'penyusutan_loss',
        'catatan'
    ];

    public function incomingStock()
    {
        return $this->belongsTo(IncomingStock::class);
    }

    public function inventoryStocks()
    {
        return $this->hasMany(InventoryStock::class);
    }

    // --- DISINI LOGIKA OTOMATISNYA BERJALAN ---
    protected static function booted()
    {
        static::created(function ($grading) {
            
            // 1. Update status Stok Masuk jadi 'sudah' agar tidak digrading ulang
            $grading->incomingStock->update(['status_grading' => 'sudah']);

            // 2. Ambil Data Sayuran untuk tahu masa simpannya (Logika FEFO)
            $sayuran = $grading->incomingStock->vegetable;
            $masaSimpan = $sayuran->masa_simpan_hari;
            
            // Hitung Tanggal Kadaluwarsa (Tgl Grading + Masa Simpan)
            // Contoh: Grading tgl 27 + 3 hari = Expired tgl 30
            $tglExpired = Carbon::parse($grading->tanggal_grading)->addDays($masaSimpan);

            // 3. Buat Kode Batch Unik (Contoh: TOMAT-20251227-GRADINGID)
            $kodeBatch = strtoupper(substr($sayuran->nama_sayur, 0, 3)) . '-' . date('Ymd') . '-' . $grading->id;

            // 4. Masukkan ke Inventory (Grade A) jika ada isinya
            if ($grading->berat_grade_a > 0) {
                InventoryStock::create([
                    'vegetable_id' => $sayuran->id,
                    'grading_id'   => $grading->id,
                    'kode_batch'   => $kodeBatch . '-A',
                    'kualitas'     => 'Grade A',
                    'stok_awal'    => $grading->berat_grade_a,
                    'stok_saat_ini'=> $grading->berat_grade_a,
                    'tanggal_kadaluwarsa' => $tglExpired, // INI FEFO NYA
                    'status'       => 'available',
                ]);
            }

            // 5. Masukkan ke Inventory (Grade B) jika ada isinya
            if ($grading->berat_grade_b > 0) {
                InventoryStock::create([
                    'vegetable_id' => $sayuran->id,
                    'grading_id'   => $grading->id,
                    'kode_batch'   => $kodeBatch . '-B',
                    'kualitas'     => 'Grade B',
                    'stok_awal'    => $grading->berat_grade_b,
                    'stok_saat_ini'=> $grading->berat_grade_b,
                    'tanggal_kadaluwarsa' => $tglExpired, // INI FEFO NYA
                    'status'       => 'available',
                ]);
            }
        });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'vegetable_id',
        'grading_id',
        'kode_batch',
        'kualitas',
        'stok_awal',
        'stok_saat_ini',
        'tanggal_kadaluwarsa',
        'status'
    ];

    public function vegetable()
    {
        return $this->belongsTo(Vegetable::class);
    }

    public function grading()
    {
        return $this->belongsTo(Grading::class);
    }
}
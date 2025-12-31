<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'vegetable_id',
        'tanggal_masuk',
        'berat_total_abres',
        'status_grading'
    ];

    // Kebalikan HasMany: Stok ini milik siapa?
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function vegetable()
    {
        return $this->belongsTo(Vegetable::class);
    }

    // Satu stok masuk hanya punya satu hasil grading
    public function grading()
    {
        return $this->hasOne(Grading::class);
    }
    // Aksesor untuk label dropdown yang mudah dibaca
public function getLabelTextAttribute()
{
    return "{$this->vegetable->nama_sayur} - {$this->supplier->nama_petani} ({$this->berat_total_abres} Kg)";
}
}
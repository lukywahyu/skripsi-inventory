<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\InventoryStock;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    // Mengatur urutan agar widget ini ada di paling atas (opsional)
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // LOGIKA: Hitung stok yang mau expired 3 hari ke depan (FEFO)
        // Kita hitung data yang statusnya 'available' DAN tanggal kadaluwarsa <= 3 hari dari sekarang
        $hampirExpired = InventoryStock::where('status', 'available')
            ->whereDate('tanggal_kadaluwarsa', '<=', Carbon::now()->addDays(3))
            ->count();

        return [
            // KARTU 1: Total Stok Grade A
            Stat::make('Total Stok Grade A', InventoryStock::where('kualitas', 'Grade A')->sum('stok_saat_ini') . ' Kg')
                ->description('Stok siap jual kualitas terbaik')
                ->icon('heroicon-m-check-badge')
                ->color('success'), // Warna Hijau

            // KARTU 2: Total Stok Grade B
            Stat::make('Total Stok Grade B', InventoryStock::where('kualitas', 'Grade B')->sum('stok_saat_ini') . ' Kg')
                ->description('Stok kualitas standar')
                ->icon('heroicon-m-scale')
                ->color('warning'), // Warna Kuning/Oranye

            // KARTU 3: Peringatan FEFO (Bahaya)
            Stat::make('Peringatan FEFO', $hampirExpired . ' Batch')
                ->description('Batch sayuran hampir busuk (< 3 hari)')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger') // Warna Merah (Biar Gudang Panik & Segera Jual)
                ->chart([7, 3, 10, 5, $hampirExpired]), // Grafik hiasan kecil
        ];
    }
}
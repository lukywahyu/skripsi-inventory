<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\InventoryStock;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

   protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Inventory & Distribusi';
protected static ?int $navigationSort = 2;
  public static function form(Form $form): Form
{
    return $form
        ->schema([
            // DROPDOWN CANGGIH FEFO
            Select::make('inventory_stock_id')
                ->label('Pilih Batch Stok (Urut FEFO)')
                ->options(function () {
                    // Ambil stok yang masih ada (> 0), urutkan berdasarkan Expired terdekat
                    return InventoryStock::where('stok_saat_ini', '>', 0)
                        ->orderBy('tanggal_kadaluwarsa', 'asc') // Logic FEFO
                        ->get()
                        ->mapWithKeys(function ($item) {
                            // Format tampilan: "TOM-2025... (Sisa: 60kg) - Exp: 30 Dec"
                            return [$item->id => "{$item->kode_batch} - {$item->kualitas} (Sisa: {$item->stok_saat_ini} Kg) - Exp: {$item->tanggal_kadaluwarsa}"];
                        });
                })
                ->searchable()
                ->required()
                ->reactive() // Agar bisa validasi sisa stok realtime (opsional)
                ->helperText('Sistem mengurutkan otomatis dari yang paling cepat busuk (FEFO).'),

            DatePicker::make('tanggal_keluar')
                ->default(now())
                ->required(),

            TextInput::make('jumlah_keluar')
                ->numeric()
                ->suffix('Kg')
                ->required()
                ->label('Jumlah Keluar'),

            TextInput::make('tujuan')
                ->label('Tujuan Distribusi')
                ->placeholder('Contoh: Pasar Induk Lembang'),
        ]);
}

  public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('tanggal_keluar')->date(),
            TextColumn::make('inventoryStock.kode_batch')->label('Kode Batch'),
            TextColumn::make('inventoryStock.vegetable.nama_sayur')->label('Sayuran'),
            TextColumn::make('jumlah_keluar')->suffix(' Kg')->label('Keluar'),
            TextColumn::make('tujuan'),
        ])
        ->defaultSort('tanggal_keluar', 'desc')
        
        // --- TAMBAHAN BARU DI SINI (MULAI) ---
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(), // Agar bisa hapus banyak sekaligus
                ExportBulkAction::make(), // Tombol Export Excel
            ]),
        ]);
        // --- TAMBAHAN BARU DI SINI (SELESAI) ---
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
    // Logika: Hanya Admin yang boleh lihat menu ini
public static function canViewAny(): bool
{
    // Jika user yg login role-nya 'admin', return TRUE (Boleh lihat)
    // Jika bukan, return FALSE (Sembunyikan)
    return auth()->user()->role === 'admin';
}
}

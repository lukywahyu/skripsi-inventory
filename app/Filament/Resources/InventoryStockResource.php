<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryStockResource\Pages;
use App\Filament\Resources\InventoryStockResource\RelationManagers;
use App\Models\InventoryStock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Carbon\Carbon;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class InventoryStockResource extends Resource
{
    protected static ?string $model = InventoryStock::class;

   protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
protected static ?string $navigationGroup = 'Inventory & Distribusi';
protected static ?int $navigationSort = 1;
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

 public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('kode_batch')
                ->searchable()
                ->copyable() // Agar bisa dicopy kodenya
                ->label('Kode Batch'),

            TextColumn::make('vegetable.nama_sayur')
                ->label('Sayuran')
                ->sortable(),

            TextColumn::make('kualitas')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Grade A' => 'success', // Hijau
                    'Grade B' => 'warning', // Kuning
                    default => 'gray',
                }),

            TextColumn::make('stok_saat_ini')
                ->numeric()
                ->suffix(' Kg')
                ->label('Sisa Stok'),

            TextColumn::make('tanggal_kadaluwarsa')
                ->date()
                ->sortable()
                ->label('Tgl Expired (FEFO)')
                ->description(fn (InventoryStock $record) => 
                    Carbon::parse($record->tanggal_kadaluwarsa)->diffForHumans()
                ) // Menampilkan tulisan "3 days from now"
                ->color(fn (string $state): string => 
                    Carbon::parse($state)->isPast() ? 'danger' : 'primary'
                ), // Merah jika sudah lewat expired
        ])
        // INI KUNCI FEFO: Urutkan dari tanggal expired terdekat (ASC)
        ->defaultSort('tanggal_kadaluwarsa', 'asc')

        // --- TAMBAHAN BARU DI SINI ---
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
                ExportBulkAction::make(), // Tombol Export Excel
            ]),
        ]);
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
            'index' => Pages\ListInventoryStocks::route('/'),
            'create' => Pages\CreateInventoryStock::route('/create'),
            'edit' => Pages\EditInventoryStock::route('/{record}/edit'),
        ];
    }
    // --- MATIKAN FITUR CREATE MANUAL ---
    public static function canCreate(): bool
    {
        return false;
    }
}

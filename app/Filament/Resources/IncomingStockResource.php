<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncomingStockResource\Pages;
use App\Filament\Resources\IncomingStockResource\RelationManagers;
use App\Models\IncomingStock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class IncomingStockResource extends Resource
{
    protected static ?string $model = IncomingStock::class;

   protected static ?string $navigationIcon = 'heroicon-o-truck';
protected static ?string $navigationGroup = 'Operasional Gudang';
protected static ?int $navigationSort = 1;
    
   public static function form(Form $form): Form
{
    return $form
        ->schema([
            // Pilih Petani dari data yang sudah ada
            Select::make('supplier_id')
                ->relationship('supplier', 'nama_petani')
                ->required()
                ->searchable()
                ->preload()
                ->label('Pilih Petani'),

            // Pilih Sayuran
            Select::make('vegetable_id')
                ->relationship('vegetable', 'nama_sayur')
                ->required()
                ->searchable()
                ->preload()
                ->label('Jenis Sayuran'),

            DatePicker::make('tanggal_masuk')
                ->required()
                ->default(now()) // Otomatis tanggal hari ini
                ->label('Tanggal Penerimaan'),

            TextInput::make('berat_total_abres')
                ->required()
                ->numeric()
                ->suffix('Kg')
                ->label('Berat Total (Kotor/Abres)'),

            // Status kita sembunyikan saja, biarkan default 'belum' dari database
        ]);
}

 public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('tanggal_masuk')
                ->date()
                ->sortable(),

            // Menampilkan nama petani, bukan ID-nya
            TextColumn::make('supplier.nama_petani')
                ->label('Petani')
                ->searchable(),

            // Menampilkan nama sayur
            TextColumn::make('vegetable.nama_sayur')
                ->label('Sayuran')
                ->sortable(),

            TextColumn::make('berat_total_abres')
                ->suffix(' Kg')
                ->label('Berat Awal'),

            TextColumn::make('status_grading')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'belum' => 'danger', // Merah jika belum
                    'sudah' => 'success', // Hijau jika sudah
                })
                ->label('Status Sortir'),
        ])
        ->defaultSort('tanggal_masuk', 'desc'); // Urutkan dari yang terbaru
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
            'index' => Pages\ListIncomingStocks::route('/'),
            'create' => Pages\CreateIncomingStock::route('/create'),
            'edit' => Pages\EditIncomingStock::route('/{record}/edit'),
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

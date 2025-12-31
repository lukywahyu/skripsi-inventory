<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GradingResource\Pages;
use App\Filament\Resources\GradingResource\RelationManagers;
use App\Models\Grading;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\IncomingStock;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class GradingResource extends Resource
{
    protected static ?string $model = Grading::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
protected static ?string $navigationGroup = 'Operasional Gudang';
protected static ?int $navigationSort = 2;
    
   public static function form(Form $form): Form
{
    return $form
        ->schema([
            // Dropdown pintar: Hanya menampilkan stok yang STATUS-nya 'belum'
            Select::make('incoming_stock_id')
                ->label('Pilih Stok Masuk (Abres)')
                ->options(IncomingStock::where('status_grading', 'belum')->get()->pluck('label_text', 'id'))
                ->searchable()
                ->required(),

            DatePicker::make('tanggal_grading')
                ->default(now())
                ->required(),

            // Kita buat 3 kolom berdampingan agar rapi
            TextInput::make('berat_grade_a')
                ->label('Hasil Grade A (Kg)')
                ->numeric()
                ->default(0)
                ->required(),

            TextInput::make('berat_grade_b')
                ->label('Hasil Grade B (Kg)')
                ->numeric()
                ->default(0)
                ->required(),

            TextInput::make('berat_reject')
                ->label('Barang Reject/Busuk (Kg)')
                ->numeric()
                ->default(0)
                ->required(),

            TextInput::make('penyusutan_loss')
                ->label('Penyusutan / Hilang (Kg)')
                ->helperText('Otomatis dihitung sistem (Sisa berat)')
                ->numeric()
                ->default(0),

            Textarea::make('catatan')
                ->columnSpanFull(),
        ]);
}

  public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('tanggal_grading')->date()->sortable(),
            TextColumn::make('incomingStock.vegetable.nama_sayur')->label('Sayuran'),
            TextColumn::make('berat_grade_a')->label('Grade A (Kg)'),
            TextColumn::make('berat_grade_b')->label('Grade B (Kg)'),
            TextColumn::make('berat_reject')->label('Reject'),
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
            'index' => Pages\ListGradings::route('/'),
            'create' => Pages\CreateGrading::route('/create'),
            'edit' => Pages\EditGrading::route('/{record}/edit'),
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

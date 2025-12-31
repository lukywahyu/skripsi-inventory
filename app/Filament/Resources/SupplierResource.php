<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    // --- TAMBAHKAN BARIS INI ---
    protected static ?string $navigationGroup = 'Master Data';
    
    // Ikon biarkan saja
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
  public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('nama_petani')
                ->required()
                ->label('Nama Petani'),
                
            TextInput::make('no_hp')
                ->tel()
                ->label('Nomor HP/WA'),
                
            Textarea::make('alamat')
                ->rows(3)
                ->label('Alamat Lengkap'),
        ]);
}

 public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('nama_petani')->searchable()->sortable(),
            TextColumn::make('no_hp'),
            TextColumn::make('alamat')->limit(50), // Batasi teks agar tidak kepanjangan
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
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

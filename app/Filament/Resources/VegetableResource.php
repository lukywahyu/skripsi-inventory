<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VegetableResource\Pages;
use App\Filament\Resources\VegetableResource\RelationManagers;
use App\Models\Vegetable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
class VegetableResource extends Resource
{
    protected static ?string $model = Vegetable::class;

   protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
protected static ?string $navigationGroup = 'Master Data';
protected static ?int $navigationSort = 2; // Biar muncul di bawah Suppliers
 public static function form(Form $form): Form
{

    
    return $form
        ->schema([
            TextInput::make('nama_sayur')
                ->required()
                ->maxLength(255)
                ->label('Nama Sayuran'),
                
                FileUpload::make('image')
                ->label('Foto Sayuran')
                ->directory('sayuran') // Folder penyimpanan
                ->image() // Pastikan yg diupload gambar
                ->imageEditor(),
                
            TextInput::make('satuan')
                ->required()
                ->placeholder('Kg, Ikat, Gram')
                ->label('Satuan'),
                
            TextInput::make('masa_simpan_hari')
                ->required()
                ->numeric()
                ->suffix('Hari')
                ->label('Estimasi Masa Simpan (FEFO)')
                ->helperText('Berapa lama sayuran ini tahan sebelum busuk?'),
        ]);
}

   public static function table(Table $table): Table
{
    return $table
        ->columns([

            ImageColumn::make('image')
                ->circular() // Biar fotonya bulat (modern look)
                ->label('Foto'),
                
            TextColumn::make('nama_sayur')->searchable()->sortable(),
            TextColumn::make('satuan'),
            TextColumn::make('masa_simpan_hari')
                ->suffix(' Hari')
                ->sortable(),
        ])
        ->filters([
            //
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
            'index' => Pages\ListVegetables::route('/'),
            'create' => Pages\CreateVegetable::route('/create'),
            'edit' => Pages\EditVegetable::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

   protected static ?string $navigationGroup = 'Pengaturan'; // Grup baru
protected static ?string $navigationIcon = 'heroicon-o-users';

  public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('name')
                ->required()
                ->label('Nama Lengkap'),

            TextInput::make('email')
                ->email()
                ->required(),

            TextInput::make('password')
                ->password()
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $context): bool => $context === 'create'),

            // PILIHAN ROLE
            Select::make('role')
                ->options([
                    'admin' => 'Admin Gudang',
                    'owner' => 'Pemilik (Owner)',
                ])
                ->required()
                ->label('Hak Akses'),
        ]);
}

  public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')->searchable(),
            TextColumn::make('email'),
            TextColumn::make('role')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'admin' => 'success', // Hijau
                    'owner' => 'info',    // Biru
                }),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

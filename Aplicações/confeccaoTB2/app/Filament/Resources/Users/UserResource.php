<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Schemas\UserInfolist;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationLabel = 'Usuario';

    //    public static function canAccess(): bool 
    //    {
    //     return auth()->user()?->hasRole('Estoque') ?? false
    //     && auth()->user()?->can('Admin') ?? false;
    // }

    protected static string|\UnitEnum|null $navigationGroup = 'Configurações';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'Usuarios';

    public static function form(Schema $schema): Schema
    {
        return $schema 
        ->schema([
            TextInput::make('name')
                ->label('Nome')
                ->required(),

            TextInput::make('email')
                ->label('E-mail')
                ->email()
                ->required(),

            TextInput::make('password')
                ->label('Senha')
                ->password()
                ->required(fn (string $operation): bool => $operation === 'create') 
                ->dehydrated(fn (?string $state) => filled($state)) 
                ->hiddenOn('view'),

            Select::make('roles')
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->searchable()
                ->label('Cargos / Permissões'),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}

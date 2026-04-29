<?php

namespace App\Filament\Resources\Permissions;

use App\Filament\Resources\Permissions\Pages\CreatePermission;
use App\Filament\Resources\Permissions\Pages\EditPermission;
use App\Filament\Resources\Permissions\Pages\ListPermissions;
use App\Filament\Resources\Permissions\Pages\ViewPermission;
use App\Filament\Resources\Permissions\Schemas\PermissionForm;
use App\Filament\Resources\Permissions\Schemas\PermissionInfolist;
use App\Filament\Resources\Permissions\Tables\PermissionsTable;
// use App\Models\Permission;
use Spatie\Permission\Models\Permission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Configurações';

    protected static ?string $navigationLabel = 'Permissões';
       protected static ?int $navigationSort = 3;

//     protected static ?string $modelLabel = 'Criar Permissão';

//    protected static ?string $pluralModelLabel = 'Permissões';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ShieldCheck;

    protected static ?string $recordTitleAttribute = 'Permissoes';

    public static function form(Schema $schema): Schema
    {
        return $schema
        ->schema([
            \Filament\Forms\Components\TextInput::make('name')
            ->label('Nome da  Permissão')
            ->required()
            ->unique(ignoreRecord:true)
            ->maxLength(255)
            ->columnSpanFull(),

            \Filament\Forms\Components\TextInput::make('guard_name')
            ->label('Nivel da  Permissão')
            ->required()
            ->maxLength(20)
            ->columnSpanFull(),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PermissionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            \Filament\Tables\Columns\TextColumn::make('name')
            ->label('Nome da Permissão')
            ->searchable()
            ->sortable(),

            \Filament\Tables\Columns\TextColumn::make('guard_name')
            ->label('Nive da Permissão')
            ->searchable(),

            \Filament\Tables\Columns\TextColumn::make('created_at')
            ->label('Criada em')
            ->dateTime('d/m/Y')
            ->sortable(),
        ]);

        // ->actions([
        //     \Filament\Tables\Actions\EditAction::make(),
        //     \Filament\Tables\Actions\DeleteAction::make(),
        // ]);
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
            'index' => ListPermissions::route('/'),
            'create' => CreatePermission::route('/create'),
            'view' => ViewPermission::route('/{record}'),
            'edit' => EditPermission::route('/{record}/edit'),
        ];
    }
}

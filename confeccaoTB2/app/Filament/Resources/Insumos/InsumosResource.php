<?php

namespace App\Filament\Resources\Insumos;

use Filament\Tables\Columns\TextColumn;
use Filament\Support\RawJs;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Insumos\Pages\CreateInsumos;
use App\Filament\Resources\Insumos\Pages\EditInsumos;
use App\Filament\Resources\Insumos\Pages\ListInsumos;
use App\Filament\Resources\Insumos\Pages\ViewInsumos;
use App\Filament\Resources\Insumos\Schemas\InsumosForm;
use App\Filament\Resources\Insumos\Schemas\InsumosInfolist;
use App\Filament\Resources\Insumos\Tables\InsumosTable;
use App\Models\Insumos;
use Filament\Forms\Components\Select;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InsumosResource extends Resource
{
    protected static ?string $model = Insumos::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Insumo';

    public static function form(Schema $schema): Schema
    {
           return $schema
            ->schema([
                TextInput::make('nome')
                    ->required()
                    ->maxLength(255)
                    ->label('Nome do Insumo'),
                
                TextInput::make('quantidade_medida')
                    ->required()
                    ->placeholder('Ex: kg, Litros, Unidade')
                    ->label('Unidade de Medida'),
                
                TextInput::make('preco_custo')
                    ->numeric()
                    ->prefix('R$')
                    ->label('Preço de Custo'),
                
                Select::make('estoque')
    ->label('Quantidade em Estoque')
    ->options([
        '0' => 'Vazio (0)',
        '5' => 'Pouco (5)',
        '10' => 'Médio (10)',
        '20' => 'Cheio (20)',
    ])
    ->required()
    ->native(false),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return InsumosInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
  return $table
            ->columns([
                TextColumn::make('nome')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('quantidade_medida')
                    ->label('Unid.'),
                
                TextColumn::make('preco_custo')
                    ->money('BRL')
                    ->sortable()
                    ->label('Custo'),
                
                TextColumn::make('estoque')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->label('Estoque Atual'),
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
            'index' => ListInsumos::route('/'),
            'create' => CreateInsumos::route('/create'),
            'view' => ViewInsumos::route('/{record}'),
            'edit' => EditInsumos::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

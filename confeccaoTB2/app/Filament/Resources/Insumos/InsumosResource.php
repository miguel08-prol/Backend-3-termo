<?php

namespace App\Filament\Resources\Insumos;

use App\Models\Insumos;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Insumos\Pages\CreateInsumos;
use App\Filament\Resources\Insumos\Pages\EditInsumos;
use App\Filament\Resources\Insumos\Pages\ListInsumos;
use App\Filament\Resources\Insumos\Pages\ViewInsumos;
use BackedEnum;

class InsumosResource extends Resource
{
    protected static ?string $model = Insumos::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $recordTitleAttribute = 'nome';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
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

    // Correção para a visualização não aparecer vazia

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid(['md' => 2, 'xl' => 3]) // Layout em Cards
            ->columns([
                Stack::make([
                    Split::make([
                        TextColumn::make('nome')
                            ->weight('bold')
                            ->searchable()
                            ->sortable(),

                        TextColumn::make('preco_custo')
                            ->money('BRL')
                            ->alignEnd()
                            ->grow(false),
                    ]),

                    Split::make([
                        TextColumn::make('quantidade_medida')
                            ->color('gray')
                            ->size('xs')
                            ->formatStateUsing(fn ($state) => "Unid: {$state}"),

                        TextColumn::make('estoque')
                            ->badge()
                            ->color(fn ($state) => $state > 5 ? 'success' : 'warning')
                            ->formatStateUsing(fn ($state) => "Estoque: {$state}")
                            ->grow(false),
                    ]),
                ])
                ->space(3)
                ->extraAttributes([
                    'class' => 'p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm hover:border-primary-500 transition',
                ]),
            ]);
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
}
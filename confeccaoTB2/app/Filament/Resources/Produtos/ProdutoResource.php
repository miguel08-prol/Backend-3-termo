<?php

namespace App\Filament\Resources\Produtos;

use Filament\Tables\Columns\TextColumn;
use Filament\Support\RawJs;
use Filament\Forms\Components\TextInput;
use App\Filament\Resources\Produtos\Pages\CreateProduto;
use App\Filament\Resources\Produtos\Pages\EditProduto;
use App\Filament\Resources\Produtos\Pages\ListProdutos;
use App\Filament\Resources\Produtos\Pages\ViewProduto;
use App\Filament\Resources\Produtos\Schemas\ProdutoForm;
use App\Filament\Resources\Produtos\Schemas\ProdutoInfolist;
use App\Filament\Resources\Produtos\Tables\ProdutosTable;
use App\Models\Produto;
use Filament\Forms\Components\Select;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProdutoResource extends Resource
{
    protected static ?string $model = Produto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Produto';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('nome')
                    ->label('Nome do Produto')
                    ->required()
                    ->maxLength(255),

                TextInput::make('referencia')
                    ->label('Referência')
                    ->placeholder('Ex: REF-001'),

                TextInput::make('preco_venda')
                    ->label('Preço de Venda')
                    ->numeric()
                    ->prefix('R$')
                    ->placeholder('0,00'),

                Select::make('estoque')
                    ->label('Quantidade em Estoque')
                    ->options([
                        0 => 'Esgotado (0)',
                        10 => 'Padrão (10)',
                        20 => 'Médio (20)',
                        50 => 'Alto (50)',
                        100 => 'Atacado (100)',
                    ])
                    ->default(0)
                    ->required()
                    ->native(false),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProdutoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nome')
                    ->label('Produto')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('referencia')
                    ->label('Ref.')
                    ->searchable(),

                TextColumn::make('preco_venda')
                    ->label('Preço')
                    ->money('BRL')
                    ->sortable(),

                TextColumn::make('estoque')
                    ->label('Estoque')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger')
                    ->sortable(),
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
            'index' => ListProdutos::route('/'),
            'create' => CreateProduto::route('/create'),
            'view' => ViewProduto::route('/{record}'),
            'edit' => EditProduto::route('/{record}/edit'),
        ];
    }
}

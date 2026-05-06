<?php

namespace App\Filament\Resources\Produtos;

use App\Models\Produto;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Produtos\Pages\CreateProduto;
use App\Filament\Resources\Produtos\Pages\EditProduto;
use App\Filament\Resources\Produtos\Pages\ListProdutos;
use App\Filament\Resources\Produtos\Pages\ViewProduto;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;
use BackedEnum;

class ProdutoResource extends Resource
{
    protected static ?string $model = Produto::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Vendas';

    protected static ?string $navigationLabel = 'Produto';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Tag;

    protected static ?string $recordTitleAttribute = 'nome';

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

                // Campos que não existem na tabela 'produtos', mas usaremos para criar o estoque
                Select::make('estoque_quantidade')
                    ->label('Quantidade Inicial em Estoque')
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

                TextInput::make('estoque_minimo')
                    ->label('Mínimo para Alerta')
                    ->numeric()
                    ->default(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // Ativa a grade de cards (ajusta conforme a tela)
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Stack::make([
                    // Linha superior: Nome e Preço
                    Split::make([
                        TextColumn::make('nome')
                            ->weight('bold')
                            ->searchable()
                            ->sortable(),

                        TextColumn::make('preco_venda')
                            ->money('BRL')
                            ->alignEnd()
                            ->grow(false),
                    ]),

                    // Linha do meio: Referência e Badge de Estoque
                    Split::make([
                        TextColumn::make('referencia')
                            ->color('gray')
                            ->size('xs')
                            ->formatStateUsing(fn ($state) => $state ? "Ref: {$state}" : 'Sem ref.'),

                        TextColumn::make('estoque.quantidade')
                            ->badge()
                            ->label('Estoque')
                            ->color(fn ($state) => $state > 0 ? 'success' : 'danger')
                            ->formatStateUsing(fn ($state) => "Qtd: {$state}")
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
            'index' => ListProdutos::route('/'),
            'create' => CreateProduto::route('/create'),
            'view' => ViewProduto::route('/{record}'),
            'edit' => EditProduto::route('/{record}/edit'),
        ];
    }
}
<?php

namespace App\Filament\Resources\Pedidos;

use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Filament\Resources\Pedidos\Pages\CreatePedido;
use App\Filament\Resources\Pedidos\Pages\EditPedido;
use App\Filament\Resources\Pedidos\Pages\ListPedidos;
use App\Filament\Resources\Pedidos\Pages\ViewPedido;
use App\Models\Pedido;
use App\Models\Produto;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Table;

class PedidoResource extends Resource
{
    protected static ?string $model = Pedido::class;
    protected static string|\UnitEnum|null $navigationGroup = 'Vendas';
    protected static ?string $navigationLabel = 'Pedido';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;
    protected static ?string $recordTitleAttribute = 'Pedido';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('cliente_id')
                ->relationship('cliente', 'nome')
                ->searchable()
                ->preload()
                ->required()
                ->label('Selecione o Cliente'),

            Select::make('status')
                ->options([
                    'Pendente' => 'Pendente',
                    'Em Produção' => 'Em Produção',
                    'Finalizado' => 'Finalizado',
                ])
                ->default('Pendente')
                ->required(),

            TextInput::make('valor_total')
                ->numeric()
                ->readOnly()
                ->label('Valor total')
                ->prefix('R$'),

            Repeater::make('itens')
                ->relationship('itens')
                ->schema([
                    Select::make('produto_id')
                        ->relationship('produto', 'nome')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->label('Produto')
                        ->live()
                        ->reactive()
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            // Busca o produto e preenche o preço unitário
                            if ($state) {
                                $produto = Produto::find($state);
                                if ($produto && $produto->preco_venda) {
                                    $set('preco_unitario', $produto->preco_venda);
                                }
                                // Garante que quantidade seja 1
                                $set('quantidade', 1);
                            }
                            // Recalcula o total
                            self::calcularTotal($get, $set);
                        })
                        ->columnSpan(2),

                    TextInput::make('quantidade')
                        ->numeric()
                        ->default(1)
                        ->required()
                        ->minValue(1)
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::calcularTotal($get, $set);
                        })
                        ->columnSpan(1),

                    TextInput::make('preco_unitario')
                        ->numeric()
                        ->prefix('R$')
                        ->required()
                        ->readOnly()
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set) {
                            self::calcularTotal($get, $set);
                        })
                        ->columnSpan(1),
                ])
                ->columns(4)
                ->columnSpanFull()
                ->label('Produto do Pedido')
                ->live()
                ->afterStateUpdated(function (Get $get, Set $set) {
                    self::calcularTotal($get, $set);
                })
                ->mutateRelationshipDataBeforeCreateUsing(function (array $data): array {
                    // Garante que os dados estão corretos antes de salvar
                    return $data;
                }),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Stack::make([
                    Split::make([
                        TextColumn::make('cliente.nome')
                            ->weight('bold')
                            ->searchable()
                            ->sortable(),

                        TextColumn::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Pendente' => 'warning',
                                'Em Produção' => 'info',
                                'Finalizado' => 'success',
                                default => 'gray',
                            })
                            ->grow(false),
                    ]),
                    Stack::make([
                        TextColumn::make('valor_total')
                            ->money('BRL')
                            ->size('lg')
                            ->weight('black')
                            ->color('primary'),
                        TextColumn::make('created_at')
                            ->label('Data do Pedido')
                            ->dateTime('d/m/Y H:i')
                            ->size('xs')
                            ->color('gray'),
                    ])->space(1),
                ])
                ->extraAttributes([
                    'class' => 'p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm',
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPedidos::route('/'),
            'create' => CreatePedido::route('/create'),
            'view' => ViewPedido::route('/{record}'),
            'edit' => EditPedido::route('/{record}/edit'),
        ];
    }
    
    public static function calcularTotal(Get $get, Set $set): void
    {
        $itens = $get('itens') ?? [];
        $total = 0;

        if (!empty($itens)) {
            foreach ($itens as $index => $item) {
                $quantidade = (float) ($item['quantidade'] ?? 0);
                $preco = (float) ($item['preco_unitario'] ?? 0);
                $total += $quantidade * $preco;
            }
        }
        
        $set('valor_total', number_format($total, 2, '.', ''));
    }
}
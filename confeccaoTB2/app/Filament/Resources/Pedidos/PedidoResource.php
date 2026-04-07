<?php

namespace App\Filament\Resources\Pedidos;

use App\Filament\Resources\Pedidos\Pages\CreatePedido;
use App\Filament\Resources\Pedidos\Pages\EditPedido;
use App\Filament\Resources\Pedidos\Pages\ListPedidos;
use App\Filament\Resources\Pedidos\Pages\ViewPedido;
use App\Filament\Resources\Pedidos\Schemas\PedidoForm;
use App\Filament\Resources\Pedidos\Schemas\PedidoInfolist;
use App\Filament\Resources\Pedidos\Tables\PedidosTable;
use App\Models\Pedido;
use BackedEnum;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;

class PedidoResource extends Resource
{
    protected static ?string $model = Pedido::class;

   protected static string|\UnitEnum|null $navigationGroup = 'Vendas';

   protected static ?string $navigationLabel = 'Pedido';

    
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ClipboardDocumentList;

    
    protected static ?string $recordTitleAttribute = 'Pedido';

    public static function form(Schema $schema): Schema
    {
        // return PedidoForm::configure($schema);
        return $schema 
        ->schema([
            Select::make('cliente_id')->relationship('cliente','nome')->searchable()->preload()->required()->label('Selecione o Cliente'),

            Select::make('status')->options([
                'Pendente' => 'Pendente',
                'Em Produção' => 'Em Produção',
                'Finalizado' => 'Finalizado',
            ])->default('Pendente')->required(),

            TextInput::make('valor_total')->numeric()->readOnly()->label('Valor total')->prefix('R$'),

           Repeater::make('itens')
    ->relationship('itens')
    ->schema([
        Select::make('produto_id')
            ->relationship('produto', 'nome')
            ->searchable()
            ->preload()
            ->required()
            ->label('Produto')
            ->columnSpan(2),

        TextInput::make('quantidade')
            ->numeric()
            ->default(1)
            ->required()
            ->live(onBlur: true)
            ->afterStateUpdated(fn (Get $get,Set $set) => self::CalcularTotal($get, $set))
            ->columnSpan(1),

        TextInput::make('preco_unitario')
            ->numeric()
            ->prefix('R$')
            ->required()
            ->live(onBlur: true)
            ->afterStateUpdated(fn (Get $get,Set $set) => self::CalcularTotal($get, $set))
            ->columnSpan(1),
    ])
    ->columns(4)
    ->columnSpanFull()
    ->label('Produto do Pedido')
    ->live()
    ->afterStateUpdated(fn (Get $get,Set $set) => self::CalcularTotal($get, $set))
   
        ]);
    }



public static function table(Table $table): Table
    {
        return $table
            // Transforma a tabela em Grid de Cards
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Stack::make([
                    // Linha superior: Nome e Status
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

                    // Linha inferior: Valor e Data
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
                // Adiciona padding e estilo de card via Tailwind
                ->extraAttributes([
                    'class' => 'p-6 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm',
                ]),
            ]);
            // ->filters([])
            // ->actions([
            //     \Filament\Tables\Actions\EditAction::make(),
            // //     \Filament\Tables\Actions\DeleteAction::make(),
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
            'index' => ListPedidos::route('/'),
            'create' => CreatePedido::route('/create'),
            'view' => ViewPedido::route('/{record}'),
            'edit' => EditPedido::route('/{record}/edit'),
        ];
    }
    
    public static function CalcularTotal(Get $get,Set $set): void {
        $itens = $get('itens') ?? [];
        $total = 0;

        foreach ($itens as $item) { 
        $quantidade = (float) ($item['quantidade'] ?? 0);
        $preco = (float) ($item['preco_unitario'] ?? 0);

        $total += $quantidade * $preco;
        }
        $set('valor_total',number_format($total,2,'.',''));
    }
}

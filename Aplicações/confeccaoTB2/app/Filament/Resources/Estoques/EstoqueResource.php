<?php

namespace App\Filament\Resources\Estoques;

use App\Filament\Resources\Estoques\Pages\CreateEstoque;
use App\Filament\Resources\Estoques\Pages\EditEstoque;
use App\Filament\Resources\Estoques\Pages\ListEstoques;
use App\Filament\Resources\Estoques\Pages\ViewEstoque;
use App\Filament\Resources\Estoques\Schemas\EstoqueForm;
use App\Filament\Resources\Estoques\Schemas\EstoqueInfolist;
use App\Filament\Resources\Estoques\Tables\EstoquesTable;
use App\Models\Estoque;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\Layout\Split;

class EstoqueResource extends Resource
{
    protected static ?string $model = Estoque::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Estoque';

    protected static ?string $navigationLabel = 'Fornecedor';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArchiveBox;

    protected static ?string $recordTitleAttribute = 'Estoque';

    public static function form(Schema $schema): Schema
    {
       return $schema
            ->schema([
                Select::make('produto_id')
                    ->relationship('produto', 'nome')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Selecione o Produto')
                    ->columnSpanFull(),

                TextInput::make('quantidade')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->label('Quantidade em Estoque'),

                TextInput::make('quantidade_minima')
                    ->numeric()
                    ->default(1)
                    ->required()
                    ->label('Alerta de Estoque Mínimo'),
            ])->columns(2);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EstoqueInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
       return $table
            // Mantendo o visual de Cards que você usou nos pedidos
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Stack::make([
                    Split::make([
                        TextColumn::make('produto.nome')
                            ->weight('bold')
                            ->searchable()
                            ->sortable(),

                        TextColumn::make('quantidade')
                            ->badge()
                            ->color(fn ($record): string => 
                                $record->quantidade <= $record->quantidade_minima ? 'danger' : 'success'
                            )
                            ->grow(false),
                    ]),

                    Stack::make([
                        TextColumn::make('quantidade_minima')
                            ->label('Mínimo')
                            ->formatStateUsing(fn ($state) => "Mínimo aceitável: {$state}")
                            ->size('xs')
                            ->color('gray'),

                        TextColumn::make('updated_at')
                            ->label('Última atualização')
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEstoques::route('/'),
            'create' => CreateEstoque::route('/create'),
            'view' => ViewEstoque::route('/{record}'),
            'edit' => EditEstoque::route('/{record}/edit'),
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

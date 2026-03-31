<?php

namespace App\Filament\Resources\Fornecedors;

use App\Filament\Resources\Fornecedors\Pages\CreateFornecedor;
use App\Filament\Resources\Fornecedors\Pages\EditFornecedor;
use App\Filament\Resources\Fornecedors\Pages\ListFornecedors;
use App\Filament\Resources\Fornecedors\Pages\ViewFornecedor;
use App\Models\Fornecedor;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\Layout\Stack; // Adicionado
use Filament\Tables\Columns\Layout\Split; // Adicionado
use Filament\Support\RawJs;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FornecedorResource extends Resource
{
    protected static ?string $model = Fornecedor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Fornecedor';

protected static ?string $modelLabel = 'Fornecedor';


protected static ?string $pluralModelLabel = 'Fornecedor';

    protected static ?string $recordTitleAttribute = 'nome';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('nome')
                    ->required()
                    ->label('Razão Social / Nome'),
                
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->label('Email'),
                
                TextInput::make('telefone')
                    ->required()
                    ->label('Telefone/Zap')
                    ->mask('(99) 99999-9999'),
                
                TextInput::make('documento')
                    ->required()
                    ->label('CPF ou CNPJ')
                    ->mask(RawJs::make(<<<'JS'
                        $input.length > 14 ? '99.999.999/9999-99' : '999.999.999-99'
                    JS)),
            ]);
    }

    /**
     * Define como as informações aparecem na tela de Visualização (View).
     */


    public static function table(Table $table): Table
    {
        return $table
            // Ativa o layout de grade (Cards)
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Stack::make([
                    // Nome do Fornecedor em destaque
                    TextColumn::make('nome')
                        ->weight('bold')
                        ->size('lg')
                        ->searchable()
                        ->sortable(),

                    // Email e Telefone lado a lado
                    Split::make([
                        TextColumn::make('email')
                            ->icon('heroicon-m-envelope')
                            ->color('gray')
                            ->size('xs')
                            ->searchable(),

                        TextColumn::make('telefone')
                            ->icon('heroicon-m-phone')
                            ->color('gray')
                            ->size('xs')
                            ->alignEnd(),
                    ]),

                    // Documento (CPF/CNPJ) na parte inferior
                    TextColumn::make('documento')
                        ->label('Documento')
                        ->fontFamily('mono')
                        ->color('primary')
                        ->size('xs')
                        ->formatStateUsing(fn ($state) => "Doc: {$state}"),
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
            'index' => ListFornecedors::route('/'),
            'create' => CreateFornecedor::route('/create'),
            'view' => ViewFornecedor::route('/{record}'),
            'edit' => EditFornecedor::route('/{record}/edit'),
        ];
    }
}
<?php

namespace App\Filament\Resources\Estoques\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EstoqueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('produto_id')
                    ->required()
                    ->numeric(),
                TextInput::make('quantidade')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('quantidade_minima')
                    ->required()
                    ->numeric()
                    ->default(1),
            ]);
    }
}

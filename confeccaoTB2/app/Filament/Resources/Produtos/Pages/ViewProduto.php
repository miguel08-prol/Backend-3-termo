<?php

namespace App\Filament\Resources\Produtos\Pages;

use App\Filament\Resources\Produtos\ProdutoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewProduto extends ViewRecord
{
    protected static string $resource = ProdutoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }

    /**
     * Garante que os campos de estoque sejam preenchidos na visualização.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $estoque = $this->record->estoque;

        if ($estoque) {
            $data['estoque_quantidade'] = $estoque->quantidade;
            $data['estoque_minimo'] = $estoque->quantidade_minima;
        }

        return $data;
    }
}
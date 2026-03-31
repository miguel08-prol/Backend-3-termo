<?php

namespace App\Filament\Resources\Produtos\Pages;

use App\Filament\Resources\Produtos\ProdutoResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Estoque;

class CreateProduto extends CreateRecord
{
    protected static string $resource = ProdutoResource::class;

    /**
     * Prepara os dados antes de criar o registro de Produto
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Guardamos os dados do estoque numa variável da classe
        $this->estoqueData = [
            'quantidade' => $data['estoque_quantidade'] ?? 0,
            'quantidade_minima' => $data['estoque_minimo'] ?? 1,
        ];

        // Removemos os campos que não pertencem à tabela 'produtos'
        unset($data['estoque_quantidade']);
        unset($data['estoque_minimo']);

        return $data;
    }

    /**
     * Roda logo após o Produto ser salvo no banco
     */
    protected function afterCreate(): void
    {
        // Cria o estoque vinculado ao ID do produto que acabou de ser criado
        $this->record->estoque()->create([
            'quantidade' => $this->estoqueData['quantidade'],
            'quantidade_minima' => $this->estoqueData['quantidade_minima'],
        ]);
    }
}
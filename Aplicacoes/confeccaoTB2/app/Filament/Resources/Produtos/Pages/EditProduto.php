<?php

namespace App\Filament\Resources\Produtos\Pages;

use App\Filament\Resources\Produtos\ProdutoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProduto extends EditRecord
{
    protected static string $resource = ProdutoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * Preenche o formulário com os dados vindos da relação de estoque.
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

    /**
     * Remove os campos de estoque dos dados de 'produtos' antes de salvar,
     * evitando o erro de "Column not found".
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Guardamos para usar no afterSave
        $this->dadosEstoqueParaAtualizar = [
            'quantidade' => $data['estoque_quantidade'] ?? 0,
            'quantidade_minima' => $data['estoque_minimo'] ?? 1,
        ];

        unset($data['estoque_quantidade']);
        unset($data['estoque_minimo']);

        return $data;
    }

    /**
     * Atualiza a tabela de estoques após salvar o produto.
     */
    protected function afterSave(): void
    {
        $this->record->estoque()->updateOrCreate(
            ['produto_id' => $this->record->id],
            $this->dadosEstoqueParaAtualizar
        );
    }
}
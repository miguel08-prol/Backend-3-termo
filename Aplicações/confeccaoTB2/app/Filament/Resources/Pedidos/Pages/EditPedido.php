<?php

namespace App\Filament\Resources\Pedidos\Pages;

use App\Filament\Resources\Pedidos\PedidoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPedido extends EditRecord
{
    protected static string $resource = PedidoResource::class;

protected function afterSave(): void
{
    $pedido = $this->record;

    $total = $pedido->itens()->get()->sum(function ($item) {
        return $item->quantidade * $item->preco_unitario;
    });

    $pedido->updateQuietly(['valor_total' => $total]); 
}

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

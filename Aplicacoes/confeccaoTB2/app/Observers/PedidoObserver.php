<?php

namespace App\Observers;

use App\Models\Pedido;
use App\Notifications\PedidoCriadoClienteNotification;
use Illuminate\Support\Facades\Log;

class PedidoObserver
{
    public function created(Pedido $pedido): void
    {
        Log::info('Observer executado para pedido ID: ' . $pedido->id);
        
        // Carrega os relacionamentos
        $pedido->load(['cliente', 'itens.produto']);
        
        // Verifica se o cliente existe e tem email
        if ($pedido->cliente && $pedido->cliente->email) {
            Log::info('Cliente tem email: ' . $pedido->cliente->email);
            
            try {
                $pedido->cliente->notify(new PedidoCriadoClienteNotification($pedido));
                Log::info('E-mail enviado com sucesso para: ' . $pedido->cliente->email);
            } catch (\Exception $e) {
                Log::error('Erro ao enviar e-mail: ' . $e->getMessage());
            }
        } else {
            Log::warning('Cliente sem email cadastrado');
        }
    }
}
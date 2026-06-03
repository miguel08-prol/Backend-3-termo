<?php

namespace App\Filament\Resources\Pedidos\Pages;

use App\Filament\Resources\Pedidos\PedidoResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CreatePedido extends CreateRecord
{
    protected static string $resource = PedidoResource::class;
    
    protected function afterCreate(): void
    {
        $record = $this->record;
        
        // Carrega os relacionamentos
        $record->load(['cliente', 'itens.produto']);
        
        // 🔥 DAR BAIXA NO ESTOQUE 🔥
        $this->baixarEstoque($record);
        
        // Envia e-mail para o cliente
        if ($record->cliente && $record->cliente->email) {
            try {
                $this->sendEmail($record);
                
                Log::info('E-mail enviado com sucesso para: ' . $record->cliente->email);
                
                \Filament\Notifications\Notification::make()
                    ->title('✅ E-mail enviado com sucesso!')
                    ->body("Notificação enviada para {$record->cliente->email}")
                    ->success()
                    ->send();
                    
            } catch (\Exception $e) {
                Log::error('Erro ao enviar e-mail: ' . $e->getMessage());
                
                \Filament\Notifications\Notification::make()
                    ->title('❌ Erro ao enviar e-mail')
                    ->body($e->getMessage())
                    ->danger()
                    ->send();
            }
        } else {
            Log::warning('Cliente não tem email cadastrado');
            
            \Filament\Notifications\Notification::make()
                ->title('⚠️ Atenção!')
                ->body("Este cliente não possui e-mail cadastrado.")
                ->warning()
                ->send();
        }
    }
    
    /**
     * 🔥 FUNÇÃO PARA DAR BAIXA NO ESTOQUE 🔥
     */
    private function baixarEstoque($pedido): void
    {
        $erros = [];
        
        foreach ($pedido->itens as $item) {
            $produto = $item->produto;
            $quantidade = $item->quantidade;
            
            // Verifica se o produto tem estoque
            if (!$produto->estoque) {
                $erros[] = "Produto '{$produto->nome}' não possui estoque cadastrado!";
                continue;
            }
            
            // Verifica se tem estoque suficiente
            if ($produto->estoque->quantidade < $quantidade) {
                $erros[] = "Produto '{$produto->nome}' tem estoque insuficiente! Disponível: {$produto->estoque->quantidade}, Solicitado: {$quantidade}";
                continue;
            }
            
            // Dá baixa no estoque
            $produto->estoque->decrement('quantidade', $quantidade);
            
            Log::info("Baixa no estoque: {$produto->nome} - {$quantidade} unidades. Estoque restante: " . $produto->estoque->fresh()->quantidade);
        }
        
        // Mostra erros se houver
        if (!empty($erros)) {
            $mensagem = implode("\n", $erros);
            Log::error('Erros ao dar baixa no estoque: ' . $mensagem);
            
            \Filament\Notifications\Notification::make()
                ->title('⚠️ Atenção no Estoque!')
                ->body($mensagem)
                ->warning()
                ->send();
        } else {
            \Filament\Notifications\Notification::make()
                ->title('✅ Estoque atualizado!')
                ->body("Os produtos foram baixados do estoque com sucesso.")
                ->success()
                ->send();
        }
    }
    
    private function sendEmail($record): void
    {
        // ... seu código de e-mail existente ...
        $itensHtml = '';
        foreach ($record->itens as $item) {
            $subtotal = $item->quantidade * $item->preco_unitario;
            $itensHtml .= '
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #e9ecef;"><strong>' . htmlspecialchars($item->produto->nome) . '</strong></td>
                    <td style="padding: 12px; border-bottom: 1px solid #e9ecef; text-align: center;">' . $item->quantidade . '</td>
                    <td style="padding: 12px; border-bottom: 1px solid #e9ecef;">R$ ' . number_format($item->preco_unitario, 2, ',', '.') . '</td>
                    <td style="padding: 12px; border-bottom: 1px solid #e9ecef;"><strong>R$ ' . number_format($subtotal, 2, ',', '.') . '</strong></td>
                </tr>';
        }
        
        $html = '
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Pedido Confirmado</title>
            <style>
                body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; }
                .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 20px rgba(0,0,0,0.08); }
                .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; }
                .header h1 { color: white; margin: 0; font-size: 24px; }
                .order-number { background: rgba(255,255,255,0.2); display: inline-block; padding: 5px 15px; border-radius: 20px; color: white; margin-top: 10px; font-size: 14px; }
                .content { padding: 30px; }
                .greeting { margin-bottom: 20px; }
                .greeting h2 { color: #333; margin: 0 0 10px 0; font-size: 22px; }
                .greeting p { color: #666; margin: 0; }
                .status-badge { display: inline-block; padding: 6px 16px; border-radius: 20px; font-size: 14px; font-weight: 600; margin-bottom: 20px; background: #d4edda; color: #155724; }
                .info-card { background: #f8f9fa; border-radius: 10px; padding: 20px; margin: 20px 0; }
                .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e9ecef; }
                .info-row:last-child { border-bottom: none; }
                .info-label { font-weight: 600; color: #495057; }
                .info-value { color: #212529; }
                .total-box { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px; margin: 20px 0; text-align: center; }
                .total-box .total-value { font-size: 32px; font-weight: bold; margin-top: 5px; }
                .footer { background: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #e9ecef; }
                .footer p { color: #868e96; font-size: 12px; margin: 5px 0; }
                table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                th { background: #f8f9fa; padding: 12px; text-align: left; font-weight: 600; color: #495057; border-bottom: 2px solid #dee2e6; }
                .btn { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-weight: 600; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>✅ Pedido Confirmado!</h1>
                    <div class="order-number">Nº #' . str_pad($record->id, 6, '0', STR_PAD_LEFT) . '</div>
                </div>
                <div class="content">
                    <div class="greeting">
                        <h2>Olá ' . htmlspecialchars($record->cliente->nome) . '!</h2>
                        <p>Recebemos seu pedido e já estamos preparando tudo para você.</p>
                    </div>
                    <div class="status-badge">📍 Status: ' . $record->status . '</div>
                    <div class="info-card">
                        <div class="info-row"><span class="info-label">📅 Data:</span><span class="info-value">' . $record->created_at->format('d/m/Y H:i') . '</span></div>
                        <div class="info-row"><span class="info-label">👤 Cliente:</span><span class="info-value">' . htmlspecialchars($record->cliente->nome) . '</span></div>
                    </div>
                    <h3 style="margin-bottom: 10px;">🛍️ Itens do Pedido</h3>
                    <table>
                        <thead><tr><th>Produto</th><th>Qtd</th><th>Preço</th><th>Subtotal</th></tr></thead>
                        <tbody>' . $itensHtml . '</tbody>
                    </table>
                    <div class="total-box">
                        <div>TOTAL DO PEDIDO</div>
                        <div class="total-value">R$ ' . number_format($record->valor_total, 2, ',', '.') . '</div>
                    </div>
                    <div style="text-align: center;">
                        <a href="' . url('/admin/pedidos/' . $record->id) . '" class="btn">🔍 Acompanhar Pedido</a>
                    </div>
                </div>
                <div class="footer">
                    <p>© ' . date('Y') . ' Sistema de Pedidos - Todos os direitos reservados</p>
                    <p>Este e-mail foi enviado automaticamente. Por favor, não responda.</p>
                </div>
            </div>
        </body>
        </html>';
        
        Mail::send([], [], function ($message) use ($record, $html) {
            $message->to($record->cliente->email, $record->cliente->nome)
                    ->subject('✅ Pedido Confirmado! #' . str_pad($record->id, 6, '0', STR_PAD_LEFT))
                    ->html($html);
        });
    }
}
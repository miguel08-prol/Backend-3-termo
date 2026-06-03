<?php

namespace App\Notifications;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PedidoCriadoClienteNotification extends Notification
{
    use Queueable;

    protected $pedido;

    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Busca os itens do pedido
        $itensList = '';
        foreach ($this->pedido->itens as $item) {
            $subtotal = $item->quantidade * $item->preco_unitario;
            $itensList .= "- {$item->produto->nome}: {$item->quantidade} x R$ " . number_format($item->preco_unitario, 2, ',', '.') . " = R$ " . number_format($subtotal, 2, ',', '.') . "\n";
        }

        return (new MailMessage)
            ->subject('✅ Seu Pedido foi Confirmado! - #' . $this->pedido->id)
            ->greeting('Olá ' . ($this->pedido->cliente->nome ?? 'Cliente') . '!')
            ->line('**Obrigado pela sua compra!** Seu pedido foi recebido com sucesso em nosso sistema.')
            ->line('')
            ->line('**📋 DETALHES DO PEDIDO:**')
            ->line('🔹 **Número do Pedido:** #' . $this->pedido->id)
            ->line('🔹 **Status:** ' . $this->pedido->status)
            ->line('🔹 **Data:** ' . $this->pedido->created_at->format('d/m/Y H:i'))
            ->line('')
            ->line('**🛍️ ITENS DO PEDIDO:**')
            ->line($itensList)
            ->line('')
            ->line('**💰 TOTAL DO PEDIDO: R$ ' . number_format($this->pedido->valor_total, 2, ',', '.') . '**')
            ->line('')
            ->line('📌 **Próximos passos:**')
            ->line('Seu pedido será processado em até 24h úteis.')
            ->line('Você receberá uma nova notificação quando o status for atualizado.')
            ->line('')
            ->line('Agradecemos pela preferência!')
            ->salutation('Atenciosamente,<br>Equipe de Vendas');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'pedido_id' => $this->pedido->id,
            'cliente_nome' => $this->pedido->cliente->nome,
            'valor_total' => $this->pedido->valor_total,
            'status' => $this->pedido->status,
        ];
    }
}
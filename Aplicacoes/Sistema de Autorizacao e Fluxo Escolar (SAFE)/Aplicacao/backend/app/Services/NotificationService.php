<?php

namespace App\Services;

use App\Models\Authorization;
use App\Models\AuthorizationLog;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function sendEmail(string $to, string $subject, string $body, Authorization $authorization): array
    {
        $payload = [
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
            'timestamp' => now()->toIso8601String()
        ];

        try {
            Log::info('[SAFE_NOTIFICATION] Envio de e-mail simulado', $payload);
            $this->createLog($authorization, 'email', $to, 'simulated', $payload);
            return ['success' => true, 'message' => 'E-mail registrado no log'];
        } catch (\Exception $e) {
            $this->createLog($authorization, 'email', $to, 'failed', $payload, $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function sendWhatsAppSimulated(string $phone, string $message, Authorization $authorization): array
    {
        $payload = [
            'to' => $phone,
            'message' => $message,
            'timestamp' => now()->toIso8601String(),
            'simulated' => true
        ];

        Log::info('[SAFE_NOTIFICATION] Simulação de WhatsApp', $payload);
        $this->createLog($authorization, 'whatsapp_simulated', $phone, 'simulated', $payload);
        return ['success' => true, 'message' => 'WhatsApp simulado registrado no log'];
    }

    // NOVO: Notificação informativa para professor (sem ação necessária)
    public function notifyProfessorInformative(Authorization $authorization, User $professor): void
    {
        $subject = "📋 Nova Autorização de Saída - {$authorization->aluno_nome}";
        $body = "Olá Professor(a),\n\nO aluno {$authorization->aluno_nome} da turma {$authorization->turma} foi autorizado a sair às " .
               date('H:i', strtotime($authorization->horario_saida)) . ".\n\nEsta é apenas uma notificação informativa. Nenhuma ação é necessária.";
        
        $this->sendEmail($professor->email, $subject, $body, $authorization);
        
        if ($professor->telefone) {
            $whatsappMsg = "📋 Aluno autorizado: {$authorization->aluno_nome} - {$authorization->turma} às " . date('H:i', strtotime($authorization->horario_saida));
            $this->sendWhatsAppSimulated($professor->telefone, $whatsappMsg, $authorization);
        }
    }

    public function notifyGateway(Authorization $authorization, User $portaria): void
    {
        $subject = "✅ Nova Autorização - {$authorization->aluno_nome}";
        $body = "Aluno: {$authorization->aluno_nome}\nTurma: {$authorization->turma}\nHorário: " . date('H:i', strtotime($authorization->horario_saida));
        $this->sendEmail($portaria->email, $subject, $body, $authorization);
    }

    public function notifyResponsible(Authorization $authorization, string $responsavelContato): void
    {
        $subject = "🔒 Saída autorizada - {$authorization->aluno_nome}";
        $body = "Prezado responsável,\n\nO aluno {$authorization->aluno_nome} teve sua saída autorizada às " .
               date('H:i', strtotime($authorization->horario_saida)) . ".\n\nTurma: {$authorization->turma}";
        
        $this->sendEmail($responsavelContato, $subject, $body, $authorization);
        
        $whatsappMsg = "🔒 SAÍDA AUTORIZADA: {$authorization->aluno_nome} às " . date('H:i', strtotime($authorization->horario_saida));
        $this->sendWhatsAppSimulated($responsavelContato, $whatsappMsg, $authorization);
    }

    private function createLog(Authorization $authorization, string $tipo, string $destinatario, string $status, array $payload, ?string $resposta = null): void
    {
        AuthorizationLog::create([
            'authorization_id' => $authorization->id,
            'tipo' => $tipo,
            'destinatario' => $destinatario,
            'status' => $status,
            'payload' => $payload,
            'resposta' => $resposta
        ]);
    }
}
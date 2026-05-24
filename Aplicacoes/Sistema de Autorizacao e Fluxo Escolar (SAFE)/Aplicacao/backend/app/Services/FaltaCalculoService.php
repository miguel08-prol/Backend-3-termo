<?php

namespace App\Services;

use App\Models\Authorization;
use App\Models\Presenca;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FaltaCalculoService
{
    /**
     * Calcular faltas baseado na hora de entrada (atraso)
     * 
     * @param User $aluno
     * @param string $horarioEntrada Formato H:i
     * @param string $turno manha|tarde|noite
     * @param string $data
     * @param int|null $authorizationId
     * @return array Lista de aulas com falta
     */
    public function calcularFaltasAtraso($aluno, $horarioEntrada, $turno, $data, $authorizationId = null)
    {
        $turnosConfig = config('horarios.turnos');
        $turnoConfig = $turnosConfig[$turno] ?? $turnosConfig['manha'];
        
        $horarioEntradaObj = Carbon::parse($horarioEntrada);
        $aulasComFalta = [];
        
        foreach ($turnoConfig['aulas'] as $numero => $horarios) {
            $fimAula = Carbon::parse($horarios['fim']);
            
            // Se o aluno chegou depois do fim da aula, ele perdeu essa aula
            if ($horarioEntradaObj->gt($fimAula)) {
                $aulasComFalta[] = $numero;
            }
        }
        
        // Registrar as faltas
        foreach ($aulasComFalta as $aulaNumero) {
            $this->registrarFalta($aluno->id, $aulaNumero, $data, $authorizationId);
        }
        
        Log::info("Aluno {$aluno->name} chegou às {$horarioEntrada} e teve falta nas aulas: " . implode(', ', $aulasComFalta));
        
        return $aulasComFalta;
    }
    
    /**
     * Calcular faltas baseado na hora de saída (saída antecipada)
     * 
     * @param User $aluno
     * @param string $horarioSaida Formato H:i
     * @param string $turno manha|tarde|noite
     * @param string $data
     * @param int|null $authorizationId
     * @return array Lista de aulas com falta
     */
    public function calcularFaltasSaidaAntecipada($aluno, $horarioSaida, $turno, $data, $authorizationId = null)
    {
        $turnosConfig = config('horarios.turnos');
        $turnoConfig = $turnosConfig[$turno] ?? $turnosConfig['manha'];
        
        $horarioSaidaObj = Carbon::parse($horarioSaida);
        $aulasComFalta = [];
        
        foreach ($turnoConfig['aulas'] as $numero => $horarios) {
            $inicioAula = Carbon::parse($horarios['inicio']);
            
            // Se o aluno saiu antes do início da aula, ele perde essa aula
            if ($horarioSaidaObj->lt($inicioAula)) {
                $aulasComFalta[] = $numero;
            }
        }
        
        // Registrar as faltas
        foreach ($aulasComFalta as $aulaNumero) {
            $this->registrarFalta($aluno->id, $aulaNumero, $data, $authorizationId);
        }
        
        Log::info("Aluno {$aluno->name} saiu às {$horarioSaida} e teve falta nas aulas: " . implode(', ', $aulasComFalta));
        
        return $aulasComFalta;
    }
    
    /**
     * Calcular faltas baseado na aula específica (quando o professor marca qual aula o aluno vai perder)
     * 
     * @param User $aluno
     * @param int $aulaNumero Aula que o aluno vai perder
     * @param string $turno manha|tarde|noite
     * @param string $data
     * @param int|null $authorizationId
     * @return array Lista de aulas com falta (inclui a aula especificada e as seguintes)
     */
    public function calcularFaltasPorAula($aluno, $aulaNumero, $turno, $data, $authorizationId = null)
    {
        $turnosConfig = config('horarios.turnos');
        $turnoConfig = $turnosConfig[$turno] ?? $turnosConfig['manha'];
        
        $aulasComFalta = [];
        
        // Se sair na aula X, perde as aulas a partir de X (inclusive)
        foreach ($turnoConfig['aulas'] as $numero => $horarios) {
            if ($numero >= $aulaNumero) {
                $aulasComFalta[] = $numero;
            }
        }
        
        // Registrar as faltas
        foreach ($aulasComFalta as $aula) {
            $this->registrarFalta($aluno->id, $aula, $data, $authorizationId);
        }
        
        Log::info("Aluno {$aluno->name} saiu na {$aulaNumero}ª aula e teve falta nas aulas: " . implode(', ', $aulasComFalta));
        
        return $aulasComFalta;
    }
    
    /**
     * Calcular faltas para entrada atrasada baseado na aula específica
     * 
     * @param User $aluno
     * @param int $aulaNumero Aula que o aluno chegou
     * @param string $turno manha|tarde|noite
     * @param string $data
     * @param int|null $authorizationId
     * @return array Lista de aulas com falta
     */
    public function calcularFaltasPorAulaChegada($aluno, $aulaNumero, $turno, $data, $authorizationId = null)
    {
        $turnosConfig = config('horarios.turnos');
        $turnoConfig = $turnosConfig[$turno] ?? $turnosConfig['manha'];
        
        $aulasComFalta = [];
        
        // Se chegou na aula X, perdeu TODAS as aulas anteriores a X
        foreach ($turnoConfig['aulas'] as $numero => $horarios) {
            if ($numero < $aulaNumero) {
                $aulasComFalta[] = $numero;
            }
        }
        
        // Registrar as faltas
        foreach ($aulasComFalta as $aula) {
            $this->registrarFalta($aluno->id, $aula, $data, $authorizationId);
        }
        
        Log::info("Aluno {$aluno->name} chegou na {$aulaNumero}ª aula e teve falta nas aulas: " . implode(', ', $aulasComFalta));
        
        return $aulasComFalta;
    }
    
    /**
     * Registrar uma falta no sistema
     */
    private function registrarFalta($alunoId, $aulaNumero, $data, $authorizationId = null)
    {
        $dataObj = Carbon::parse($data);
        
        // Verificar se já existe registro para esta aula
        $presenca = Presenca::where('aluno_id', $alunoId)
            ->whereDate('data', $dataObj)
            ->where('aula_numero', $aulaNumero)
            ->first();
        
        if (!$presenca) {
            Presenca::create([
                'aluno_id' => $alunoId,
                'data' => $dataObj,
                'aula_numero' => $aulaNumero,
                'status' => 'falta',
                'authorization_id' => $authorizationId
            ]);
        } elseif ($presenca->status !== 'falta') {
            $presenca->update([
                'status' => 'falta',
                'authorization_id' => $authorizationId
            ]);
        }
    }
    
    /**
     * Determinar o turno baseado no horário
     */
    public function determinarTurno($horario)
    {
        $hora = Carbon::parse($horario)->format('H:i');
        
        if ($hora >= '07:00' && $hora < '12:00') {
            return 'manha';
        } elseif ($hora >= '12:00' && $hora < '18:00') {
            return 'tarde';
        } else {
            return 'noite';
        }
    }
    
    /**
     * Obter a aula atual baseado no horário
     */
    public function getAulaAtual($horario, $turno)
    {
        $turnosConfig = config('horarios.turnos');
        $turnoConfig = $turnosConfig[$turno] ?? $turnosConfig['manha'];
        
        $horarioObj = Carbon::parse($horario);
        
        foreach ($turnoConfig['aulas'] as $numero => $horarios) {
            $inicio = Carbon::parse($horarios['inicio']);
            $fim = Carbon::parse($horarios['fim']);
            
            if ($horarioObj->between($inicio, $fim)) {
                return $numero;
            }
        }
        
        // Se o horário for antes da primeira aula
        if ($horarioObj->lt(Carbon::parse($turnoConfig['aulas'][1]['inicio']))) {
            return 0; // Antes da primeira aula
        }
        
        // Se o horário for depois da última aula
        if ($horarioObj->gt(Carbon::parse($turnoConfig['aulas'][5]['fim']))) {
            return 6; // Depois da última aula
        }
        
        return null;
    }

    public function registrarFaltaPorNome($alunoNome, $aulaNumero, $data, $authorizationId = null)
{
    $aluno = User::where('name', $alunoNome)->first();
    
    if (!$aluno) {
        Log::warning("Aluno não encontrado para registrar falta: {$alunoNome}");
        return false;
    }
    
    return $this->registrarFalta($aluno->id, $aulaNumero, $data, $authorizationId);
}
}
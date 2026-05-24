<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Authorization extends Model
{
    protected $table = 'authorizations';
    
protected $fillable = [
    'aluno_nome', 
    'turma', 
    'turno', // ADICIONAR ESTA LINHA
    'motivo_saida', 
    'horario_saida',
    'aula_numero', 
    'status', 
    'admin_id', 
    'professor_id',
    'portaria_id', 
    'com_falta',
    'observacoes', 
    'autorizado_em',
    'tipo'
];

    protected $casts = [
        'horario_saida' => 'datetime:H:i',
        'autorizado_em' => 'datetime',
        'com_falta' => 'boolean'
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function professor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function portaria(): BelongsTo
    {
        return $this->belongsTo(User::class, 'portaria_id');
    }

    public function validation(): HasOne
    {
        return $this->hasOne(ProfessorValidation::class);
    }

    public function gatewayEntry(): HasOne
    {
        return $this->hasOne(GatewayEntry::class);
    }
}
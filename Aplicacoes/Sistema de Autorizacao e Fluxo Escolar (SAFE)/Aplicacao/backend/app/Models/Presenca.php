<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presenca extends Model
{
    protected $fillable = [
        'aluno_id',
        'data',
        'aula_numero',
        'status',
        'authorization_id',
        'justificativa'
    ];

    protected $casts = [
        'data' => 'date'
    ];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aluno_id');
    }

    public function authorization(): BelongsTo
    {
        return $this->belongsTo(Authorization::class);
    }
}
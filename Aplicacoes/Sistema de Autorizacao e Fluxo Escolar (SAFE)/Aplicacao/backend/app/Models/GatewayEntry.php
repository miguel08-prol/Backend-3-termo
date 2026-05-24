<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GatewayEntry extends Model
{
    protected $fillable = [
        'authorization_id', 'portaria_id', 'horario_entrada',
        'horario_saida', 'tipo', 'observacoes'
    ];

    protected $casts = [
        'horario_entrada' => 'datetime',
        'horario_saida' => 'datetime'
    ];

    public function authorization(): BelongsTo
    {
        return $this->belongsTo(Authorization::class);
    }

    public function portaria(): BelongsTo
    {
        return $this->belongsTo(User::class, 'portaria_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuthorizationLog extends Model
{
    protected $fillable = [
        'authorization_id', 'tipo', 'destinatario',
        'status', 'payload', 'resposta'
    ];

    protected $casts = [
        'payload' => 'array'
    ];

    public function authorization(): BelongsTo
    {
        return $this->belongsTo(Authorization::class);
    }
}
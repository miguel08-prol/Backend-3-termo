<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessorValidation extends Model
{
    protected $fillable = [
        'authorization_id', 'professor_id', 'status',
        'com_falta', 'observacao', 'validated_at'
    ];

    protected $casts = [
        'validated_at' => 'datetime',
        'com_falta' => 'boolean'
    ];

    public function authorization(): BelongsTo
    {
        return $this->belongsTo(Authorization::class);
    }

    public function professor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professor_id');
    }
}
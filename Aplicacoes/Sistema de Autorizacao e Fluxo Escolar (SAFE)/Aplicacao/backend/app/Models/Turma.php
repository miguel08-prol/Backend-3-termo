<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Turma extends Model
{

use HasFactory;

    protected $fillable = [
        'nome',
        'codigo',
        'ano',
        'periodo',
        'capacidade',
        'professor_id',
        'status',
        'observacoes'
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function professor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professor_id');
    }
}
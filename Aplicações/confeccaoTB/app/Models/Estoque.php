<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Estoque extends Model
{
    use HasFactory;

    // Define explicitamente a tabela para evitar erros de pluralização do Laravel
    protected $table = 'estoques';

    protected $fillable = [
        'produto_id',
        'quantidade',
        'tipo',
        'motivo'
    ];

    /**
     * Casts: Garante que os dados saiam do banco no formato correto.
     */
    protected $casts = [
        'quantidade' => 'integer',
        'created_at' => 'datetime',
    ];

    /**
     * Relacionamento: Toda movimentação pertence a um produto.
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    /**
     * ACESSOR SÉNIOR: Retorna a classe CSS baseada no tipo de movimentação.
     * Uso na View: {{ $movimentacao->tipo_badge }}
     */
    public function getTipoBadgeAttribute()
    {
        return $this->tipo === 'Entrada' 
            ? 'bg-emerald-100 text-emerald-600 border-emerald-200' 
            : 'bg-rose-100 text-rose-600 border-rose-200';
    }

    /**
     * ACESSOR SÉNIOR: Retorna o ícone do Phosphor baseado no tipo.
     */
    public function getTipoIconAttribute()
    {
        return $this->tipo === 'Entrada' ? 'ph-arrow-up-right' : 'ph-arrow-down-right';
    }
}
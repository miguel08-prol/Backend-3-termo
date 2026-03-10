<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'produto', 'valor', 'quantidade'];

    // Relacionamento com o cliente
    public function cliente()
    {
        return $this->belongsTo(clientes::class, 'cliente_id');
    }

    // Casts para garantir precisão matemática
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'valor'      => 'decimal:2',
        'quantidade' => 'integer',
    ];

    // Atributo dinâmico para facilitar a exibição do total
    public function getTotalAttribute()
    {
        return $this->valor * $this->quantidade;
    }
}
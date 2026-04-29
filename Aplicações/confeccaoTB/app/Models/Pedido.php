<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'produto', 'valor', 'quantidade'];

    public function cliente()
    {
        return $this->belongsTo(clientes::class, 'cliente_id');
    }

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'valor'      => 'decimal:2',
        'quantidade' => 'integer',
    ];

    /**
     * Accessor para o Valor Total
     * Use no Blade como: {{ $pedido->total_pedido }}
     */
    public function getTotalPedidoAttribute()
    {
        return (float) $this->valor * (int) $this->quantidade;
    }
}
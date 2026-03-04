<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'produto', 'valor', 'quantidade'];

    // Relacionamento: Um pedido pertence a um cliente
    public function cliente()
    {
        return $this->belongsTo(clientes::class, 'cliente_id');
    }
}

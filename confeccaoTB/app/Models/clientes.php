<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clientes extends Model
{
    use HasFactory; // Colocado no local correto

    protected $fillable = ['nome', 'cpf', 'email', 'telefone', 'endereco'];

    // Relacionamento: Um cliente tem muitos pedidos
    public function pedidos()
    {
        // Certifica-te que o Model de Pedidos chama-se 'Pedido' ou 'Pedidos'
        return $this->hasMany(Pedido::class, 'cliente_id');
    }
}
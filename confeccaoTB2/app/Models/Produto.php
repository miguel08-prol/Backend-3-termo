<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Produto extends Model
{
    protected $guarded = [];

    // Define a relação com a tabela estoques
public function estoque(): HasOne
{
    return $this->hasOne(Estoque::class);
}

    public function itensPedido() 
    {
        return $this->hasMany(ItemPedido::class);
    }

    public function baixarEstoque(int $quantidade)
    {
        // Note o uso de $this->estoque (relação)
        if (!$this->estoque || $this->estoque->quantidade < $quantidade) {
            throw new \Exception("Estoque insuficiente para o produto: {$this->nome}");
        }

        $this->estoque->decrement('quantidade', $quantidade);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{

 protected $guarded = [];
 
public function itensPedido() 
{
    return $this->hasMany(ItemPedido::class);
}


public function baixarEstoque(int $quantidade)
{
    if ($this->estoque->quantidade < $quantidade) {
        throw new \Exception("Estoque insuficiente para o produto: {$this->nome}");
    }

    $this->estoque->decrement('quantidade', $quantidade);
}
}

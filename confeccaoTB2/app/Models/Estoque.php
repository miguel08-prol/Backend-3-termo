<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
// Adicione isso dentro da classe Estoque
public function estaBaixo()
{
    return $this->quantidade <= $this->quantidade_minima;
}
}

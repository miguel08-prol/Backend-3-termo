<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class clientes extends Model
{
    public function pedidos()
{
    return $this->hasMany(Pedidos::class, 'cliente_id');
}
use HasFactory;
protected $fillable = ['nome','cpf','email','telefone','endereco'];
}



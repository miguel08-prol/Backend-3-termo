<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // 1. Adicione esta linha
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory; // 2. Adicione esta linha dentro da classe

    protected $fillable = ['nome', 'descricao', 'preco', 'estoque'];
}

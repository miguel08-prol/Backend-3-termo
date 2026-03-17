<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'descricao', 'preco', 'estoque'];

    // Garante que o preço seja sempre tratado como um número decimal
    protected $casts = [
        'preco'   => 'decimal:2',
        'estoque' => 'integer',
    ];
}
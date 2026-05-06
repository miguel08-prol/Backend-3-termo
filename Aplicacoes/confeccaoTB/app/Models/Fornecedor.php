<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    use HasFactory;

    // Nome da tabela (verifique se no seu banco é fornecedors ou fornecedores)
    protected $table = 'fornecedors';

    protected $fillable = [
        'nome_fantasia', 
        'razao_social', 
        'cnpj', 
        'email', 
        'telefone'
    ];
}
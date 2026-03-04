<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estoque extends Model
{
    use HasFactory;

    protected $fillable = ['produto_id', 'quantidade', 'tipo', 'motivo'];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}

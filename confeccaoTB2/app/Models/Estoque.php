<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estoque extends Model
{

protected $guarded = [];

    protected $fillable = [
        'produto_id', 
        'quantidade', 
        'quantidade_minima'
    ];

    /**
     * Get the product associated with the stock.
     */
    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }
}
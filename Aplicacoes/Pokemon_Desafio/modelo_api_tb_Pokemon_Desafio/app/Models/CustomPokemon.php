<?php
// app/Models/CustomPokemon.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomPokemon extends Model
{
    protected $table = 'custom_pokemons';
    
    protected $fillable = [
        'name', 'height', 'weight', 'base_experience',
        'image_url', 'stats', 'abilities', 'evolutions', 'types', 'description' // Adicionado description
    ];

    protected $casts = [
        'stats' => 'array',
        'abilities' => 'array',
        'evolutions' => 'array',
        'types' => 'array'
    ];
}
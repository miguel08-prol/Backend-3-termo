<?php

namespace Database\Factories;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

class EstoqueFactory extends Factory
{
    public function definition(): array
    {
        $tipo = fake()->randomElement(['Entrada', 'Saída']);
        return [
            'produto_id' => Produto::pluck('id')->random(),
            'quantidade' => $tipo == 'Entrada' ? fake()->numberBetween(10, 50) : fake()->numberBetween(-20, -1),
            'tipo' => $tipo,
            'motivo' => fake()->randomElement(['Reposição', 'Venda', 'Ajuste de Inventário', 'Devolução']),
        ];
    }
}
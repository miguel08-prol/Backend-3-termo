<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pedido>
 */
class PedidoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
 public function definition(): array
{
    return [
        // Escolhe um ID de um cliente que já existe no banco
        'cliente_id' => \App\Models\clientes::pluck('id')->random(), 
        'produto' => fake()->sentence(3),
        'valor' => fake()->randomFloat(2, 10, 500),
        'quantidade' => fake()->numberBetween(1, 5),
    ];
}
}

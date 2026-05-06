<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produto>
 */
class ProdutoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
  public function definition(): array
{
    return [
        'nome' => fake()->words(3, true), // Nome do produto com 3 palavras
        'descricao' => fake()->sentence(),
        'preco' => fake()->randomFloat(2, 5, 1000), // Preço entre 5 e 1000
        'estoque' => fake()->numberBetween(0, 100),
    ];
}
}

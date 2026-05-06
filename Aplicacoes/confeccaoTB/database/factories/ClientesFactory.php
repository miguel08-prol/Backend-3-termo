<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\clientes>
 */
class ClientesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
public function definition(): array
{
    return [
        'nome' => fake()->name(), // Nome real, não parágrafo
        'cpf' => fake()->numerify('###.###.###-##'),
        'email' => fake()->unique()->safeEmail(),
        'telefone' => fake()->phoneNumber(),
        'endereco' => fake()->address(),
    ];
}
}

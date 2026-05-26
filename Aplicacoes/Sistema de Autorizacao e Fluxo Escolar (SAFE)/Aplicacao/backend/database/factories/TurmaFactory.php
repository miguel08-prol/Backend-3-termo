<?php

namespace Database\Factories;

use App\Models\Turma;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Turma>
 */
class TurmaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
public function definition(): array
{
    return [
        'nome' => 'Turma ' . $this->faker->unique()->bothify('??-####'),
        'codigo' => $this->faker->unique()->lexify('COD-?????'),
        'ano' => '2026',
        'periodo' => $this->faker->randomElement(['Manhã', 'Tarde', 'Noite']),
        'capacidade' => $this->faker->numberBetween(20, 45),
        'professor_id' => null, // Ou User::all()->random()->id se tiver usuários
        'status' => 'ativa',
        'observacoes' => $this->faker->sentence(),
    ];
}
}

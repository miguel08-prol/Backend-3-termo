<?php

namespace Database\Factories;

use App\Models\Authorization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Authorization>
 */
class AuthorizationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
public function definition(): array
{
    return [
        'aluno_nome' => $this->faker->name(),
        'turma' => 'Turma ' . $this->faker->bothify('#?'),
        'turno' => $this->faker->randomElement(['Manhã', 'Tarde', 'Noite']), // Campo que você adicionou no Model
        'motivo_saida' => $this->faker->sentence(),
        'horario_saida' => $this->faker->time('H:i'),
        'aula_numero' => $this->faker->numberBetween(1, 5),
        
        // Use EXATAMENTE um destes: pending, approved_by_professor, completed, cancelled
        'status' => $this->faker->randomElement(['approved_by_professor', 'completed']), 
        
        'admin_id' => \App\Models\User::first()?->id ?? \App\Models\User::factory(),
        'professor_id' => \App\Models\User::first()?->id ?? \App\Models\User::factory(),
        'portaria_id' => null,
        'com_falta' => $this->faker->boolean(),
        'tipo' => $this->faker->randomElement(['saida', 'entrada']),
        'autorizado_em' => now(),
    ];
}
}

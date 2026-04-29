<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FornecedorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nome_fantasia' => fake()->company(),
            'razao_social' => fake()->company() . ' Ltda',
            'cnpj' => fake()->numerify('##.###.###/0001-##'),
            'email' => fake()->unique()->companyEmail(),
            'telefone' => fake()->phoneNumber(),
        ];
    }
}
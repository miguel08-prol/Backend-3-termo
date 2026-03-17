<?php

namespace Database\Seeders;

use App\Models\Estoque;
use Illuminate\Database\Seeder;

class EstoqueSeeder extends Seeder
{
    public function run(): void
    {
        Estoque::factory(50)->create();
    }
}
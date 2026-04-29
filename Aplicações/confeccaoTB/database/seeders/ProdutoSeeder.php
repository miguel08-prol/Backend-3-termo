<?php

namespace Database\Seeders;

use App\Models\Produto; // Importante importar o Model
use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    public function run(): void
    {
        Produto::factory(20)->create();
    }
}
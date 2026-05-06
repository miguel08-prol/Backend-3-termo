<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\clientes;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Criar usuário de teste
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => bcrypt('password')]
        );

        
        clientes::factory(10)->create();

        $this->call([
            ProdutoSeeder::class,
            FornecedorSeeder::class,
            PedidosSeeder::class,  
            EstoqueSeeder::class,
        ]);
    }
}
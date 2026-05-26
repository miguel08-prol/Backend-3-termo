<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Usuário Administrador
        User::updateOrCreate(
            ['email' => 'admin@senai.com.br'],  // <-- CORRIGIDO: admin@senai.com.br
            [
                'name' => 'Administrador SAFE',
                'password' => Hash::make('admin@123'),
                'role' => 'admin',
                'departamento' => 'Administração',
                'telefone' => '(11) 91111-1111',
                'notificacoes_email' => true,
                'notificacoes_push' => true,
            ]
        );

        // Usuário Professor
        User::updateOrCreate(
            ['email' => 'professor@senai.com.br'],  // <-- CORRIGIDO
            [
                'name' => 'Professor Exemplo',
                'password' => Hash::make('professor@123'),
                'role' => 'professor',
                'departamento' => 'Informática',
                'telefone' => '(11) 92222-2222',
                'notificacoes_email' => true,
                'notificacoes_push' => true,
            ]
        );

        // Usuário Portaria (Técnico)
        User::updateOrCreate(
            ['email' => 'portaria@senai.com.br'],  // <-- CORRIGIDO
            [
                'name' => 'Portaria Exemplo',
                'password' => Hash::make('portaria@123'),
                'role' => 'tecnico',
                'departamento' => 'Portaria',
                'telefone' => '(11) 93333-3333',
                'notificacoes_email' => true,
                'notificacoes_push' => false,
            ]
        );

        \App\Models\Turma::factory(50)->create();
        \App\Models\Authorization::factory(50)->create();
    }
}
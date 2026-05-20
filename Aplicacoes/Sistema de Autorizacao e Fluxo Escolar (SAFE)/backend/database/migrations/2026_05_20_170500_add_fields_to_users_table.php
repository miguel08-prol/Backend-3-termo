<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Adicionar colunas se não existirem
            if (!Schema::hasColumn('users', 'departamento')) {
                $table->string('departamento')->nullable()->after('role');
            }
            
            if (!Schema::hasColumn('users', 'telefone')) {
                $table->string('telefone')->nullable()->after('departamento');
            }
            
            if (!Schema::hasColumn('users', 'notificacoes_email')) {
                $table->boolean('notificacoes_email')->default(true)->after('telefone');
            }
            
            if (!Schema::hasColumn('users', 'notificacoes_push')) {
                $table->boolean('notificacoes_push')->default(false)->after('notificacoes_email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'departamento',
                'telefone',
                'notificacoes_email',
                'notificacoes_push'
            ]);
        });
    }
};
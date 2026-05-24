<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Para controle de faltas dos alunos
            $table->integer('total_faltas')->default(0)->after('departamento');
            $table->integer('faltas_mes')->default(0)->after('total_faltas');
            $table->date('ultima_falta')->nullable()->after('faltas_mes');
            $table->json('faltas_por_aula')->nullable()->after('ultima_falta'); // Ex: {"segunda": [1,2,3], "terca": [1,4]}
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['total_faltas', 'faltas_mes', 'ultima_falta', 'faltas_por_aula']);
        });
    }
};
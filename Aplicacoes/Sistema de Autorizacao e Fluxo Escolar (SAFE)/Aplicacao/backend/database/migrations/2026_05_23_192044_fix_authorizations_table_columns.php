<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authorizations', function (Blueprint $table) {
            // Verificar e adicionar colunas faltantes
            if (!Schema::hasColumn('authorizations', 'horario_saida')) {
                $table->time('horario_saida')->after('motivo_saida');
            }
            
            if (!Schema::hasColumn('authorizations', 'tipo')) {
                $table->enum('tipo', ['saida', 'entrada'])->default('saida')->after('status');
            }
            
            if (!Schema::hasColumn('authorizations', 'autorizado_em')) {
                $table->timestamp('autorizado_em')->nullable()->after('observacoes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('authorizations', function (Blueprint $table) {
            $table->dropColumn(['horario_saida', 'tipo', 'autorizado_em']);
        });
    }
};
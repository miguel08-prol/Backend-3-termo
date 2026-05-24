<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authorizations', function (Blueprint $table) {
            // Verificar e adicionar colunas se não existirem
            if (!Schema::hasColumn('authorizations', 'motivo_saida')) {
                $table->string('motivo_saida')->nullable()->after('turma');
            }
            
            if (!Schema::hasColumn('authorizations', 'tipo')) {
                $table->enum('tipo', ['saida', 'entrada'])->default('saida')->after('status');
            }
            
            if (!Schema::hasColumn('authorizations', 'observacoes')) {
                $table->text('observacoes')->nullable()->after('com_falta');
            }
        });
    }

    public function down(): void
    {
        Schema::table('authorizations', function (Blueprint $table) {
            $table->dropColumn(['motivo_saida', 'tipo', 'observacoes']);
        });
    }
};
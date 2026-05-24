<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authorizations', function (Blueprint $table) {
            $table->id();
            $table->string('aluno_nome');
            $table->string('turma');
            $table->string('motivo_saida')->nullable();
            $table->time('horario_saida');
            $table->integer('aula_numero')->comment('1 a 5');
            $table->enum('status', [
                'pending',           // Aguardando (não vamos mais usar)
                'approved_by_professor', // Autorizada
                'rejected',          // Rejeitada (não vamos usar)
                'completed',         // Concluída (saída registrada)
                'cancelled'          // Cancelada
            ])->default('approved_by_professor'); // Mudar default para approved
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('professor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('portaria_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('com_falta')->default(false);
            $table->text('observacoes')->nullable();
            $table->timestamp('autorizado_em')->nullable();
            $table->enum('tipo', ['saida', 'entrada'])->default('saida');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('authorizations');
    }
};
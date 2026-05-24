<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presencas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('users')->onDelete('cascade');
            $table->date('data');
            $table->integer('aula_numero'); // 1 a 5
            $table->enum('status', ['presente', 'falta', 'justificado'])->default('falta');
            $table->foreignId('authorization_id')->nullable()->constrained()->onDelete('set null');
            $table->text('justificativa')->nullable();
            $table->timestamps();
            
            $table->unique(['aluno_id', 'data', 'aula_numero']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presencas');
    }
};
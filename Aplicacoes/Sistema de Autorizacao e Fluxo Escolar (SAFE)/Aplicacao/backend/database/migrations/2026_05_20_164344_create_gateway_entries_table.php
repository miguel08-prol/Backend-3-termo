<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gateway_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('authorization_id')->constrained()->onDelete('cascade');
            $table->foreignId('portaria_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('horario_entrada')->nullable();
            $table->timestamp('horario_saida');
            $table->enum('tipo', ['entrada', 'saida'])->default('saida');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gateway_entries');
    }
};
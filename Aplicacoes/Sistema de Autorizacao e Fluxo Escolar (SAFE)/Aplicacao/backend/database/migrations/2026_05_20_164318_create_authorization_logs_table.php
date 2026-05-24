<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authorization_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('authorization_id')->constrained()->onDelete('cascade');
            $table->string('tipo'); // email, whatsapp_simulated
            $table->string('destinatario'); // email ou telefone
            $table->string('status'); // sent, failed, simulated
            $table->json('payload')->nullable();
            $table->text('resposta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('authorization_logs');
    }
};
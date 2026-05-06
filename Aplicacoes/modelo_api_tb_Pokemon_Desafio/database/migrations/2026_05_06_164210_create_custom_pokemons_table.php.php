<?php
// database/migrations/2025_01_01_000001_create_custom_pokemons_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_pokemons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('height')->nullable()->default(10);
            $table->integer('weight')->nullable()->default(100);
            $table->integer('base_experience')->nullable()->default(100);
            $table->string('image_url')->nullable();
            $table->json('stats')->nullable();
            $table->json('abilities')->nullable();
            $table->json('evolutions')->nullable();
            $table->json('types')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_pokemons');
    }
};
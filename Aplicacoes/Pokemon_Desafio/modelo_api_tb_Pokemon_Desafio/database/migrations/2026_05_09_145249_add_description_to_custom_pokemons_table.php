<?php
// database/migrations/YYYY_MM_DD_HHMMSS_add_description_to_custom_pokemons_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_pokemons', function (Blueprint $table) {
            $table->text('description')->nullable()->after('types');
        });
    }

    public function down(): void
    {
        Schema::table('custom_pokemons', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};

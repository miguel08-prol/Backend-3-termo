<?php
// database/migrations/2026_05_24_000000_add_turno_to_authorizations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authorizations', function (Blueprint $table) {
            if (!Schema::hasColumn('authorizations', 'turno')) {
                $table->enum('turno', ['manha', 'tarde', 'noite'])->nullable()->after('turma');
            }
        });
    }

    public function down(): void
    {
        Schema::table('authorizations', function (Blueprint $table) {
            $table->dropColumn('turno');
        });
    }
};
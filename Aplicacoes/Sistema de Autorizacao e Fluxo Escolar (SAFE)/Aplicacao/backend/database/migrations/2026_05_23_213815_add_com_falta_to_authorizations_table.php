<?php
// database/migrations/2026_05_24_000000_add_com_falta_to_authorizations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('authorizations', function (Blueprint $table) {
            if (!Schema::hasColumn('authorizations', 'com_falta')) {
                $table->boolean('com_falta')->default(false)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('authorizations', function (Blueprint $table) {
            $table->dropColumn('com_falta');
        });
    }
};
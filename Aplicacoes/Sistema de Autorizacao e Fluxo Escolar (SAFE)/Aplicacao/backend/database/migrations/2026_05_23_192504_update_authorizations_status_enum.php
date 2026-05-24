<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    public function up(): void
    {
        // Para MySQL, precisamos modificar a coluna ENUM
        DB::statement("ALTER TABLE authorizations MODIFY COLUMN status ENUM('pending', 'approved_by_professor', 'rejected', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE authorizations MODIFY COLUMN status ENUM('pending', 'approved_by_admin', 'approved_by_professor', 'rejected', 'completed') DEFAULT 'pending'");
    }
};
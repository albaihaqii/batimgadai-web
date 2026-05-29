<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE perpanjangan MODIFY officer_id BIGINT UNSIGNED NULL DEFAULT NULL");
        DB::statement("ALTER TABLE pelunasan MODIFY officer_id BIGINT UNSIGNED NULL DEFAULT NULL");
    }

    public function down(): void
    {
        // Tidak di-rollback karena bisa merusak data
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gadai', function (Blueprint $table) {
            // Hapus kolom lama
            $table->dropColumn('nilai_taksiran_awal');

            // Tambah kolom baru range taksiran
            $table->decimal('nilai_taksiran_min', 15, 2)->after('admin_id');
            $table->decimal('nilai_taksiran_max', 15, 2)->after('nilai_taksiran_min');
        });
    }

    public function down(): void
    {
        Schema::table('gadai', function (Blueprint $table) {
            $table->dropColumn(['nilai_taksiran_min', 'nilai_taksiran_max']);
            $table->decimal('nilai_taksiran_awal', 15, 2)->after('admin_id');
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gadai', function (Blueprint $table) {
            if (!Schema::hasColumn('gadai', 'nilai_pinjaman_awal')) {
                $table->unsignedBigInteger('nilai_pinjaman_awal')->nullable()->after('nilai_pinjaman');
            }
            if (!Schema::hasColumn('gadai', 'nilai_pinjaman_tambahan')) {
                $table->unsignedBigInteger('nilai_pinjaman_tambahan')->default(0)->after('nilai_pinjaman_awal');
            }
            if (!Schema::hasColumn('gadai', 'catatan_tambahan_pinjaman')) {
                $table->text('catatan_tambahan_pinjaman')->nullable()->after('nilai_pinjaman_tambahan');
            }
            if (!Schema::hasColumn('gadai', 'tipe_jasa')) {
                $table->enum('tipe_jasa', ['umum', 'perhiasan'])->default('umum')->after('jasa_persen');
            }
        });
    }

    public function down(): void
    {
        Schema::table('gadai', function (Blueprint $table) {
            $columns = ['nilai_pinjaman_awal', 'nilai_pinjaman_tambahan', 'catatan_tambahan_pinjaman', 'tipe_jasa'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('gadai', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
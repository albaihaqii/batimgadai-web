<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE notifikasi MODIFY tipe_notif ENUM(
            'pengajuan_gadai',
            'approval_gadai',
            'jatuh_tempo',
            'perpanjangan',
            'pelunasan',
            'booking_kunjungan',
            'info',
            'lelang'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE notifikasi MODIFY tipe_notif ENUM(
            'pengajuan_gadai',
            'approval_gadai',
            'jatuh_tempo',
            'perpanjangan',
            'pelunasan',
            'booking_kunjungan',
            'info'
        ) NOT NULL");
    }
};
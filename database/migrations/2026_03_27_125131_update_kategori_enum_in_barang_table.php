<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE barang MODIFY COLUMN kategori ENUM(
            'handphone',
            'laptop',
            'tablet',
            'elektronik_lainnya',
            'kendaraan_motor',
            'barang_rumah_tangga'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE barang MODIFY COLUMN kategori ENUM(
            'handphone',
            'laptop',
            'tablet',
            'elektronik_lainnya',
            'kendaraan_motor',
            'bpkb_motor',
            'barang_rumah_tangga'
        ) NOT NULL");
    }
};
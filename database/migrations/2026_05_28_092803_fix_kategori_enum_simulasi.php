<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $validKategori = [
            'handphone', 'laptop', 'tablet',
            'elektronik_lainnya', 'kendaraan_motor',
            'barang_rumah_tangga', 'perhiasan',
        ];

        DB::table('simulasi_kelengkapan')->whereNotIn('kategori', $validKategori)->delete();
        DB::table('simulasi_kecacatan')->whereNotIn('kategori', $validKategori)->delete();
        DB::table('simulasi_master')->whereNotIn('kategori', $validKategori)->delete();

        $enum = "'handphone','laptop','tablet','elektronik_lainnya','kendaraan_motor','barang_rumah_tangga','perhiasan'";

        DB::statement("ALTER TABLE simulasi_master MODIFY kategori ENUM({$enum}) NOT NULL");
        DB::statement("ALTER TABLE simulasi_kecacatan MODIFY kategori ENUM({$enum}) NOT NULL");
        DB::statement("ALTER TABLE simulasi_kelengkapan MODIFY kategori ENUM({$enum}) NOT NULL");
    }

    public function down(): void
    {
        $enumLama = "'handphone','laptop','tablet','kamera','elektronik','perhiasan','kendaraan','barang_rumah_tangga','lainnya'";

        DB::statement("ALTER TABLE simulasi_master MODIFY kategori ENUM({$enumLama}) NOT NULL");
        DB::statement("ALTER TABLE simulasi_kecacatan MODIFY kategori ENUM({$enumLama}) NOT NULL");
        DB::statement("ALTER TABLE simulasi_kelengkapan MODIFY kategori ENUM({$enumLama}) NOT NULL");
    }
};
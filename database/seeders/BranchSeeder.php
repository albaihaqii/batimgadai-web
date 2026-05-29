<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cabang')->insert([
            [
                'kode'       => 'SMG',
                'nama'       => 'Batim Gadai Semanggi',
                'alamat'     => 'Jl. Brantas 2 No.30, Tegal Boto Lor, Sumbersari, Jember',
                'latitude'   => -8.1629,
                'longitude'  => 113.7143,
                'hari_buka'  => 'Senin - Sabtu',
                'jam_buka'   => '07.00',
                'jam_tutup'  => '17.00',
                'no_telp'    => '0331-123456',
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode'       => 'MST',
                'nama'       => 'Batim Gadai Mastrip',
                'alamat'     => 'Jl. Mastrip No.123, Kebonsari, Sumbersari, Jember',
                'latitude'   => -8.1780,
                'longitude'  => 113.6990,
                'hari_buka'  => 'Senin - Sabtu',
                'jam_buka'   => '07.00',
                'jam_tutup'  => '17.00',
                'no_telp'    => '0331-456789',
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode'       => 'KRM',
                'nama'       => 'Batim Gadai Karimata',
                'alamat'     => 'Jl. Karimata No.217, Gumuk Kerang, Sumbersari, Jember',
                'latitude'   => -8.1710,
                'longitude'  => 113.7220,
                'hari_buka'  => 'Senin - Sabtu',
                'jam_buka'   => '07.00',
                'jam_tutup'  => '17.00',
                'no_telp'    => '0331-345678',
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
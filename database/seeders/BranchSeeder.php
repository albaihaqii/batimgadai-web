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
                'nama'       => 'Cabang Semanggi',
                'alamat'     => 'Jl. Semanggi No. 1, Jember',
                'no_telp'    => '0331-123456',
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode'       => 'MGL',
                'nama'       => 'Cabang Mangli',
                'alamat'     => 'Jl. Mangli No. 1, Jember',
                'no_telp'    => '0331-234567',
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode'       => 'KRM',
                'nama'       => 'Cabang Karimata',
                'alamat'     => 'Jl. Karimata No. 1, Jember',
                'no_telp'    => '0331-345678',
                'status'     => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
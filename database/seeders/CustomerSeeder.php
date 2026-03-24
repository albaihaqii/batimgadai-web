<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $smg = DB::table('cabang')->where('kode', 'SMG')->value('id');
        $mgl = DB::table('cabang')->where('kode', 'MGL')->value('id');
        $krm = DB::table('cabang')->where('kode', 'KRM')->value('id');
        $superadminId = DB::table('users')->where('role', 'superadmin')->value('id');

        $nasabah = [
            [
                'no_cif'        => 'CIF-SMG-000001',
                'nama'          => 'Suharto',
                'no_ktp'        => '3509011203560001',
                'no_hp'         => '081234567801',
                'alamat'        => 'Jl. Srikoyo No. 12, Patrang, Jember',
                'cabang_id'     => $smg,
                'status'        => 'aktif',
                'tgl_bergabung' => Carbon::parse('2023-01-15'),
                'created_by'    => $superadminId,
            ],
            [
                'no_cif'        => 'CIF-SMG-000002',
                'nama'          => 'Sumiati',
                'no_ktp'        => '3509014504620002',
                'no_hp'         => '081234567802',
                'alamat'        => 'Jl. Nusantara No. 5, Sumbersari, Jember',
                'cabang_id'     => $smg,
                'status'        => 'aktif',
                'tgl_bergabung' => Carbon::parse('2023-03-22'),
                'created_by'    => $superadminId,
            ],
            [
                'no_cif'        => 'CIF-SMG-000003',
                'nama'          => 'Faiq Raihan Albaihaqi',
                'no_ktp'        => '3509011505030003',
                'no_hp'         => '085648912301',
                'alamat'        => 'Jl. Kalimantan No. 37, Sumbersari, Jember',
                'cabang_id'     => $smg,
                'status'        => 'aktif',
                'tgl_bergabung' => Carbon::parse('2023-06-10'),
                'created_by'    => $superadminId,
            ],
            [
                'no_cif'        => 'CIF-SMG-000004',
                'nama'          => 'Dewi Nur Cahyani',
                'no_ktp'        => '3509014407030004',
                'no_hp'         => '082145678901',
                'alamat'        => 'Jl. Mastrip No. 8, Kaliwates, Jember',
                'cabang_id'     => $smg,
                'status'        => 'aktif',
                'tgl_bergabung' => Carbon::parse('2023-07-18'),
                'created_by'    => $superadminId,
            ],
            [
                'no_cif'        => 'CIF-SMG-000005',
                'nama'          => 'Bambang Eko Prasetyo',
                'no_ktp'        => '3509011208750005',
                'no_hp'         => '081357924680',
                'alamat'        => 'Jl. PB Sudirman No. 45, Kaliwates, Jember',
                'cabang_id'     => $smg,
                'status'        => 'aktif',
                'tgl_bergabung' => Carbon::parse('2023-09-05'),
                'created_by'    => $superadminId,
            ],
            [
                'no_cif'        => 'CIF-SMG-000006',
                'nama'          => 'Siti Nur Halimah',
                'no_ktp'        => '3509016809800006',
                'no_hp'         => '089654321012',
                'alamat'        => 'Jl. Semanggi No. 3, Patrang, Jember',
                'cabang_id'     => $smg,
                'status'        => 'aktif',
                'tgl_bergabung' => Carbon::parse('2023-11-20'),
                'created_by'    => $superadminId,
            ],
            [
                'no_cif'        => 'CIF-SMG-000007',
                'nama'          => 'Muhammad Rizky Firmansyah',
                'no_ktp'        => '3509011404040007',
                'no_hp'         => '085712345678',
                'alamat'        => 'Jl. Veteran No. 22, Sumbersari, Jember',
                'cabang_id'     => $smg,
                'status'        => 'aktif',
                'tgl_bergabung' => Carbon::parse('2024-01-08'),
                'created_by'    => $superadminId,
            ],
            [
                'no_cif'        => 'CIF-SMG-000008',
                'nama'          => 'Nur Aini Rahmawati',
                'no_ktp'        => '3509015509020008',
                'no_hp'         => '081298765432',
                'alamat'        => 'Perum Griya Indah Blok C No. 7, Patrang, Jember',
                'cabang_id'     => $smg,
                'status'        => 'nonaktif',
                'tgl_bergabung' => Carbon::parse('2024-02-14'),
                'created_by'    => $superadminId,
            ],
            [
                'no_cif'        => 'CIF-MGL-000001',
                'nama'          => 'Agus Santoso',
                'no_ktp'        => '3509011507680009',
                'no_hp'         => '081234598760',
                'alamat'        => 'Jl. Mangli Indah No. 14, Kaliwates, Jember',
                'cabang_id'     => $mgl,
                'status'        => 'aktif',
                'tgl_bergabung' => Carbon::parse('2023-02-10'),
                'created_by'    => $superadminId,
            ],
            [
                'no_cif'        => 'CIF-MGL-000002',
                'nama'          => 'Alviansyah Nurhidayat',
                'no_ktp'        => '3509012809040010',
                'no_hp'         => '082198765401',
                'alamat'        => 'Jl. Wijaya Kusuma No. 9, Kaliwates, Jember',
                'cabang_id'     => $mgl,
                'status'        => 'aktif',
                'tgl_bergabung' => Carbon::parse('2023-08-25'),
                'created_by'    => $superadminId,
            ],
            [
                'no_cif'        => 'CIF-MGL-000003',
                'nama'          => 'Winarti',
                'no_ktp'        => '3509014203710011',
                'no_hp'         => '081345678902',
                'alamat'        => 'Jl. Gajahmada No. 33, Kaliwates, Jember',
                'cabang_id'     => $mgl,
                'status'        => 'aktif',
                'tgl_bergabung' => Carbon::parse('2024-03-01'),
                'created_by'    => $superadminId,
            ],
            [
                'no_cif'        => 'CIF-MGL-000004',
                'nama'          => 'Juliana Intan Purwaningtyas',
                'no_ktp'        => '3509015207040012',
                'no_hp'         => '089876543210',
                'alamat'        => 'Jl. Brawijaya No. 17, Kaliwates, Jember',
                'cabang_id'     => $mgl,
                'status'        => 'aktif',
                'tgl_bergabung' => Carbon::parse('2024-04-17'),
                'created_by'    => $superadminId,
            ],
            [
                'no_cif'        => 'CIF-KRM-000001',
                'nama'          => 'Misdi',
                'no_ktp'        => '3509010906580013',
                'no_hp'         => '081765432109',
                'alamat'        => 'Jl. Karimata No. 28, Sumbersari, Jember',
                'cabang_id'     => $krm,
                'status'        => 'aktif',
                'tgl_bergabung' => Carbon::parse('2023-04-12'),
                'created_by'    => $superadminId,
            ],
            [
                'no_cif'        => 'CIF-KRM-000002',
                'nama'          => 'Ririn Dwi Agustin',
                'no_ktp'        => '3509014508000014',
                'no_hp'         => '082345678901',
                'alamat'        => 'Jl. Sumatra No. 11, Sumbersari, Jember',
                'cabang_id'     => $krm,
                'status'        => 'aktif',
                'tgl_bergabung' => Carbon::parse('2023-10-30'),
                'created_by'    => $superadminId,
            ],
            [
                'no_cif'        => 'CIF-KRM-000003',
                'nama'          => 'Yohanes Fabian Surya',
                'no_ktp'        => '3509012005050015',
                'no_hp'         => '085634567890',
                'alamat'        => 'Jl. Jawa No. 6, Sumbersari, Jember',
                'cabang_id'     => $krm,
                'status'        => 'nonaktif',
                'tgl_bergabung' => Carbon::parse('2024-05-20'),
                'created_by'    => $superadminId,
            ],
        ];

        foreach ($nasabah as $data) {
            DB::table('nasabah')->insert(array_merge($data, [
                'tgl_bergabung' => $data['tgl_bergabung']->toDateString(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]));
        }
    }
}
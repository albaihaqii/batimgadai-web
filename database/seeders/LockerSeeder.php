<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Locker;
use App\Models\Branch;

class LockerSeeder extends Seeder
{
    public function run(): void
    {
        // Keterangan per rak
        $keteranganRak = [
            'A' => 'Loker handphone dan tablet',
            'B' => 'Loker laptop dan elektronik besar',
            'C' => 'Loker BPKB dan dokumen berharga',
            'D' => 'Loker barang rumah tangga',
            'E' => 'Loker perhiasan dan jam tangan',
            'F' => 'Loker kamera dan aksesoris elektronik',
        ];

        $branches = Branch::all();

        foreach ($branches as $branch) {
            foreach ($keteranganRak as $rak => $keterangan) {
                // Jumlah loker per rak bervariasi
                $jumlah = match($rak) {
                    'A' => 8,  // HP/tablet paling banyak
                    'B' => 6,  // Laptop cukup banyak
                    'C' => 5,  // BPKB/dokumen sedang
                    'D' => 4,  // Barang RT sedang
                    'E' => 5,  // Perhiasan sedang
                    'F' => 4,  // Kamera sedikit
                    default => 4,
                };

                for ($i = 0; $i < $jumlah; $i++) {
                    Locker::create([
                        'kode_loker'  => Locker::generateKode($branch->kode, $rak),
                        'cabang_id'   => $branch->id,
                        'rak'         => $rak,
                        'status'      => 'kosong',
                        'gadai_id'    => null,
                        'keterangan'  => $keterangan,
                    ]);
                }
            }
        }
    }
}
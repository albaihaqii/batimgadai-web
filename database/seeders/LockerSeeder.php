<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Locker;
use App\Models\Branch;

class LockerSeeder extends Seeder
{
    public function run(): void
    {
        $keteranganRak = [
            'A' => 'Loker handphone dan tablet',
            'B' => 'Loker laptop dan elektronik besar',
            'C' => 'Loker BPKB dan dokumen berharga',
            'D' => 'Loker barang rumah tangga',
            'E' => 'Loker perhiasan dan jam tangan',
            'F' => 'Loker kamera dan aksesoris elektronik',
        ];

        $jumlahPerRak = [
            'A' => 10,
            'B' => 8,
            'C' => 6,
            'D' => 6,
            'E' => 6,
            'F' => 5,
        ];

        foreach (Branch::all() as $branch) {
            foreach ($keteranganRak as $rak => $keterangan) {
                for ($i = 0; $i < $jumlahPerRak[$rak]; $i++) {
                    Locker::create([
                        'kode_loker' => Locker::generateKode($branch->kode, $rak),
                        'cabang_id'  => $branch->id,
                        'rak'        => $rak,
                        'status'     => 'kosong',
                        'gadai_id'   => null,
                        'keterangan' => $keterangan,
                    ]);
                }
            }
        }
    }
}
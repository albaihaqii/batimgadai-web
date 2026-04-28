<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JasaRate;

class JasaRateSeeder extends Seeder
{
    public function run(): void
    {
        JasaRate::truncate();

        $rates = [
            [
                'tipe' => 'umum',
                'min_pinjaman' => 0,
                'max_pinjaman' => 99999,
                'jasa_15_hari' => 10.00,
                'jasa_30_hari' => 10.00,
            ],
            [
                'tipe' => 'umum',
                'min_pinjaman' => 100000,
                'max_pinjaman' => 1999999,
                'jasa_15_hari' => 5.00,
                'jasa_30_hari' => 8.00,
            ],
            [
                'tipe' => 'umum',
                'min_pinjaman' => 2000000,
                'max_pinjaman' => 2999999,
                'jasa_15_hari' => 4.00,
                'jasa_30_hari' => 7.00,
            ],
            [
                'tipe' => 'umum',
                'min_pinjaman' => 3000000,
                'max_pinjaman' => 4999999,
                'jasa_15_hari' => 4.00,
                'jasa_30_hari' => 6.00,
            ],
            [
                'tipe' => 'umum',
                'min_pinjaman' => 5000000,
                'max_pinjaman' => null,
                'jasa_15_hari' => 3.00,
                'jasa_30_hari' => 5.00,
            ],
            [
                'tipe' => 'perhiasan',
                'min_pinjaman' => 100000,
                'max_pinjaman' => null,
                'jasa_15_hari' => 2.50,
                'jasa_30_hari' => 5.00,
            ],
        ];

        foreach ($rates as $rate) {
            JasaRate::create(
                array_merge(
                    $rate,
                    [
                        'is_active' => true,
                    ]
                )
            );
        }
    }
}
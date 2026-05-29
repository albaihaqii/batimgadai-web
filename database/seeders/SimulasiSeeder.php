<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SimulasiSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data lama
        DB::table('simulasi_kelengkapan')->truncate();
        DB::table('simulasi_kecacatan')->truncate();
        DB::table('simulasi_master')->truncate();

        // ── Master Persentase ────────────────────────────
        $master = [
            [
                'kategori'   => 'handphone',
                'persen_min' => 60,
                'persen_max' => 75,
                'keterangan' => 'Smartphone, HP Android/iOS',
            ],
            [
                'kategori'   => 'laptop',
                'persen_min' => 55,
                'persen_max' => 70,
                'keterangan' => 'Laptop, Notebook, Netbook',
            ],
            [
                'kategori'   => 'tablet',
                'persen_min' => 55,
                'persen_max' => 70,
                'keterangan' => 'Tablet, iPad, Android Tablet',
            ],
            [
                'kategori'   => 'elektronik_lainnya',
                'persen_min' => 50,
                'persen_max' => 65,
                'keterangan' => 'TV, AC, Kulkas, Kamera, Speaker, dll',
            ],
            [
                'kategori'   => 'kendaraan_motor',
                'persen_min' => 70,
                'persen_max' => 80,
                'keterangan' => 'Kendaraan Bermotor (Unit + BPKB)',
            ],
            [
                'kategori'   => 'barang_rumah_tangga',
                'persen_min' => 40,
                'persen_max' => 60,
                'keterangan' => 'Mesin Cuci, Kompor, Peralatan Dapur, dll',
            ],
            [
                'kategori'   => 'perhiasan',
                'persen_min' => 80,
                'persen_max' => 90,
                'keterangan' => 'Emas, Perak, Berlian, Cincin, Kalung',
            ],
        ];

        foreach ($master as $m) {
            DB::table('simulasi_master')->insert(array_merge($m, [
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ── Kecacatan per Kategori ───────────────────────
        $kecacatan = [
            // Handphone
            ['kategori' => 'handphone', 'label' => 'Mulus / Tanpa Cacat',     'faktor' => 0],
            ['kategori' => 'handphone', 'label' => 'Layar Retak',              'faktor' => -10],
            ['kategori' => 'handphone', 'label' => 'Body Baret',               'faktor' => -5],
            ['kategori' => 'handphone', 'label' => 'Tombol Rusak',             'faktor' => -5],
            ['kategori' => 'handphone', 'label' => 'Tidak Bisa Nyala',         'faktor' => -20],
            ['kategori' => 'handphone', 'label' => 'Baterai Kembung',          'faktor' => -8],
            ['kategori' => 'handphone', 'label' => 'Kamera Buram/Rusak',       'faktor' => -7],

            // Laptop
            ['kategori' => 'laptop', 'label' => 'Mulus / Tanpa Cacat',         'faktor' => 0],
            ['kategori' => 'laptop', 'label' => 'Layar Bergaris/Retak',        'faktor' => -10],
            ['kategori' => 'laptop', 'label' => 'Body Baret',                  'faktor' => -5],
            ['kategori' => 'laptop', 'label' => 'Keyboard Rusak/Lepas',        'faktor' => -8],
            ['kategori' => 'laptop', 'label' => 'Tidak Bisa Nyala',            'faktor' => -20],
            ['kategori' => 'laptop', 'label' => 'Engsel Rusak',                'faktor' => -7],
            ['kategori' => 'laptop', 'label' => 'Baterai Drop/Tidak Tahan',    'faktor' => -5],

            // Tablet
            ['kategori' => 'tablet', 'label' => 'Mulus / Tanpa Cacat',         'faktor' => 0],
            ['kategori' => 'tablet', 'label' => 'Layar Retak',                 'faktor' => -10],
            ['kategori' => 'tablet', 'label' => 'Body Baret',                  'faktor' => -5],
            ['kategori' => 'tablet', 'label' => 'Tidak Bisa Nyala',            'faktor' => -20],
            ['kategori' => 'tablet', 'label' => 'Tombol/Port Rusak',           'faktor' => -7],

            // Elektronik Lainnya
            ['kategori' => 'elektronik_lainnya', 'label' => 'Normal / Berfungsi Baik',   'faktor' => 0],
            ['kategori' => 'elektronik_lainnya', 'label' => 'Baret Ringan',              'faktor' => -5],
            ['kategori' => 'elektronik_lainnya', 'label' => 'Tidak Berfungsi Normal',    'faktor' => -20],
            ['kategori' => 'elektronik_lainnya', 'label' => 'Layar/Panel Rusak',         'faktor' => -10],
            ['kategori' => 'elektronik_lainnya', 'label' => 'Komponen Kurang/Hilang',    'faktor' => -8],
            ['kategori' => 'elektronik_lainnya', 'label' => 'Penyok/Retak',              'faktor' => -7],

            // Kendaraan Motor
            ['kategori' => 'kendaraan_motor', 'label' => 'Kondisi Normal',               'faktor' => 0],
            ['kategori' => 'kendaraan_motor', 'label' => 'Bodi Penyok/Lecet',            'faktor' => -5],
            ['kategori' => 'kendaraan_motor', 'label' => 'Mesin Bermasalah',             'faktor' => -15],
            ['kategori' => 'kendaraan_motor', 'label' => 'Cat Pudar/Kusam',              'faktor' => -3],
            ['kategori' => 'kendaraan_motor', 'label' => 'Ban Botak',                    'faktor' => -3],
            ['kategori' => 'kendaraan_motor', 'label' => 'Kaca Retak',                   'faktor' => -5],
            ['kategori' => 'kendaraan_motor', 'label' => 'Lampu Rusak',                  'faktor' => -3],

            // Barang Rumah Tangga
            ['kategori' => 'barang_rumah_tangga', 'label' => 'Normal / Berfungsi Baik',  'faktor' => 0],
            ['kategori' => 'barang_rumah_tangga', 'label' => 'Penyok Ringan',            'faktor' => -5],
            ['kategori' => 'barang_rumah_tangga', 'label' => 'Bocor/Retak',              'faktor' => -10],
            ['kategori' => 'barang_rumah_tangga', 'label' => 'Berkarat',                 'faktor' => -8],
            ['kategori' => 'barang_rumah_tangga', 'label' => 'Tidak Berfungsi Normal',   'faktor' => -20],
            ['kategori' => 'barang_rumah_tangga', 'label' => 'Komponen Kurang/Hilang',   'faktor' => -7],

            // Perhiasan
            ['kategori' => 'perhiasan', 'label' => 'Mulus / Tanpa Cacat',                'faktor' => 0],
            ['kategori' => 'perhiasan', 'label' => 'Tergores Ringan',                    'faktor' => -5],
            ['kategori' => 'perhiasan', 'label' => 'Rantai/Pengait Putus',               'faktor' => -10],
            ['kategori' => 'perhiasan', 'label' => 'Warna Pudar',                        'faktor' => -5],
            ['kategori' => 'perhiasan', 'label' => 'Batu/Berlian Hilang',                'faktor' => -15],
            ['kategori' => 'perhiasan', 'label' => 'Bentuk Berubah/Bengkok',             'faktor' => -8],
        ];

        foreach ($kecacatan as $k) {
            DB::table('simulasi_kecacatan')->insert(array_merge($k, [
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ── Kelengkapan per Kategori ─────────────────────
        $kelengkapan = [
            // Handphone
            ['kategori' => 'handphone', 'label' => 'Box + Charger + Aksesoris Lengkap',  'faktor' => 5],
            ['kategori' => 'handphone', 'label' => 'Charger Saja',                        'faktor' => 0],
            ['kategori' => 'handphone', 'label' => 'Tanpa Kelengkapan',                   'faktor' => -5],

            // Laptop
            ['kategori' => 'laptop', 'label' => 'Charger + Tas + Dus Lengkap',            'faktor' => 5],
            ['kategori' => 'laptop', 'label' => 'Charger Saja',                           'faktor' => 0],
            ['kategori' => 'laptop', 'label' => 'Tanpa Kelengkapan',                      'faktor' => -5],

            // Tablet
            ['kategori' => 'tablet', 'label' => 'Box + Charger + Aksesoris Lengkap',      'faktor' => 5],
            ['kategori' => 'tablet', 'label' => 'Charger Saja',                           'faktor' => 0],
            ['kategori' => 'tablet', 'label' => 'Tanpa Kelengkapan',                      'faktor' => -5],

            // Elektronik Lainnya
            ['kategori' => 'elektronik_lainnya', 'label' => 'Lengkap dengan Remote & Aksesoris', 'faktor' => 3],
            ['kategori' => 'elektronik_lainnya', 'label' => 'Unit Saja',                          'faktor' => 0],
            ['kategori' => 'elektronik_lainnya', 'label' => 'Tidak Lengkap',                      'faktor' => -5],

            // Kendaraan Motor
            ['kategori' => 'kendaraan_motor', 'label' => 'BPKB + STNK + Kunci Lengkap',  'faktor' => 5],
            ['kategori' => 'kendaraan_motor', 'label' => 'BPKB + Kunci (tanpa STNK)',     'faktor' => 0],
            ['kategori' => 'kendaraan_motor', 'label' => 'BPKB Saja',                     'faktor' => -5],

            // Barang Rumah Tangga
            ['kategori' => 'barang_rumah_tangga', 'label' => 'Lengkap dengan Aksesoris & Buku Manual', 'faktor' => 3],
            ['kategori' => 'barang_rumah_tangga', 'label' => 'Unit Saja',                               'faktor' => 0],
            ['kategori' => 'barang_rumah_tangga', 'label' => 'Tidak Lengkap',                           'faktor' => -5],

            // Perhiasan
            ['kategori' => 'perhiasan', 'label' => 'Sertifikat + Nota Pembelian Lengkap', 'faktor' => 10],
            ['kategori' => 'perhiasan', 'label' => 'Nota Pembelian Saja',                 'faktor' => 5],
            ['kategori' => 'perhiasan', 'label' => 'Tanpa Dokumen',                       'faktor' => -5],
        ];

        foreach ($kelengkapan as $k) {
            DB::table('simulasi_kelengkapan')->insert(array_merge($k, [
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('SimulasiSeeder selesai — ' . count($master) . ' kategori, ' . count($kecacatan) . ' kecacatan, ' . count($kelengkapan) . ' kelengkapan.');
    }
}
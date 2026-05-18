<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Gadai;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\User;
use Carbon\Carbon;

class SimulasiTestSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $superadmin = User::where('role', 'superadmin')->first();

        if ($branches->isEmpty() || !$superadmin) {
            $this->command->error('Pastikan sudah ada data cabang dan superadmin.');
            return;
        }

        $catalog = $this->getCatalog();
        $statuses = ['aktif', 'lunas', 'perpanjangan', 'jatuh_tempo'];
        $kondisis = ['baik', 'cukup', 'rusak_ringan'];
        $kondisiMultiplier = ['baik' => 1.0, 'cukup' => 0.8, 'rusak_ringan' => 0.6];
        $counter = 0;

        foreach ($branches as $branch) {
            $officer = User::where('role', 'officer')->where('cabang_id', $branch->id)->first();
            $admin = User::where('role', 'admin')->where('cabang_id', $branch->id)->first() ?? $superadmin;
            $customers = Customer::where('cabang_id', $branch->id)->get();

            if (!$officer || $customers->isEmpty()) continue;

            foreach ($catalog as $item) {
                // Buat 2-4 variasi per item per cabang
                $variations = rand(2, 4);
                for ($v = 0; $v < $variations; $v++) {
                    $customer = $customers->random();
                    $kondisi = $kondisis[array_rand($kondisis)];
                    $mult = $kondisiMultiplier[$kondisi];

                    // Variasi harga ±15% dari base
                    $variance = rand(-15, 15) / 100;
                    $baseFinal = $item['base_final'];
                    $final = round(($baseFinal * $mult) * (1 + $variance), -3); // bulatkan ke ribuan
                    $min = round($final * rand(75, 85) / 100, -3);
                    $max = round($final * rand(110, 130) / 100, -3);
                    if ($final < 10000) $final = 10000;
                    if ($min < 5000) $min = 5000;

                    $pinjaman = round($final * rand(65, 80) / 100, -3);
                    if ($pinjaman < 5000) $pinjaman = 5000;

                    // Tanggal tersebar 0-14 bulan terakhir
                    $tglGadai = Carbon::now()->subDays(rand(1, 420));
                    $tglJt = (clone $tglGadai)->addDays(30);
                    $status = $statuses[array_rand($statuses)];

                    $tipeJasa = $item['kategori'] === 'perhiasan' ? 'perhiasan' : 'umum';
                    $jasaPersen = 5.00;
                    $jasaNominal = round($pinjaman * ($jasaPersen / 100));
                    $totalTebus = $pinjaman + $jasaNominal;

                    $prefix = $tglGadai->format('ym') . strtoupper($branch->kode);
                    $counter++;
                    $noSbg = $prefix . 'S' . str_pad($counter, 5, '0', STR_PAD_LEFT);

                    $barangModel = Barang::create([
                        'nasabah_id'  => $customer->id,
                        'nama_barang' => $item['nama'],
                        'kategori'    => $item['kategori'],
                        'merk'        => $item['merk'],
                        'tipe_model'  => $item['tipe'],
                        'kondisi'     => $kondisi,
                        'kelengkapan' => $item['kelengkapan'],
                        'created_at'  => $tglGadai,
                        'updated_at'  => $tglGadai,
                    ]);

                    Gadai::create([
                        'no_sbg'               => $noSbg,
                        'nasabah_id'           => $customer->id,
                        'barang_id'            => $barangModel->id,
                        'cabang_id'            => $branch->id,
                        'officer_id'           => $officer->id,
                        'admin_id'             => $admin->id,
                        'nilai_taksiran_min'   => $min,
                        'nilai_taksiran_max'   => $max,
                        'nilai_taksiran_akhir' => $final,
                        'nilai_pinjaman'       => $pinjaman,
                        'nilai_pinjaman_awal'  => $pinjaman,
                        'tipe_jasa'            => $tipeJasa,
                        'jasa_persen'          => $jasaPersen,
                        'jasa_nominal'         => $jasaNominal,
                        'total_tebus'          => $totalTebus,
                        'tgl_gadai'            => $tglGadai,
                        'tgl_jatuh_tempo'      => $tglJt,
                        'status'               => $status,
                        'created_at'           => $tglGadai,
                        'updated_at'           => $tglGadai,
                    ]);
                }
            }
        }

        $this->command->info("Seeder simulasi selesai: {$counter} transaksi gadai dibuat.");
    }

    private function getCatalog(): array
    {
        return [
            // === HANDPHONE ===
            ['nama' => 'iPhone 14 Pro 128GB',     'kategori' => 'handphone', 'merk' => 'Apple',   'tipe' => 'iPhone 14 Pro',       'base_final' => 8500000,  'kelengkapan' => 'Unit, charger, dus'],
            ['nama' => 'iPhone 13 128GB',          'kategori' => 'handphone', 'merk' => 'Apple',   'tipe' => 'iPhone 13',           'base_final' => 6500000,  'kelengkapan' => 'Unit, charger, dus'],
            ['nama' => 'iPhone 12 64GB',           'kategori' => 'handphone', 'merk' => 'Apple',   'tipe' => 'iPhone 12',           'base_final' => 4500000,  'kelengkapan' => 'Unit, charger'],
            ['nama' => 'iPhone 11 64GB',           'kategori' => 'handphone', 'merk' => 'Apple',   'tipe' => 'iPhone 11',           'base_final' => 3200000,  'kelengkapan' => 'Unit, charger'],
            ['nama' => 'Samsung Galaxy S23 256GB', 'kategori' => 'handphone', 'merk' => 'Samsung', 'tipe' => 'Galaxy S23',          'base_final' => 7000000,  'kelengkapan' => 'Unit, charger, dus'],
            ['nama' => 'Samsung Galaxy A54 128GB', 'kategori' => 'handphone', 'merk' => 'Samsung', 'tipe' => 'Galaxy A54',          'base_final' => 3000000,  'kelengkapan' => 'Unit, charger, dus'],
            ['nama' => 'Samsung Galaxy A14 64GB',  'kategori' => 'handphone', 'merk' => 'Samsung', 'tipe' => 'Galaxy A14',          'base_final' => 1200000,  'kelengkapan' => 'Unit, charger'],
            ['nama' => 'Samsung Galaxy A04e 32GB', 'kategori' => 'handphone', 'merk' => 'Samsung', 'tipe' => 'Galaxy A04e',         'base_final' => 700000,   'kelengkapan' => 'Unit, charger'],
            ['nama' => 'OPPO Reno 10 256GB',       'kategori' => 'handphone', 'merk' => 'OPPO',    'tipe' => 'Reno 10',             'base_final' => 3500000,  'kelengkapan' => 'Unit, charger, dus'],
            ['nama' => 'OPPO A78 128GB',           'kategori' => 'handphone', 'merk' => 'OPPO',    'tipe' => 'A78',                 'base_final' => 2000000,  'kelengkapan' => 'Unit, charger'],
            ['nama' => 'OPPO A17 64GB',            'kategori' => 'handphone', 'merk' => 'OPPO',    'tipe' => 'A17',                 'base_final' => 1000000,  'kelengkapan' => 'Unit, charger'],
            ['nama' => 'Xiaomi 13T 256GB',         'kategori' => 'handphone', 'merk' => 'Xiaomi',  'tipe' => 'Xiaomi 13T',          'base_final' => 3800000,  'kelengkapan' => 'Unit, charger, dus'],
            ['nama' => 'Xiaomi Redmi Note 12 128GB','kategori' => 'handphone','merk' => 'Xiaomi',  'tipe' => 'Redmi Note 12',       'base_final' => 1800000,  'kelengkapan' => 'Unit, charger'],
            ['nama' => 'Xiaomi Redmi 12 64GB',     'kategori' => 'handphone', 'merk' => 'Xiaomi',  'tipe' => 'Redmi 12',            'base_final' => 1100000,  'kelengkapan' => 'Unit, charger'],
            ['nama' => 'Vivo V29 256GB',           'kategori' => 'handphone', 'merk' => 'Vivo',    'tipe' => 'V29',                 'base_final' => 3200000,  'kelengkapan' => 'Unit, charger, dus'],
            ['nama' => 'Vivo Y27 128GB',           'kategori' => 'handphone', 'merk' => 'Vivo',    'tipe' => 'Y27',                 'base_final' => 1500000,  'kelengkapan' => 'Unit, charger'],
            ['nama' => 'Realme 11 Pro 256GB',      'kategori' => 'handphone', 'merk' => 'Realme',  'tipe' => 'Realme 11 Pro',       'base_final' => 2800000,  'kelengkapan' => 'Unit, charger, dus'],
            ['nama' => 'Realme C55 128GB',         'kategori' => 'handphone', 'merk' => 'Realme',  'tipe' => 'C55',                 'base_final' => 1300000,  'kelengkapan' => 'Unit, charger'],

            // === LAPTOP ===
            ['nama' => 'MacBook Air M2 256GB',     'kategori' => 'laptop', 'merk' => 'Apple',  'tipe' => 'MacBook Air M2',    'base_final' => 11000000, 'kelengkapan' => 'Unit, charger, dus'],
            ['nama' => 'MacBook Air M1 256GB',     'kategori' => 'laptop', 'merk' => 'Apple',  'tipe' => 'MacBook Air M1',    'base_final' => 8000000,  'kelengkapan' => 'Unit, charger'],
            ['nama' => 'ASUS VivoBook 14 i5',      'kategori' => 'laptop', 'merk' => 'ASUS',   'tipe' => 'VivoBook 14',       'base_final' => 5500000,  'kelengkapan' => 'Unit, charger, tas'],
            ['nama' => 'ASUS ROG Strix G15',       'kategori' => 'laptop', 'merk' => 'ASUS',   'tipe' => 'ROG Strix G15',     'base_final' => 12000000, 'kelengkapan' => 'Unit, charger, dus'],
            ['nama' => 'Lenovo IdeaPad Slim 3',    'kategori' => 'laptop', 'merk' => 'Lenovo', 'tipe' => 'IdeaPad Slim 3',    'base_final' => 4500000,  'kelengkapan' => 'Unit, charger'],
            ['nama' => 'Lenovo ThinkPad X1 Carbon', 'kategori' => 'laptop','merk' => 'Lenovo', 'tipe' => 'ThinkPad X1 Carbon','base_final' => 9000000,  'kelengkapan' => 'Unit, charger, tas'],
            ['nama' => 'HP Pavilion 14 i5',        'kategori' => 'laptop', 'merk' => 'HP',     'tipe' => 'Pavilion 14',       'base_final' => 5000000,  'kelengkapan' => 'Unit, charger'],
            ['nama' => 'Acer Aspire 5 Ryzen 5',   'kategori' => 'laptop', 'merk' => 'Acer',   'tipe' => 'Aspire 5',          'base_final' => 4800000,  'kelengkapan' => 'Unit, charger'],

            // === TABLET ===
            ['nama' => 'iPad Pro M2 12.9 256GB',   'kategori' => 'tablet', 'merk' => 'Apple',   'tipe' => 'iPad Pro M2 12.9', 'base_final' => 10000000, 'kelengkapan' => 'Unit, charger, dus'],
            ['nama' => 'iPad Air M1 64GB',         'kategori' => 'tablet', 'merk' => 'Apple',   'tipe' => 'iPad Air M1',      'base_final' => 6000000,  'kelengkapan' => 'Unit, charger'],
            ['nama' => 'iPad 10th Gen 64GB',       'kategori' => 'tablet', 'merk' => 'Apple',   'tipe' => 'iPad 10th Gen',    'base_final' => 4000000,  'kelengkapan' => 'Unit, charger, dus'],
            ['nama' => 'Samsung Galaxy Tab S9',    'kategori' => 'tablet', 'merk' => 'Samsung', 'tipe' => 'Galaxy Tab S9',    'base_final' => 7000000,  'kelengkapan' => 'Unit, charger, S-Pen'],
            ['nama' => 'Samsung Galaxy Tab A8',    'kategori' => 'tablet', 'merk' => 'Samsung', 'tipe' => 'Galaxy Tab A8',    'base_final' => 2000000,  'kelengkapan' => 'Unit, charger'],
            ['nama' => 'Xiaomi Pad 6 128GB',       'kategori' => 'tablet', 'merk' => 'Xiaomi',  'tipe' => 'Pad 6',            'base_final' => 3000000,  'kelengkapan' => 'Unit, charger'],

            // === ELEKTRONIK LAINNYA ===
            ['nama' => 'Sony Alpha A7 III Body',   'kategori' => 'elektronik_lainnya', 'merk' => 'Sony',  'tipe' => 'Alpha A7 III',   'base_final' => 9000000,  'kelengkapan' => 'Body, charger, baterai x2'],
            ['nama' => 'Canon EOS R50 Kit',        'kategori' => 'elektronik_lainnya', 'merk' => 'Canon', 'tipe' => 'EOS R50',        'base_final' => 7000000,  'kelengkapan' => 'Body, lensa kit, charger'],
            ['nama' => 'JBL Charge 5 Speaker',     'kategori' => 'elektronik_lainnya', 'merk' => 'JBL',   'tipe' => 'Charge 5',       'base_final' => 1200000,  'kelengkapan' => 'Unit, kabel USB-C'],
            ['nama' => 'Sony PS5 Disc Edition',    'kategori' => 'elektronik_lainnya', 'merk' => 'Sony',  'tipe' => 'PlayStation 5',  'base_final' => 5000000,  'kelengkapan' => 'Unit, 2 stick, kabel'],
            ['nama' => 'Nintendo Switch OLED',     'kategori' => 'elektronik_lainnya', 'merk' => 'Nintendo','tipe' => 'Switch OLED',  'base_final' => 3000000,  'kelengkapan' => 'Unit, dock, joy-con'],
            ['nama' => 'DJI Mini 3 Pro Drone',     'kategori' => 'elektronik_lainnya', 'merk' => 'DJI',   'tipe' => 'Mini 3 Pro',     'base_final' => 6000000,  'kelengkapan' => 'Unit, remote, baterai x2'],

            // === KENDARAAN MOTOR ===
            ['nama' => 'Honda Vario 160 2023',     'kategori' => 'kendaraan_motor', 'merk' => 'Honda',  'tipe' => 'Vario 160',        'base_final' => 14000000, 'kelengkapan' => 'BPKB, STNK, kunci'],
            ['nama' => 'Honda Beat 2022',          'kategori' => 'kendaraan_motor', 'merk' => 'Honda',  'tipe' => 'Beat Street',      'base_final' => 8000000,  'kelengkapan' => 'BPKB, STNK, kunci'],
            ['nama' => 'Honda Scoopy 2021',        'kategori' => 'kendaraan_motor', 'merk' => 'Honda',  'tipe' => 'Scoopy',           'base_final' => 9000000,  'kelengkapan' => 'BPKB, STNK, kunci x2'],
            ['nama' => 'Yamaha NMAX 155 2022',     'kategori' => 'kendaraan_motor', 'merk' => 'Yamaha', 'tipe' => 'NMAX 155',         'base_final' => 15000000, 'kelengkapan' => 'BPKB, STNK, kunci'],
            ['nama' => 'Yamaha Aerox 155 2022',    'kategori' => 'kendaraan_motor', 'merk' => 'Yamaha', 'tipe' => 'Aerox 155',        'base_final' => 13000000, 'kelengkapan' => 'BPKB, STNK, kunci'],
            ['nama' => 'Yamaha Mio M3 2020',       'kategori' => 'kendaraan_motor', 'merk' => 'Yamaha', 'tipe' => 'Mio M3',           'base_final' => 6000000,  'kelengkapan' => 'BPKB, STNK, kunci'],

            // === BARANG RUMAH TANGGA ===
            ['nama' => 'Mesin Cuci Samsung 9kg',   'kategori' => 'barang_rumah_tangga', 'merk' => 'Samsung', 'tipe' => 'WA90T5260BY', 'base_final' => 3000000,  'kelengkapan' => 'Unit, selang'],
            ['nama' => 'Kulkas LG 2 Pintu 230L',  'kategori' => 'barang_rumah_tangga', 'merk' => 'LG',      'tipe' => 'GN-B232SQBB', 'base_final' => 3500000,  'kelengkapan' => 'Unit'],
            ['nama' => 'AC Daikin 1PK',           'kategori' => 'barang_rumah_tangga', 'merk' => 'Daikin',   'tipe' => 'FTV25BXV14',  'base_final' => 2500000,  'kelengkapan' => 'Unit indoor+outdoor'],
            ['nama' => 'TV Samsung 43 inch',       'kategori' => 'barang_rumah_tangga', 'merk' => 'Samsung', 'tipe' => 'UA43AU7002',   'base_final' => 2800000,  'kelengkapan' => 'Unit, remote, kabel'],
            ['nama' => 'Blender Philips HR2115',   'kategori' => 'barang_rumah_tangga', 'merk' => 'Philips', 'tipe' => 'HR2115',       'base_final' => 300000,   'kelengkapan' => 'Unit, gelas, dus'],

            // === PERHIASAN ===
            ['nama' => 'Cincin Emas 24K 3 Gram',  'kategori' => 'perhiasan', 'merk' => 'Tanpa Merk', 'tipe' => 'Cincin 24K 3gr',    'base_final' => 1500000,  'kelengkapan' => 'Cincin, nota'],
            ['nama' => 'Cincin Emas 22K 5 Gram',  'kategori' => 'perhiasan', 'merk' => 'Tanpa Merk', 'tipe' => 'Cincin 22K 5gr',    'base_final' => 2200000,  'kelengkapan' => 'Cincin, sertifikat'],
            ['nama' => 'Gelang Emas 24K 10 Gram', 'kategori' => 'perhiasan', 'merk' => 'Tanpa Merk', 'tipe' => 'Gelang 24K 10gr',   'base_final' => 5000000,  'kelengkapan' => 'Gelang, nota pembelian'],
            ['nama' => 'Kalung Emas 22K 8 Gram',  'kategori' => 'perhiasan', 'merk' => 'Tanpa Merk', 'tipe' => 'Kalung 22K 8gr',    'base_final' => 3800000,  'kelengkapan' => 'Kalung, sertifikat'],
            ['nama' => 'Anting Emas 18K 2 Gram',  'kategori' => 'perhiasan', 'merk' => 'Tanpa Merk', 'tipe' => 'Anting 18K 2gr',    'base_final' => 800000,   'kelengkapan' => 'Anting, nota'],
        ];
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gadai;
use App\Models\Barang;
use App\Models\ApprovalGadai;
use App\Models\Sbg;
use App\Models\Locker;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\User;
use App\Helpers\HitungBiayaHelper;
use Carbon\Carbon;
use Illuminate\Support\Str;

class GadaiSeeder extends Seeder
{
    public function run(): void
    {
        $lokerKategoriMap = [
            'handphone'           => 'Loker handphone dan tablet',
            'tablet'              => 'Loker handphone dan tablet',
            'laptop'              => 'Loker laptop dan elektronik besar',
            'elektronik_lainnya'  => 'Loker laptop dan elektronik besar',
            'kendaraan_motor'     => 'Loker BPKB dan dokumen berharga',
            'barang_rumah_tangga' => 'Loker barang rumah tangga',
            'perhiasan'           => 'Loker handphone dan tablet',
        ];

        // 22 barang berbeda — tidak ada yang sama, semua range terpakai
        $barangList = [
            // RANGE 1 — di bawah Rp 99.999
            [
                'nama_barang' => 'Tabung Gas Elpiji 3kg',
                'kategori' => 'barang_rumah_tangga', 'merk' => 'Pertamina',
                'tipe_model' => 'Tabung 3kg', 'kondisi' => 'cukup',
                'kelengkapan' => 'Tabung gas',
                'min' => 30000, 'max' => 50000, 'final' => 45000, 'tipe_jasa' => 'umum',
            ],
            [
                'nama_barang' => 'Setrika Listrik Cosmos',
                'kategori' => 'barang_rumah_tangga', 'merk' => 'Cosmos',
                'tipe_model' => 'CS-298', 'kondisi' => 'cukup',
                'kelengkapan' => 'Unit setrika',
                'min' => 40000, 'max' => 70000, 'final' => 55000, 'tipe_jasa' => 'umum',
            ],
            // RANGE 2 — Rp 100.000 - 1.999.999
            [
                'nama_barang' => 'Nokia 105 (2023)',
                'kategori' => 'handphone', 'merk' => 'Nokia',
                'tipe_model' => '105 4th Edition', 'kondisi' => 'baik',
                'kelengkapan' => 'Unit, charger, dus',
                'min' => 100000, 'max' => 200000, 'final' => 150000, 'tipe_jasa' => 'umum',
            ],
            [
                'nama_barang' => 'Speaker Bluetooth JBL Go 3',
                'kategori' => 'elektronik_lainnya', 'merk' => 'JBL',
                'tipe_model' => 'Go 3', 'kondisi' => 'baik',
                'kelengkapan' => 'Unit, kabel USB-C',
                'min' => 200000, 'max' => 350000, 'final' => 280000, 'tipe_jasa' => 'umum',
            ],
            [
                'nama_barang' => 'Kipas Angin Meja Miyako',
                'kategori' => 'barang_rumah_tangga', 'merk' => 'Miyako',
                'tipe_model' => 'KAD-927RC', 'kondisi' => 'cukup',
                'kelengkapan' => 'Unit, remote control',
                'min' => 150000, 'max' => 250000, 'final' => 200000, 'tipe_jasa' => 'umum',
            ],
            [
                'nama_barang' => 'Xiaomi Redmi 9A 32GB',
                'kategori' => 'handphone', 'merk' => 'Xiaomi',
                'tipe_model' => 'Redmi 9A', 'kondisi' => 'cukup',
                'kelengkapan' => 'Unit, charger',
                'min' => 500000, 'max' => 700000, 'final' => 600000, 'tipe_jasa' => 'umum',
            ],
            [
                'nama_barang' => 'OPPO A15 64GB',
                'kategori' => 'handphone', 'merk' => 'OPPO',
                'tipe_model' => 'A15', 'kondisi' => 'cukup',
                'kelengkapan' => 'Unit, charger, dus',
                'min' => 700000, 'max' => 900000, 'final' => 800000, 'tipe_jasa' => 'umum',
            ],
            [
                'nama_barang' => 'Samsung Galaxy A13 128GB',
                'kategori' => 'handphone', 'merk' => 'Samsung',
                'tipe_model' => 'Galaxy A13', 'kondisi' => 'baik',
                'kelengkapan' => 'Unit, charger, dus',
                'min' => 1200000, 'max' => 1500000, 'final' => 1300000, 'tipe_jasa' => 'umum',
            ],
            // RANGE 3 — Rp 2.000.000 - 2.999.999
            [
                'nama_barang' => 'OPPO Reno 6 128GB',
                'kategori' => 'handphone', 'merk' => 'OPPO',
                'tipe_model' => 'Reno 6', 'kondisi' => 'baik',
                'kelengkapan' => 'Unit, charger original, dus',
                'min' => 2000000, 'max' => 2500000, 'final' => 2200000, 'tipe_jasa' => 'umum',
            ],
            [
                'nama_barang' => 'Mesin Cuci Sanken 7kg Top Loading',
                'kategori' => 'barang_rumah_tangga', 'merk' => 'Sanken',
                'tipe_model' => 'TW-931LX', 'kondisi' => 'cukup',
                'kelengkapan' => 'Unit, selang inlet, selang outlet',
                'min' => 2200000, 'max' => 2800000, 'final' => 2500000, 'tipe_jasa' => 'umum',
            ],
            // RANGE 4 — Rp 3.000.000 - 4.999.999
            [
                'nama_barang' => 'iPhone 11 64GB',
                'kategori' => 'handphone', 'merk' => 'Apple',
                'tipe_model' => 'iPhone 11', 'kondisi' => 'cukup',
                'kelengkapan' => 'Unit, charger',
                'min' => 3000000, 'max' => 3500000, 'final' => 3200000, 'tipe_jasa' => 'umum',
            ],
            [
                'nama_barang' => 'Honda Beat Street CBS ISS 2021',
                'kategori' => 'kendaraan_motor', 'merk' => 'Honda',
                'tipe_model' => 'Beat Street CBS ISS', 'kondisi' => 'baik',
                'kelengkapan' => 'BPKB asli, STNK, kunci utama, kunci cadangan',
                'min' => 3500000, 'max' => 4500000, 'final' => 4000000, 'tipe_jasa' => 'umum',
            ],
            [
                'nama_barang' => 'Yamaha Mio M3 2020',
                'kategori' => 'kendaraan_motor', 'merk' => 'Yamaha',
                'tipe_model' => 'Mio M3 125 Blue Core', 'kondisi' => 'baik',
                'kelengkapan' => 'BPKB asli, STNK, kunci utama',
                'min' => 3800000, 'max' => 4800000, 'final' => 4200000, 'tipe_jasa' => 'umum',
            ],
            // RANGE 5 — Rp 5.000.000 ke atas
            [
                'nama_barang' => 'iPhone 13 128GB',
                'kategori' => 'handphone', 'merk' => 'Apple',
                'tipe_model' => 'iPhone 13', 'kondisi' => 'baik',
                'kelengkapan' => 'Unit, charger, dus',
                'min' => 6000000, 'max' => 7000000, 'final' => 6500000, 'tipe_jasa' => 'umum',
            ],
            [
                'nama_barang' => 'MacBook Air M1 8GB 256GB',
                'kategori' => 'laptop', 'merk' => 'Apple',
                'tipe_model' => 'MacBook Air M1', 'kondisi' => 'baik',
                'kelengkapan' => 'Unit, charger original, dus',
                'min' => 8000000, 'max' => 9500000, 'final' => 8500000, 'tipe_jasa' => 'umum',
            ],
            [
                'nama_barang' => 'ASUS VivoBook 14 Intel i5 Gen 12',
                'kategori' => 'laptop', 'merk' => 'ASUS',
                'tipe_model' => 'VivoBook 14 X1402ZA', 'kondisi' => 'baik',
                'kelengkapan' => 'Unit, charger, tas laptop',
                'min' => 5500000, 'max' => 6500000, 'final' => 6000000, 'tipe_jasa' => 'umum',
            ],
            [
                'nama_barang' => 'Honda Vario 160 CBS ISS 2022',
                'kategori' => 'kendaraan_motor', 'merk' => 'Honda',
                'tipe_model' => 'Vario 160 CBS ISS', 'kondisi' => 'baik',
                'kelengkapan' => 'Unit motor, BPKB asli, STNK, kunci utama, kunci cadangan',
                'min' => 13000000, 'max' => 15000000, 'final' => 14000000, 'tipe_jasa' => 'umum',
            ],
            // PERHIASAN
            [
                'nama_barang' => 'Cincin Emas 22K 3 Gram',
                'kategori' => 'perhiasan', 'merk' => 'Tanpa Merk',
                'tipe_model' => 'Cincin polos 22 karat', 'kondisi' => 'baik',
                'kelengkapan' => 'Cincin, nota pembelian',
                'min' => 800000, 'max' => 1000000, 'final' => 900000, 'tipe_jasa' => 'perhiasan',
            ],
            [
                'nama_barang' => 'Gelang Emas 24K 5 Gram',
                'kategori' => 'perhiasan', 'merk' => 'Tanpa Merk',
                'tipe_model' => 'Gelang polos 24 karat', 'kondisi' => 'baik',
                'kelengkapan' => 'Gelang, surat pembelian',
                'min' => 1800000, 'max' => 2200000, 'final' => 2000000, 'tipe_jasa' => 'perhiasan',
            ],
            [
                'nama_barang' => 'Kalung Emas 22K 7 Gram dengan Liontin',
                'kategori' => 'perhiasan', 'merk' => 'Tanpa Merk',
                'tipe_model' => 'Kalung 22 karat + liontin', 'kondisi' => 'baik',
                'kelengkapan' => 'Kalung + liontin, sertifikat',
                'min' => 3500000, 'max' => 4500000, 'final' => 4000000, 'tipe_jasa' => 'perhiasan',
            ],
            [
                'nama_barang' => 'Sony Alpha A7 III Body Only',
                'kategori' => 'elektronik_lainnya', 'merk' => 'Sony',
                'tipe_model' => 'Alpha A7 III', 'kondisi' => 'baik',
                'kelengkapan' => 'Body kamera, charger, baterai x2, dus',
                'min' => 8000000, 'max' => 10000000, 'final' => 9000000, 'tipe_jasa' => 'umum',
            ],
            [
                'nama_barang' => 'iPad Pro M2 12.9 inch 256GB WiFi',
                'kategori' => 'tablet', 'merk' => 'Apple',
                'tipe_model' => 'iPad Pro M2 12.9', 'kondisi' => 'baik',
                'kelengkapan' => 'Unit, charger, Apple Pencil Gen 2, dus',
                'min' => 9000000, 'max' => 11000000, 'final' => 10000000, 'tipe_jasa' => 'umum',
            ],
        ];

        $branches   = Branch::all();
        $superadmin = User::where('role', 'superadmin')->first();

        foreach ($branches as $branch) {
            $officers        = User::where('role', 'officer')->where('cabang_id', $branch->id)->get();
            $admins          = User::where('role', 'admin')->where('cabang_id', $branch->id)->get();
            $cabangCustomers = Customer::where('cabang_id', $branch->id)->get();

            if ($officers->isEmpty() || $cabangCustomers->isEmpty()) continue;

            $officer = $officers->first();
            $admin   = $admins->first() ?? $superadmin;

            // Shuffle untuk tiap cabang berbeda urutan barangnya
            $shuffledBarang = collect($barangList)->shuffle()->values();

            // Status list — SEMUA STATUS kecuali lelang
            // Jatuh tempo ada 2: sudah lewat, dan mau JT (H-3)
            $statusList = [
                'aktif',             // 0 - normal aktif
                'aktif',             // 1 - normal aktif
                'aktif',             // 2 - normal aktif
                'aktif_h7',          // 3 - aktif tapi H-7 (warning)
                'aktif_h3',          // 4 - aktif tapi H-3 (danger)
                'jatuh_tempo_12',    // 5 - sudah telat 12 hari
                'jatuh_tempo_3',     // 6 - sudah telat 3 hari
                'menunggu_approval', // 7
                'menunggu_approval', // 8
                'ditolak',           // 9
                'perpanjangan',      // 10
                'lunas',             // 11
            ];

            foreach ($statusList as $idx => $status) {
                $barang   = $shuffledBarang[$idx % count($barangList)];
                $customer = $cabangCustomers->values()->get($idx % $cabangCustomers->count());

                // Normalize status DB
                $statusDb = match($status) {
                    'aktif_h7', 'aktif_h3' => 'aktif',
                    'jatuh_tempo_12', 'jatuh_tempo_3' => 'jatuh_tempo',
                    default => $status,
                };

                $keteranganLoker = $lokerKategoriMap[$barang['kategori']] ?? null;
                $loker = null;

                if (in_array($statusDb, ['aktif', 'jatuh_tempo', 'perpanjangan']) && $keteranganLoker) {
                    $loker = Locker::where('cabang_id', $branch->id)
                        ->where('status', 'kosong')
                        ->where('keterangan', $keteranganLoker)
                        ->first();
                    if (!$loker) {
                        $loker = Locker::where('cabang_id', $branch->id)
                            ->where('status', 'kosong')
                            ->first();
                    }
                }

                // Tentukan tanggal gadai dan jatuh tempo
                [$tglGadai, $tglJatuhTempo] = match($status) {
                    'aktif'            => [Carbon::now()->subDays(rand(5, 15)), null],
                    'aktif_h7'         => [Carbon::now()->subDays(23), Carbon::now()->addDays(7)],
                    'aktif_h3'         => [Carbon::now()->subDays(27), Carbon::now()->addDays(3)],
                    'jatuh_tempo_12'   => [Carbon::now()->subDays(42), Carbon::now()->subDays(12)],
                    'jatuh_tempo_3'    => [Carbon::now()->subDays(33), Carbon::now()->subDays(3)],
                    'lunas'            => [Carbon::now()->subDays(20), null],
                    'perpanjangan'     => [Carbon::now()->subDays(35), Carbon::now()->addDays(20)],
                    default            => [Carbon::now()->subDays(rand(1, 3)), null],
                };

                // Set tglJatuhTempo yang null ke default +30
                if ($tglJatuhTempo === null && in_array($statusDb, ['aktif', 'lunas'])) {
                    $tglJatuhTempo = (clone $tglGadai)->addDays(30);
                }

                // Buat barang
                $barangModel = Barang::create([
                    'nasabah_id'  => $customer->id,
                    'nama_barang' => $barang['nama_barang'],
                    'kategori'    => $barang['kategori'],
                    'merk'        => $barang['merk'],
                    'tipe_model'  => $barang['tipe_model'],
                    'kondisi'     => $barang['kondisi'],
                    'kelengkapan' => $barang['kelengkapan'],
                    'foto'        => null,
                ]);

                // No SBG
                $noSbg = null;
                if (!in_array($statusDb, ['menunggu_approval', 'ditolak'])) {
                    $prefix = $tglGadai->format('ym') . strtoupper($branch->kode);
                    $last   = Gadai::where('no_sbg', 'like', $prefix . '%')->count();
                    $noSbg  = $prefix . str_pad($last + 1, 6, '0', STR_PAD_LEFT);
                }

                // Hitung jasa
                $nilaiFinal  = $barang['final'];
                $tipeJasa    = $barang['tipe_jasa'] ?? 'umum';
                $jasaNominal = null;
                $jasaPersen  = null;
                $totalTebus  = null;

                if (!in_array($statusDb, ['menunggu_approval', 'ditolak'])) {
                    $rate        = HitungBiayaHelper::getJasaRate($nilaiFinal, $tipeJasa);
                    $jasaPersen  = $rate['jasa_30_hari'];
                    $jasaNominal = round($nilaiFinal * ($jasaPersen / 100));
                    $totalTebus  = $nilaiFinal + $jasaNominal;
                }

                $gadai = Gadai::create([
                    'no_sbg'               => $noSbg,
                    'nasabah_id'           => $customer->id,
                    'barang_id'            => $barangModel->id,
                    'cabang_id'            => $branch->id,
                    'loker_id'             => $loker?->id,
                    'officer_id'           => $officer->id,
                    'admin_id'             => in_array($statusDb, ['aktif','ditolak','jatuh_tempo','lunas','perpanjangan']) ? $admin->id : null,
                    'nilai_taksiran_min'   => $barang['min'],
                    'nilai_taksiran_max'   => $barang['max'],
                    'nilai_taksiran_akhir' => in_array($statusDb, ['menunggu_approval','ditolak']) ? null : $nilaiFinal,
                    'nilai_pinjaman'       => in_array($statusDb, ['menunggu_approval','ditolak']) ? null : $nilaiFinal,
                    'nilai_pinjaman_awal'  => in_array($statusDb, ['menunggu_approval','ditolak']) ? null : $nilaiFinal,
                    'tipe_jasa'            => $tipeJasa,
                    'jasa_persen'          => $jasaPersen ?? 5.00,
                    'jasa_nominal'         => $jasaNominal,
                    'total_tebus'          => $totalTebus,
                    'tgl_gadai'            => in_array($statusDb, ['menunggu_approval','ditolak']) ? null : $tglGadai,
                    'tgl_jatuh_tempo'      => $tglJatuhTempo,
                    'status'               => $statusDb,
                    'created_at'           => $tglGadai,
                    'updated_at'           => $tglGadai,
                ]);

                $approvalStatus = match($statusDb) {
                    'menunggu_approval' => 'menunggu',
                    'ditolak'           => 'ditolak',
                    default             => 'disetujui',
                };

                ApprovalGadai::create([
                    'gadai_id'     => $gadai->id,
                    'admin_id'     => in_array($statusDb, ['aktif','ditolak','jatuh_tempo','lunas','perpanjangan']) ? $admin->id : null,
                    'status'       => $approvalStatus,
                    'nilai_final'  => in_array($statusDb, ['menunggu_approval','ditolak']) ? null : $nilaiFinal,
                    'catatan'      => $statusDb === 'ditolak' ? 'Nilai barang tidak memenuhi syarat minimum gadai.' : null,
                    'tgl_diproses' => $statusDb === 'menunggu_approval' ? null : $tglGadai,
                ]);

                if ($loker && in_array($statusDb, ['aktif','jatuh_tempo','perpanjangan'])) {
                    $loker->update(['status' => 'terisi', 'gadai_id' => $gadai->id]);
                }

                if (!in_array($statusDb, ['menunggu_approval','ditolak'])) {
                    Sbg::create([
                        'no_sbg'        => $noSbg,
                        'nasabah_id'    => $customer->id,
                        'gadai_id'      => $gadai->id,
                        'tipe'          => 'gadai',
                        'referensi_id'  => $gadai->id,
                        'tgl_transaksi' => $tglGadai,
                        'qr_token'      => Str::uuid()->toString(),
                    ]);
                }
            }
        }
    }
}
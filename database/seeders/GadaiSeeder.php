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
    // Map kategori ke keterangan loker
    private array $lokerMap = [
        'handphone'           => 'Loker handphone dan tablet',
        'tablet'              => 'Loker handphone dan tablet',
        'laptop'              => 'Loker laptop dan elektronik besar',
        'elektronik_lainnya'  => 'Loker laptop dan elektronik besar',
        'kendaraan_motor'     => 'Loker BPKB dan dokumen berharga',
        'barang_rumah_tangga' => 'Loker barang rumah tangga',
        'perhiasan'           => 'Loker perhiasan dan jam tangan',
    ];

    // Semua barang — 40 item berbeda
    private array $barangPool = [];

    public function run(): void
    {
        $this->buildBarangPool();

        $branches   = Branch::all();
        $superadmin = User::where('role', 'superadmin')->first();

        foreach ($branches as $branch) {
            // Ambil officers, admins, customers per cabang tanpa eager loading Branch
            $officers  = User::where('role', 'officer')->where('cabang_id', $branch->id)->get();
            $admins    = User::where('role', 'admin')->where('cabang_id', $branch->id)->get();
            $customers = Customer::where('cabang_id', $branch->id)->get();

            if ($officers->isEmpty() || $customers->isEmpty()) continue;

            $admin = $admins->first() ?? $superadmin;

            foreach ($customers as $custIdx => $customer) {
                $isMahasiswa = $this->isMahasiswa($customer->nama);
                $jumlahGadai = rand(3, 5);
                $usedBarang  = [];

                for ($g = 0; $g < $jumlahGadai; $g++) {
                    $officer = $officers->get($g % $officers->count());
                    $barang  = $this->pickBarang($isMahasiswa, $usedBarang);
                    $usedBarang[] = $barang['nama_barang'];

                    $status   = $this->pickStatus($custIdx, $g, $jumlahGadai);
                    $tglGadai = $this->pickTanggal($status);
                    $tglJt    = $this->pickJatuhTempo($status, $tglGadai);
                    $statusDb = $this->normalizeStatus($status);

                    $loker = null;
                    if (in_array($statusDb, ['aktif', 'jatuh_tempo', 'perpanjangan'])) {
                        $keterangan = $this->lokerMap[$barang['kategori']] ?? null;
                        $loker = Locker::where('cabang_id', $branch->id)
                            ->where('status', 'kosong')
                            ->when($keterangan, fn($q) => $q->where('keterangan', $keterangan))
                            ->first();
                        if (!$loker) {
                            $loker = Locker::where('cabang_id', $branch->id)->where('status', 'kosong')->first();
                        }
                    }

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

                    $noSbg = null;
                    if (!in_array($statusDb, ['menunggu_approval', 'ditolak'])) {
                        $prefix = $tglGadai->format('ym') . strtoupper($branch->kode);
                        $last   = Gadai::where('no_sbg', 'like', $prefix . '%')->count();
                        $noSbg  = $prefix . str_pad($last + 1, 6, '0', STR_PAD_LEFT);
                    }

                    $nilaiFinal  = $barang['final'];
                    $tipeJasa    = $barang['tipe_jasa'];
                    $jasaPersen  = null;
                    $jasaNominal = null;
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
                        'admin_id'             => in_array($statusDb, ['aktif','ditolak','jatuh_tempo','lunas','perpanjangan','lelang']) ? $admin->id : null,
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
                        'tgl_jatuh_tempo'      => $tglJt,
                        'status'               => $statusDb,
                        'created_at'           => $tglGadai,
                        'updated_at'           => $tglGadai,
                    ]);

                    ApprovalGadai::create([
                        'gadai_id'     => $gadai->id,
                        'admin_id'     => in_array($statusDb, ['aktif','ditolak','jatuh_tempo','lunas','perpanjangan','lelang']) ? $admin->id : null,
                        'status'       => match($statusDb) {
                            'menunggu_approval' => 'menunggu',
                            'ditolak'           => 'ditolak',
                            default             => 'disetujui',
                        },
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

        $this->seedLelang($superadmin);
    }

    private function seedLelang(User $superadmin): void
    {
        $branches  = Branch::all();
        $lelangBarang = [
            ['nama_barang' => 'Samsung Galaxy S21 128GB', 'kategori' => 'handphone', 'merk' => 'Samsung', 'tipe_model' => 'Galaxy S21', 'kondisi' => 'cukup', 'kelengkapan' => 'Unit, charger', 'min' => 3500000, 'max' => 4500000, 'final' => 4000000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Laptop Lenovo IdeaPad 330', 'kategori' => 'laptop', 'merk' => 'Lenovo', 'tipe_model' => 'IdeaPad 330', 'kondisi' => 'cukup', 'kelengkapan' => 'Unit, charger', 'min' => 3000000, 'max' => 4000000, 'final' => 3500000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Honda Supra X 125 2019', 'kategori' => 'kendaraan_motor', 'merk' => 'Honda', 'tipe_model' => 'Supra X 125', 'kondisi' => 'cukup', 'kelengkapan' => 'BPKB, STNK, kunci', 'min' => 6000000, 'max' => 8000000, 'final' => 7000000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Kalung Emas 24K 10 Gram', 'kategori' => 'perhiasan', 'merk' => 'Tanpa Merk', 'tipe_model' => 'Kalung 24 karat', 'kondisi' => 'baik', 'kelengkapan' => 'Kalung, sertifikat', 'min' => 6000000, 'max' => 7000000, 'final' => 6500000, 'tipe_jasa' => 'perhiasan'],
            ['nama_barang' => 'iPhone 12 Pro 128GB', 'kategori' => 'handphone', 'merk' => 'Apple', 'tipe_model' => 'iPhone 12 Pro', 'kondisi' => 'cukup', 'kelengkapan' => 'Unit, charger', 'min' => 4000000, 'max' => 5000000, 'final' => 4500000, 'tipe_jasa' => 'umum'],
        ];

        $branchArr = $branches->values();

        foreach ($lelangBarang as $idx => $barang) {
            $branch   = $branchArr[$idx % $branchArr->count()];
            $customers = Customer::where('cabang_id', $branch->id)->get();
            $customer = $customers->get($idx % $customers->count());
            $officer  = User::where('role', 'officer')->where('cabang_id', $branch->id)->first();
            $admin    = User::where('role', 'admin')->where('cabang_id', $branch->id)->first() ?? $superadmin;

            // Tanggal gadai 150 hari yang lalu supaya pasti masuk lelang
            $tglGadai = Carbon::create(2026, 1, rand(1, 5))->subDays(0);
            $tglGadai = Carbon::now()->subDays(150 + $idx * 3);
            $tglJt    = $tglGadai->copy()->addDays(30);

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

            $prefix = $tglGadai->format('ym') . strtoupper($branch->kode);
            $last   = Gadai::where('no_sbg', 'like', $prefix . '%')->count();
            $noSbg  = $prefix . str_pad($last + 1, 6, '0', STR_PAD_LEFT);

            $rate        = HitungBiayaHelper::getJasaRate($barang['final'], $barang['tipe_jasa']);
            $jasaPersen  = $rate['jasa_30_hari'];
            $jasaNominal = round($barang['final'] * ($jasaPersen / 100));
            $totalTebus  = $barang['final'] + $jasaNominal;

            $gadai = Gadai::create([
                'no_sbg'               => $noSbg,
                'nasabah_id'           => $customer->id,
                'barang_id'            => $barangModel->id,
                'cabang_id'            => $branch->id,
                'loker_id'             => null,
                'officer_id'           => $officer->id,
                'admin_id'             => $admin->id,
                'nilai_taksiran_min'   => $barang['min'],
                'nilai_taksiran_max'   => $barang['max'],
                'nilai_taksiran_akhir' => $barang['final'],
                'nilai_pinjaman'       => $barang['final'],
                'nilai_pinjaman_awal'  => $barang['final'],
                'tipe_jasa'            => $barang['tipe_jasa'],
                'jasa_persen'          => $jasaPersen,
                'jasa_nominal'         => $jasaNominal,
                'total_tebus'          => $totalTebus,
                'tgl_gadai'            => $tglGadai,
                'tgl_jatuh_tempo'      => $tglJt,
                'status'               => 'lelang',
                'created_at'           => $tglGadai,
                'updated_at'           => now(),
            ]);

            ApprovalGadai::create([
                'gadai_id'     => $gadai->id,
                'admin_id'     => $admin->id,
                'status'       => 'disetujui',
                'nilai_final'  => $barang['final'],
                'catatan'      => null,
                'tgl_diproses' => $tglGadai,
            ]);

            Sbg::create([
                'no_sbg'        => $noSbg,
                'nasabah_id'    => $customer->id,
                'gadai_id'      => $gadai->id,
                'tipe'          => 'gadai',
                'referensi_id'  => $gadai->id,
                'tgl_transaksi' => $tglGadai,
                'qr_token'      => Str::uuid()->toString(),
            ]);

            // Buat record lelang
            \App\Models\Lelang::create([
                'gadai_id'        => $gadai->id,
                'nasabah_id'      => $customer->id,
                'no_sbg'          => $noSbg,
                'tgl_jatuh_tempo' => $tglJt,
                'sisa_hutang'     => $totalTebus,
                'status'          => 'proses',
            ]);
        }
    }

    private function isMahasiswa(string $nama): bool
    {
        $namaMahasiswa = [
            'Faiq','Rizky','Kevin','Dimas','Alif','Gilang','Bagas','Andika','Reyhan','Hafidz',
            'Zulfikar','Yohanes','Alviansyah','Muhammad Ilham','Naufal','Taufik',
        ];
        foreach ($namaMahasiswa as $n) {
            if (str_contains($nama, $n)) return true;
        }
        return false;
    }

    private function pickBarang(bool $isMahasiswa, array $used): array
    {
        $pool = collect($this->barangPool);

        // Filter belum terpakai
        $available = $pool->filter(fn($b) => !in_array($b['nama_barang'], $used));

        if ($available->isEmpty()) {
            $available = $pool;
        }

        if ($isMahasiswa) {
            // Prioritas: hp, laptop, tablet, elektronik
            $preferred = $available->filter(fn($b) => in_array($b['kategori'], ['handphone','laptop','tablet','elektronik_lainnya']));
            if ($preferred->isNotEmpty()) {
                return $preferred->random();
            }
        } else {
            // Prioritas: barang_rumah_tangga, perhiasan, kendaraan
            $preferred = $available->filter(fn($b) => in_array($b['kategori'], ['barang_rumah_tangga','perhiasan','kendaraan_motor']));
            if ($preferred->isNotEmpty()) {
                return $preferred->random();
            }
        }

        return $available->random();
    }

    private function pickStatus(int $custIdx, int $gadaiIdx, int $total): string
    {
        // Gadai terakhir tiap nasabah = status terkini
        if ($gadaiIdx === $total - 1) {
            $statuses = ['aktif', 'aktif', 'aktif', 'aktif_h7', 'aktif_h3', 'jatuh_tempo_telat', 'jatuh_tempo_parah', 'perpanjangan', 'menunggu_approval', 'menunggu_approval'];
            return $statuses[$custIdx % count($statuses)];
        }
        // Gadai sebelumnya = sudah selesai atau ditolak
        $old = ['lunas', 'lunas', 'lunas', 'ditolak'];
        return $old[$gadaiIdx % count($old)];
    }

    private function normalizeStatus(string $status): string
    {
        return match($status) {
            'aktif_h7', 'aktif_h3' => 'aktif',
            'jatuh_tempo_telat', 'jatuh_tempo_parah' => 'jatuh_tempo',
            default => $status,
        };
    }

    private function pickTanggal(string $status): Carbon
    {
        // Semua transaksi antara 1 Jan 2026 - 2 Jun 2026
        return match($status) {
            'lunas'              => Carbon::create(2026, rand(1,4), rand(1,28)),
            'ditolak'            => Carbon::create(2026, rand(1,5), rand(1,28)),
            'aktif', 'aktif_h7', 'aktif_h3' => Carbon::create(2026, rand(4,5), rand(1,28)),
            'perpanjangan'       => Carbon::create(2026, rand(3,4), rand(1,28)),
            'jatuh_tempo_telat'  => Carbon::create(2026, rand(1,3), rand(1,28)),
            'jatuh_tempo_parah'  => Carbon::create(2026, 1, rand(1,15)),
            'menunggu_approval'  => Carbon::create(2026, 5, rand(25,31))->min(Carbon::create(2026,6,2)),
            default              => Carbon::create(2026, rand(1,5), rand(1,28)),
        };
    }

    private function pickJatuhTempo(string $status, Carbon $tglGadai): ?Carbon
    {
        return match($status) {
            'aktif'              => $tglGadai->copy()->addDays(30),
            'aktif_h7'          => Carbon::now()->addDays(7),
            'aktif_h3'          => Carbon::now()->addDays(3),
            'perpanjangan'       => Carbon::now()->addDays(rand(10, 25)),
            'jatuh_tempo_telat'  => Carbon::now()->subDays(rand(5, 20)),
            'jatuh_tempo_parah'  => Carbon::now()->subDays(rand(60, 100)),
            'lunas'              => $tglGadai->copy()->addDays(30),
            'ditolak'            => null,
            'menunggu_approval'  => null,
            default              => $tglGadai->copy()->addDays(30),
        };
    }

    private function buildBarangPool(): void
    {
        $this->barangPool = [
            // ── Handphone ────────────────────────────────────────────
            ['nama_barang' => 'Nokia 105 (2023)', 'kategori' => 'handphone', 'merk' => 'Nokia', 'tipe_model' => '105 4th Edition', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, charger, dus', 'min' => 100000, 'max' => 200000, 'final' => 150000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Xiaomi Redmi 9A 32GB', 'kategori' => 'handphone', 'merk' => 'Xiaomi', 'tipe_model' => 'Redmi 9A', 'kondisi' => 'cukup', 'kelengkapan' => 'Unit, charger', 'min' => 500000, 'max' => 700000, 'final' => 600000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'OPPO A15 64GB', 'kategori' => 'handphone', 'merk' => 'OPPO', 'tipe_model' => 'A15', 'kondisi' => 'cukup', 'kelengkapan' => 'Unit, charger, dus', 'min' => 700000, 'max' => 900000, 'final' => 800000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Samsung Galaxy A13 128GB', 'kategori' => 'handphone', 'merk' => 'Samsung', 'tipe_model' => 'Galaxy A13', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, charger, dus', 'min' => 1200000, 'max' => 1500000, 'final' => 1300000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Xiaomi Redmi Note 11 128GB', 'kategori' => 'handphone', 'merk' => 'Xiaomi', 'tipe_model' => 'Redmi Note 11', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, charger, dus', 'min' => 1500000, 'max' => 1900000, 'final' => 1700000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'OPPO Reno 6 128GB', 'kategori' => 'handphone', 'merk' => 'OPPO', 'tipe_model' => 'Reno 6', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, charger original, dus', 'min' => 2000000, 'max' => 2500000, 'final' => 2200000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'iPhone 11 64GB', 'kategori' => 'handphone', 'merk' => 'Apple', 'tipe_model' => 'iPhone 11', 'kondisi' => 'cukup', 'kelengkapan' => 'Unit, charger', 'min' => 3000000, 'max' => 3500000, 'final' => 3200000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'iPhone 13 128GB', 'kategori' => 'handphone', 'merk' => 'Apple', 'tipe_model' => 'iPhone 13', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, charger, dus', 'min' => 6000000, 'max' => 7000000, 'final' => 6500000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Samsung Galaxy A54 256GB', 'kategori' => 'handphone', 'merk' => 'Samsung', 'tipe_model' => 'Galaxy A54', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, charger, dus', 'min' => 3500000, 'max' => 4500000, 'final' => 4000000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Vivo Y35 128GB', 'kategori' => 'handphone', 'merk' => 'Vivo', 'tipe_model' => 'Y35', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, charger', 'min' => 1800000, 'max' => 2200000, 'final' => 2000000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Realme C55 128GB', 'kategori' => 'handphone', 'merk' => 'Realme', 'tipe_model' => 'C55', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, charger, dus', 'min' => 1300000, 'max' => 1700000, 'final' => 1500000, 'tipe_jasa' => 'umum'],

            // ── Laptop ────────────────────────────────────────────────
            ['nama_barang' => 'ASUS VivoBook 14 Intel i5 Gen 12', 'kategori' => 'laptop', 'merk' => 'ASUS', 'tipe_model' => 'VivoBook 14 X1402ZA', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, charger, tas laptop', 'min' => 5500000, 'max' => 6500000, 'final' => 6000000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'MacBook Air M1 8GB 256GB', 'kategori' => 'laptop', 'merk' => 'Apple', 'tipe_model' => 'MacBook Air M1', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, charger original, dus', 'min' => 8000000, 'max' => 9500000, 'final' => 8500000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Acer Aspire 5 AMD Ryzen 5', 'kategori' => 'laptop', 'merk' => 'Acer', 'tipe_model' => 'Aspire 5 A515-45', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, charger', 'min' => 5000000, 'max' => 6000000, 'final' => 5500000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Lenovo IdeaPad Slim 5 Intel i5', 'kategori' => 'laptop', 'merk' => 'Lenovo', 'tipe_model' => 'IdeaPad Slim 5', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, charger, tas', 'min' => 6500000, 'max' => 7500000, 'final' => 7000000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'HP 14s Intel i3 Gen 11', 'kategori' => 'laptop', 'merk' => 'HP', 'tipe_model' => 'HP 14s-dq2613TU', 'kondisi' => 'cukup', 'kelengkapan' => 'Unit, charger', 'min' => 3500000, 'max' => 4500000, 'final' => 4000000, 'tipe_jasa' => 'umum'],

            // ── Tablet ────────────────────────────────────────────────
            ['nama_barang' => 'iPad Pro M2 12.9 inch 256GB WiFi', 'kategori' => 'tablet', 'merk' => 'Apple', 'tipe_model' => 'iPad Pro M2 12.9', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, charger, Apple Pencil Gen 2, dus', 'min' => 9000000, 'max' => 11000000, 'final' => 10000000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Samsung Galaxy Tab A8 64GB', 'kategori' => 'tablet', 'merk' => 'Samsung', 'tipe_model' => 'Galaxy Tab A8', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, charger, dus', 'min' => 2000000, 'max' => 2500000, 'final' => 2200000, 'tipe_jasa' => 'umum'],

            // ── Elektronik Lainnya ───────────────────────────────────
            ['nama_barang' => 'Speaker Bluetooth JBL Go 3', 'kategori' => 'elektronik_lainnya', 'merk' => 'JBL', 'tipe_model' => 'Go 3', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, kabel USB-C', 'min' => 200000, 'max' => 350000, 'final' => 280000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Sony Alpha A7 III Body Only', 'kategori' => 'elektronik_lainnya', 'merk' => 'Sony', 'tipe_model' => 'Alpha A7 III', 'kondisi' => 'baik', 'kelengkapan' => 'Body kamera, charger, baterai x2, dus', 'min' => 8000000, 'max' => 10000000, 'final' => 9000000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Canon EOS M50 Mark II Kit', 'kategori' => 'elektronik_lainnya', 'merk' => 'Canon', 'tipe_model' => 'EOS M50 Mark II', 'kondisi' => 'baik', 'kelengkapan' => 'Body, lensa kit, charger, baterai', 'min' => 5500000, 'max' => 6500000, 'final' => 6000000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Headphone Sony WH-1000XM4', 'kategori' => 'elektronik_lainnya', 'merk' => 'Sony', 'tipe_model' => 'WH-1000XM4', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, kabel, pouch, dus', 'min' => 2500000, 'max' => 3000000, 'final' => 2800000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Televisi Sharp 43 inch Full HD', 'kategori' => 'elektronik_lainnya', 'merk' => 'Sharp', 'tipe_model' => '2T-C43BD1i', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, remote, kabel power, baut tembok', 'min' => 2200000, 'max' => 2800000, 'final' => 2500000, 'tipe_jasa' => 'umum'],

            // ── Kendaraan Motor ──────────────────────────────────────
            ['nama_barang' => 'Honda Beat Street CBS ISS 2021', 'kategori' => 'kendaraan_motor', 'merk' => 'Honda', 'tipe_model' => 'Beat Street CBS ISS', 'kondisi' => 'baik', 'kelengkapan' => 'BPKB asli, STNK, kunci utama, kunci cadangan', 'min' => 3500000, 'max' => 4500000, 'final' => 4000000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Yamaha Mio M3 2020', 'kategori' => 'kendaraan_motor', 'merk' => 'Yamaha', 'tipe_model' => 'Mio M3 125 Blue Core', 'kondisi' => 'baik', 'kelengkapan' => 'BPKB asli, STNK, kunci utama', 'min' => 3800000, 'max' => 4800000, 'final' => 4200000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Honda Vario 160 CBS ISS 2022', 'kategori' => 'kendaraan_motor', 'merk' => 'Honda', 'tipe_model' => 'Vario 160 CBS ISS', 'kondisi' => 'baik', 'kelengkapan' => 'Unit motor, BPKB asli, STNK, kunci utama, kunci cadangan', 'min' => 13000000, 'max' => 15000000, 'final' => 14000000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Yamaha NMAX 155 ABS 2021', 'kategori' => 'kendaraan_motor', 'merk' => 'Yamaha', 'tipe_model' => 'NMAX 155 ABS', 'kondisi' => 'baik', 'kelengkapan' => 'BPKB asli, STNK, kunci utama, kunci cadangan', 'min' => 15000000, 'max' => 18000000, 'final' => 16000000, 'tipe_jasa' => 'umum'],

            // ── Barang Rumah Tangga ──────────────────────────────────
            ['nama_barang' => 'Tabung Gas Elpiji 3kg', 'kategori' => 'barang_rumah_tangga', 'merk' => 'Pertamina', 'tipe_model' => 'Tabung 3kg', 'kondisi' => 'cukup', 'kelengkapan' => 'Tabung gas', 'min' => 30000, 'max' => 50000, 'final' => 45000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Setrika Listrik Cosmos', 'kategori' => 'barang_rumah_tangga', 'merk' => 'Cosmos', 'tipe_model' => 'CS-298', 'kondisi' => 'cukup', 'kelengkapan' => 'Unit setrika', 'min' => 40000, 'max' => 70000, 'final' => 55000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Kipas Angin Meja Miyako', 'kategori' => 'barang_rumah_tangga', 'merk' => 'Miyako', 'tipe_model' => 'KAD-927RC', 'kondisi' => 'cukup', 'kelengkapan' => 'Unit, remote control', 'min' => 150000, 'max' => 250000, 'final' => 200000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Mesin Cuci Sanken 7kg Top Loading', 'kategori' => 'barang_rumah_tangga', 'merk' => 'Sanken', 'tipe_model' => 'TW-931LX', 'kondisi' => 'cukup', 'kelengkapan' => 'Unit, selang inlet, selang outlet', 'min' => 2200000, 'max' => 2800000, 'final' => 2500000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Rice Cooker Miyako 2 Liter', 'kategori' => 'barang_rumah_tangga', 'merk' => 'Miyako', 'tipe_model' => 'MCM-628', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, sendok nasi, gelas takar', 'min' => 150000, 'max' => 250000, 'final' => 200000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Kompor Gas 2 Tungku Rinnai', 'kategori' => 'barang_rumah_tangga', 'merk' => 'Rinnai', 'tipe_model' => 'RI-522E', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, selang, regulator', 'min' => 300000, 'max' => 500000, 'final' => 400000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Lemari Pakaian 3 Pintu Olympic', 'kategori' => 'barang_rumah_tangga', 'merk' => 'Olympic', 'tipe_model' => 'Lemari 3 Pintu', 'kondisi' => 'cukup', 'kelengkapan' => 'Unit lemari', 'min' => 800000, 'max' => 1200000, 'final' => 1000000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'AC Split Daikin 1 PK', 'kategori' => 'barang_rumah_tangga', 'merk' => 'Daikin', 'tipe_model' => 'FTKQ25SVM4', 'kondisi' => 'baik', 'kelengkapan' => 'Unit indoor outdoor, remote', 'min' => 3000000, 'max' => 4000000, 'final' => 3500000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Sprei Set Katun Jepang King Size', 'kategori' => 'barang_rumah_tangga', 'merk' => 'Lady Rose', 'tipe_model' => 'Set King 180x200', 'kondisi' => 'baik', 'kelengkapan' => 'Sprei, 2 sarung bantal, 2 sarung guling', 'min' => 100000, 'max' => 200000, 'final' => 150000, 'tipe_jasa' => 'umum'],
            ['nama_barang' => 'Blender Philips 2 Liter', 'kategori' => 'barang_rumah_tangga', 'merk' => 'Philips', 'tipe_model' => 'HR2056', 'kondisi' => 'baik', 'kelengkapan' => 'Unit, gelas blender, tutup', 'min' => 250000, 'max' => 400000, 'final' => 320000, 'tipe_jasa' => 'umum'],

            // ── Perhiasan ────────────────────────────────────────────
            ['nama_barang' => 'Cincin Emas 22K 3 Gram', 'kategori' => 'perhiasan', 'merk' => 'Tanpa Merk', 'tipe_model' => 'Cincin polos 22 karat', 'kondisi' => 'baik', 'kelengkapan' => 'Cincin, nota pembelian', 'min' => 800000, 'max' => 1000000, 'final' => 900000, 'tipe_jasa' => 'perhiasan'],
            ['nama_barang' => 'Gelang Emas 24K 5 Gram', 'kategori' => 'perhiasan', 'merk' => 'Tanpa Merk', 'tipe_model' => 'Gelang polos 24 karat', 'kondisi' => 'baik', 'kelengkapan' => 'Gelang, surat pembelian', 'min' => 1800000, 'max' => 2200000, 'final' => 2000000, 'tipe_jasa' => 'perhiasan'],
            ['nama_barang' => 'Kalung Emas 22K 7 Gram dengan Liontin', 'kategori' => 'perhiasan', 'merk' => 'Tanpa Merk', 'tipe_model' => 'Kalung 22 karat + liontin', 'kondisi' => 'baik', 'kelengkapan' => 'Kalung + liontin, sertifikat', 'min' => 3500000, 'max' => 4500000, 'final' => 4000000, 'tipe_jasa' => 'perhiasan'],
            ['nama_barang' => 'Jam Tangan Casio G-Shock Original', 'kategori' => 'perhiasan', 'merk' => 'Casio', 'tipe_model' => 'G-Shock GA-2100', 'kondisi' => 'baik', 'kelengkapan' => 'Jam, dus, buku garansi', 'min' => 600000, 'max' => 900000, 'final' => 750000, 'tipe_jasa' => 'perhiasan'],
        ];
    }
}
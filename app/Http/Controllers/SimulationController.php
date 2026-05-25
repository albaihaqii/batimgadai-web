<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Gadai;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SimulationController extends Controller
{
    /**
     * Tampilkan halaman simulasi estimasi harga gadai.
     */
    public function index()
    {
        // Ambil daftar kategori unik dari tabel barang
        $kategoris = Barang::select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        // Ambil daftar merk unik
        $merks = Barang::select('merk')
            ->whereNotNull('merk')
            ->where('merk', '!=', '')
            ->distinct()
            ->orderBy('merk')
            ->pluck('merk');

        // Statistik ringkasan
        $totalTransaksi = Gadai::whereIn('status', ['aktif', 'perpanjangan', 'lunas', 'jatuh_tempo'])->count();
        $totalBarang = Barang::count();

        return view('superadmin.simulasi.index', compact(
            'kategoris',
            'merks',
            'totalTransaksi',
            'totalBarang'
        ));
    }

    /**
     * API mobile: ambil opsi awal untuk form simulasi.
     */
    public function apiOptions()
    {
        $kategoris = Barang::select('kategori')
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        $kondisi = [
            ['value' => 'baik', 'label' => 'Baik'],
            ['value' => 'cukup', 'label' => 'Cukup'],
            ['value' => 'rusak_ringan', 'label' => 'Rusak Ringan'],
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'kategori' => $kategoris,
                'kondisi' => $kondisi,
                'stats' => [
                    'total_transaksi' => Gadai::whereIn('status', ['aktif', 'perpanjangan', 'lunas', 'jatuh_tempo'])->count(),
                    'total_barang' => Barang::count(),
                ],
            ],
        ]);
    }

    /**
     * Proses simulasi estimasi harga gadai berdasarkan histori transaksi internal.
     *
     * Pendekatan estimasi:
     * - Prioritaskan data 12 bulan terakhir (fallback ke semua data jika kurang)
     * - Gunakan percentile P60 & P75 untuk rentang estimasi taksiran (menghindari estimasi terlalu rendah)
     * - Rekomendasi pinjaman = 70% dari P60 taksiran (batas bawah) s/d 70% dari P75 taksiran (batas atas)
     * - Tetap tampilkan median, avg, min, max sebagai data pendukung
     */
    public function estimate(Request $request)
    {
        $request->validate([
            'kategori'   => 'required|string',
            'kondisi'    => 'required|in:baik,cukup,rusak_ringan',
            'merk'       => 'nullable|string|max:100',
            'tipe_model' => 'nullable|string|max:100',
        ]);

        $kategori  = $request->kategori;
        $kondisi   = $request->kondisi;
        $merk      = $request->merk;
        $tipeModel = $request->tipe_model;

        // Ambil sorted values taksiran & pinjaman (prioritas 12 bulan terakhir)
        $taksiranValues = $this->getSortedValues($kategori, $kondisi, $merk, $tipeModel, 'nilai_taksiran_akhir');
        $pinjamanValues = $this->getSortedValues($kategori, $kondisi, $merk, $tipeModel, 'nilai_pinjaman');

        $totalData = $taksiranValues->count();

        // Hitung statistik dasar
        $stats = [
            'avg_taksiran' => $taksiranValues->isNotEmpty() ? $taksiranValues->avg() : 0,
            'min_taksiran' => $taksiranValues->isNotEmpty() ? $taksiranValues->min() : 0,
            'max_taksiran' => $taksiranValues->isNotEmpty() ? $taksiranValues->max() : 0,
            'avg_pinjaman' => $pinjamanValues->isNotEmpty() ? $pinjamanValues->avg() : 0,
            'min_pinjaman' => $pinjamanValues->isNotEmpty() ? $pinjamanValues->min() : 0,
            'max_pinjaman' => $pinjamanValues->isNotEmpty() ? $pinjamanValues->max() : 0,
        ];

        // Hitung percentile & median
        $medianTaksiran = $this->percentile($taksiranValues, 50);
        $p60Taksiran    = $this->percentile($taksiranValues, 60);
        $p75Taksiran    = $this->percentile($taksiranValues, 75);

        $medianPinjaman = $this->percentile($pinjamanValues, 50);
        $p60Pinjaman    = $this->percentile($pinjamanValues, 60);
        $p75Pinjaman    = $this->percentile($pinjamanValues, 75);

        // Rentang estimasi taksiran: P60 (bawah) — P75 (atas)
        $rentangTaksiranBawah = round($p60Taksiran ?? 0);
        $rentangTaksiranAtas  = round($p75Taksiran ?? 0);

        // Rekomendasi pinjaman: 70% dari rentang taksiran
        $rekomendasiPinjamanBawah = round(($p60Taksiran ?? 0) * 0.7);
        $rekomendasiPinjamanAtas  = round(($p75Taksiran ?? 0) * 0.7);

        // Tingkat kepercayaan berdasarkan jumlah data
        $confidence = $this->calculateConfidence($totalData);

        // Ambil data transaksi referensi (maks 10 terbaru)
        $referensi = $this->getReferenceTransactions($kategori, $kondisi, $merk, $tipeModel);

        // Info penggunaan data
        $dataInfo = $this->getDataInfo($kategori, $kondisi, $merk, $tipeModel);

        return response()->json([
            'success'    => true,
            'total_data' => $totalData,
            'confidence' => $confidence,
            'data_info'  => $dataInfo,
            'taksiran'   => [
                'rata_rata'      => round($stats['avg_taksiran']),
                'median'         => round($medianTaksiran ?? 0),
                'p60'            => $rentangTaksiranBawah,
                'p75'            => $rentangTaksiranAtas,
                'minimum'        => round($stats['min_taksiran']),
                'maksimum'       => round($stats['max_taksiran']),
                'rentang_bawah'  => $rentangTaksiranBawah,
                'rentang_atas'   => $rentangTaksiranAtas,
            ],
            'pinjaman' => [
                'rata_rata'           => round($stats['avg_pinjaman']),
                'median'              => round($medianPinjaman ?? 0),
                'minimum'             => round($stats['min_pinjaman']),
                'maksimum'            => round($stats['max_pinjaman']),
                'rekomendasi_bawah'   => $rekomendasiPinjamanBawah,
                'rekomendasi_atas'    => $rekomendasiPinjamanAtas,
            ],
            'referensi' => $referensi,
            'filter' => [
                'kategori'   => $kategori,
                'kondisi'    => $kondisi,
                'merk'       => $merk,
                'tipe_model' => $tipeModel,
            ],
        ]);
    }

    /**
     * API: Ambil daftar merk berdasarkan kategori.
     */
    public function getMerks(Request $request)
    {
        $kategori = $request->get('kategori');

        $merks = Barang::where('kategori', $kategori)
            ->whereNotNull('merk')
            ->where('merk', '!=', '')
            ->select('merk')
            ->distinct()
            ->orderBy('merk')
            ->pluck('merk');

        return response()->json($merks);
    }

    /**
     * API mobile: ambil daftar merk berdasarkan kategori.
     */
    public function apiMerks(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
        ]);

        $merks = Barang::where('kategori', $request->get('kategori'))
            ->whereNotNull('merk')
            ->where('merk', '!=', '')
            ->select('merk')
            ->distinct()
            ->orderBy('merk')
            ->pluck('merk');

        return response()->json([
            'success' => true,
            'data' => $merks,
        ]);
    }

    /**
     * API: Ambil daftar tipe/model berdasarkan kategori dan merk.
     */
    public function getTipeModels(Request $request)
    {
        $kategori = $request->get('kategori');
        $merk     = $request->get('merk');

        $query = Barang::where('kategori', $kategori)
            ->whereNotNull('tipe_model')
            ->where('tipe_model', '!=', '');

        if (!empty($merk)) {
            $query->where('merk', 'like', '%' . $merk . '%');
        }

        $tipeModels = $query->select('tipe_model')
            ->distinct()
            ->orderBy('tipe_model')
            ->pluck('tipe_model');

        return response()->json($tipeModels);
    }

    /**
     * API mobile: ambil daftar tipe/model berdasarkan kategori dan merk.
     */
    public function apiTipeModels(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string',
            'merk' => 'nullable|string',
        ]);

        $query = Barang::where('kategori', $request->get('kategori'))
            ->whereNotNull('tipe_model')
            ->where('tipe_model', '!=', '');

        if ($request->filled('merk')) {
            $query->where('merk', 'like', '%' . $request->get('merk') . '%');
        }

        $tipeModels = $query->select('tipe_model')
            ->distinct()
            ->orderBy('tipe_model')
            ->pluck('tipe_model');

        return response()->json([
            'success' => true,
            'data' => $tipeModels,
        ]);
    }

    /**
     * Ambil nilai sorted dari kolom tertentu.
     * Prioritaskan data 12 bulan terakhir, fallback ke semua data jika kurang dari 3.
     */
    private function getSortedValues(string $kategori, string $kondisi, ?string $merk, ?string $tipeModel, string $column)
    {
        $baseQuery = fn() => Gadai::whereIn('gadai.status', ['aktif', 'perpanjangan', 'lunas', 'jatuh_tempo'])
            ->join('barang', 'gadai.barang_id', '=', 'barang.id')
            ->where('barang.kategori', $kategori)
            ->where('barang.kondisi', $kondisi)
            ->whereNotNull("gadai.{$column}")
            ->where("gadai.{$column}", '>', 0)
            ->when(!empty($merk), fn($q) => $q->where('barang.merk', 'like', '%' . $merk . '%'))
            ->when(!empty($tipeModel), fn($q) => $q->where('barang.tipe_model', 'like', '%' . $tipeModel . '%'));

        // Coba data 12 bulan terakhir dulu
        $recentValues = $baseQuery()
            ->where('gadai.tgl_gadai', '>=', Carbon::now()->subMonths(12))
            ->orderBy("gadai.{$column}")
            ->pluck("gadai.{$column}")
            ->map(fn($v) => (float) $v)
            ->values();

        // Jika data 12 bulan kurang dari 3, gunakan semua data
        if ($recentValues->count() < 3) {
            return $baseQuery()
                ->orderBy("gadai.{$column}")
                ->pluck("gadai.{$column}")
                ->map(fn($v) => (float) $v)
                ->values();
        }

        return $recentValues;
    }

    /**
     * Hitung percentile dari collection yang sudah sorted.
     * Menggunakan metode interpolasi linear (sama dengan Excel PERCENTILE.INC).
     */
    private function percentile($sortedValues, float $percentile): ?float
    {
        if ($sortedValues->isEmpty()) {
            return null;
        }

        $count = $sortedValues->count();

        if ($count === 1) {
            return $sortedValues->first();
        }

        $rank = ($percentile / 100) * ($count - 1);
        $lower = (int) floor($rank);
        $upper = (int) ceil($rank);
        $fraction = $rank - $lower;

        if ($lower === $upper) {
            return $sortedValues[$lower];
        }

        return $sortedValues[$lower] + $fraction * ($sortedValues[$upper] - $sortedValues[$lower]);
    }

    /**
     * Ambil data transaksi referensi (maks 10 terbaru).
     */
    private function getReferenceTransactions(string $kategori, string $kondisi, ?string $merk, ?string $tipeModel)
    {
        return Gadai::with(['barang', 'nasabah', 'branch'])
            ->whereIn('gadai.status', ['aktif', 'perpanjangan', 'lunas', 'jatuh_tempo'])
            ->whereHas('barang', function ($q) use ($kategori, $kondisi, $merk, $tipeModel) {
                $q->where('kategori', $kategori)
                  ->where('kondisi', $kondisi);
                if (!empty($merk)) {
                    $q->where('merk', 'like', '%' . $merk . '%');
                }
                if (!empty($tipeModel)) {
                    $q->where('tipe_model', 'like', '%' . $tipeModel . '%');
                }
            })
            ->orderByDesc('gadai.created_at')
            ->limit(10)
            ->get()
            ->map(function ($gadai) {
                return [
                    'no_sbg'         => $gadai->no_sbg,
                    'barang'         => $gadai->barang->nama_barang ?? '-',
                    'merk'           => $gadai->barang->merk ?? '-',
                    'tipe_model'     => $gadai->barang->tipe_model ?? '-',
                    'kondisi'        => $gadai->barang->kondisi ?? '-',
                    'taksiran_akhir' => $gadai->nilai_taksiran_akhir,
                    'nilai_pinjaman' => $gadai->nilai_pinjaman,
                    'cabang'         => $gadai->branch->nama ?? '-',
                    'tgl_gadai'      => $gadai->tgl_gadai ? $gadai->tgl_gadai->format('d/m/Y') : '-',
                    'status'         => $gadai->status,
                ];
            });
    }

    /**
     * Info tentang sumber data yang digunakan untuk estimasi.
     */
    private function getDataInfo(string $kategori, string $kondisi, ?string $merk, ?string $tipeModel): array
    {
        $baseQuery = fn() => Gadai::whereIn('gadai.status', ['aktif', 'perpanjangan', 'lunas', 'jatuh_tempo'])
            ->join('barang', 'gadai.barang_id', '=', 'barang.id')
            ->where('barang.kategori', $kategori)
            ->where('barang.kondisi', $kondisi)
            ->whereNotNull('gadai.nilai_taksiran_akhir')
            ->where('gadai.nilai_taksiran_akhir', '>', 0)
            ->when(!empty($merk), fn($q) => $q->where('barang.merk', 'like', '%' . $merk . '%'))
            ->when(!empty($tipeModel), fn($q) => $q->where('barang.tipe_model', 'like', '%' . $tipeModel . '%'));

        $totalSemua  = $baseQuery()->count();
        $total12Bulan = $baseQuery()
            ->where('gadai.tgl_gadai', '>=', Carbon::now()->subMonths(12))
            ->count();

        $menggunakanDataTerbaru = $total12Bulan >= 3;

        return [
            'total_semua'     => $totalSemua,
            'total_12_bulan'  => $total12Bulan,
            'sumber'          => $menggunakanDataTerbaru ? '12 bulan terakhir' : 'semua periode',
        ];
    }

    /**
     * Hitung tingkat kepercayaan berdasarkan jumlah data referensi.
     */
    private function calculateConfidence(int $totalData): array
    {
        if ($totalData >= 20) {
            return ['level' => 'tinggi', 'label' => 'Tinggi', 'color' => 'green', 'percentage' => 90];
        } elseif ($totalData >= 10) {
            return ['level' => 'sedang', 'label' => 'Sedang', 'color' => 'yellow', 'percentage' => 70];
        } elseif ($totalData >= 3) {
            return ['level' => 'rendah', 'label' => 'Rendah', 'color' => 'orange', 'percentage' => 45];
        } else {
            return ['level' => 'sangat_rendah', 'label' => 'Sangat Rendah', 'color' => 'red', 'percentage' => 20];
        }
    }
}

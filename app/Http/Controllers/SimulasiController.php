<?php

namespace App\Http\Controllers;

use App\Models\SimulasiMaster;
use App\Models\SimulasiKecacatan;
use App\Models\SimulasiKelengkapan;
use Illuminate\Http\Request;

class SimulasiController extends Controller
{
    private const KATEGORI_LIST = [
        'handphone',
        'laptop',
        'tablet',
        'elektronik_lainnya',
        'kendaraan_motor',
        'barang_rumah_tangga',
        'perhiasan',
    ];

    public function index(Request $request)
    {
        $kategoriFilter = $request->get('kategori', '');

        $masters = SimulasiMaster::orderBy('kategori')->get()->keyBy('kategori');

        $kecacatan = SimulasiKecacatan::when($kategoriFilter, fn($q) => $q->where('kategori', $kategoriFilter))
            ->orderBy('kategori')->orderBy('id')->get()->groupBy('kategori');

        $kelengkapan = SimulasiKelengkapan::when($kategoriFilter, fn($q) => $q->where('kategori', $kategoriFilter))
            ->orderBy('kategori')->orderBy('id')->get()->groupBy('kategori');

        $kategoriList = self::KATEGORI_LIST;

        return view('superadmin.simulasi.index', compact(
            'masters', 'kecacatan', 'kelengkapan',
            'kategoriList', 'kategoriFilter'
        ));
    }

    // ── Master Persen ──────────────────────────────

    public function updateMaster(Request $request, string $kategori)
    {
        $request->validate([
            'persen_min' => 'required|numeric|min:1|max:100',
            'persen_max' => 'required|numeric|min:1|max:100|gte:persen_min',
            'keterangan' => 'nullable|string|max:255',
            'is_active'  => 'boolean',
        ]);

        SimulasiMaster::updateOrCreate(
            ['kategori' => $kategori],
            [
                'persen_min' => $request->persen_min,
                'persen_max' => $request->persen_max,
                'keterangan' => $request->keterangan,
                'is_active'  => $request->has('is_active'),
            ]
        );

        return back()->with('success', 'Master simulasi ' . ucfirst(str_replace('_', ' ', $kategori)) . ' berhasil diperbarui.');
    }

    // ── Kecacatan ──────────────────────────────────

    public function storeKecacatan(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:' . implode(',', self::KATEGORI_LIST),
            'label'    => 'required|string|max:100',
            'faktor'   => 'required|numeric|min:-100|max:0',
        ]);

        SimulasiKecacatan::create([
            'kategori'  => $request->kategori,
            'label'     => $request->label,
            'faktor'    => $request->faktor,
            'is_active' => true,
        ]);

        return back()->with('success', 'Item kecacatan berhasil ditambahkan.');
    }

    public function updateKecacatan(Request $request, int $id)
    {
        $request->validate([
            'label'  => 'required|string|max:100',
            'faktor' => 'required|numeric|min:-100|max:0',
        ]);

        SimulasiKecacatan::findOrFail($id)->update([
            'label'     => $request->label,
            'faktor'    => $request->faktor,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Item kecacatan berhasil diperbarui.');
    }

    public function destroyKecacatan(int $id)
    {
        SimulasiKecacatan::findOrFail($id)->delete();
        return back()->with('success', 'Item kecacatan berhasil dihapus.');
    }

    // ── Kelengkapan ────────────────────────────────

    public function storeKelengkapan(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:' . implode(',', self::KATEGORI_LIST),
            'label'    => 'required|string|max:100',
            'faktor'   => 'required|numeric|min:-100|max:100',
        ]);

        SimulasiKelengkapan::create([
            'kategori'  => $request->kategori,
            'label'     => $request->label,
            'faktor'    => $request->faktor,
            'is_active' => true,
        ]);

        return back()->with('success', 'Item kelengkapan berhasil ditambahkan.');
    }

    public function updateKelengkapan(Request $request, int $id)
    {
        $request->validate([
            'label'  => 'required|string|max:100',
            'faktor' => 'required|numeric|min:-100|max:100',
        ]);

        SimulasiKelengkapan::findOrFail($id)->update([
            'label'     => $request->label,
            'faktor'    => $request->faktor,
            'is_active' => $request->has('is_active'),
        ]);

        return back()->with('success', 'Item kelengkapan berhasil diperbarui.');
    }

    public function destroyKelengkapan(int $id)
    {
        SimulasiKelengkapan::findOrFail($id)->delete();
        return back()->with('success', 'Item kelengkapan berhasil dihapus.');
    }

    // ── API untuk Flutter ──────────────────────────

    public function apiOptions(Request $request)
    {
        $kategori = $request->get('kategori');

        if (!$kategori || !in_array($kategori, self::KATEGORI_LIST)) {
            return response()->json(['error' => 'Kategori tidak valid'], 422);
        }

        $master = SimulasiMaster::where('kategori', $kategori)
            ->where('is_active', true)->first();

        if (!$master) {
            return response()->json(['error' => 'Kategori belum dikonfigurasi'], 404);
        }

        return response()->json([
            'kategori'    => $kategori,
            'persen_min'  => (float) $master->persen_min,
            'persen_max'  => (float) $master->persen_max,
            'kecacatan'   => SimulasiKecacatan::where('kategori', $kategori)
                ->where('is_active', true)
                ->orderBy('faktor', 'desc')
                ->get(['id', 'label', 'faktor']),
            'kelengkapan' => SimulasiKelengkapan::where('kategori', $kategori)
                ->where('is_active', true)
                ->orderBy('faktor', 'desc')
                ->get(['id', 'label', 'faktor']),
        ]);
    }

    public function apiHitung(Request $request)
    {
        $request->validate([
            'kategori'       => 'required|in:' . implode(',', self::KATEGORI_LIST),
            'harga_pasar'    => 'required|numeric|min:100000',
            'kondisi'        => 'required|in:baik,cukup,rusak_ringan',
            'kecacatan_ids'  => 'nullable|array',
            'kelengkapan_id' => 'nullable|integer',
        ]);

        $master = SimulasiMaster::where('kategori', $request->kategori)
            ->where('is_active', true)->first();

        if (!$master) {
            return response()->json(['error' => 'Kategori tidak tersedia'], 404);
        }

        $hargaPasar = (float) $request->harga_pasar;

        // Faktor kondisi
        $faktorKondisi = match($request->kondisi) {
            'baik'         => 1.0,
            'cukup'        => 0.85,
            'rusak_ringan' => 0.70,
            default        => 1.0,
        };

        // Faktor kecacatan (total semua yang dipilih)
        $totalFaktorKecacatan = 0;
        $labelKecacatan       = [];
        if ($request->filled('kecacatan_ids') && count($request->kecacatan_ids) > 0) {
            $cacatItems = SimulasiKecacatan::whereIn('id', $request->kecacatan_ids)
                ->where('is_active', true)->get();
            $totalFaktorKecacatan = $cacatItems->sum('faktor');
            $labelKecacatan       = $cacatItems->pluck('label')->toArray();
        }

        // Faktor kelengkapan
        $faktorKelengkapan = 0;
        $labelKelengkapan  = 'Tidak dipilih';
        if ($request->filled('kelengkapan_id')) {
            $kl = SimulasiKelengkapan::where('id', $request->kelengkapan_id)
                ->where('is_active', true)->first();
            if ($kl) {
                $faktorKelengkapan = (float) $kl->faktor;
                $labelKelengkapan  = $kl->label;
            }
        }

        // Total faktor penyesuaian dalam desimal
        $totalPenyesuaian = ($totalFaktorKecacatan + $faktorKelengkapan) / 100;

        // Hitung nilai taksiran
        $nilaiMin = round($hargaPasar * ($master->persen_min / 100) * $faktorKondisi * (1 + $totalPenyesuaian));
        $nilaiMax = round($hargaPasar * ($master->persen_max / 100) * $faktorKondisi * (1 + $totalPenyesuaian));

        // Pastikan tidak negatif
        $nilaiMin = max(0, $nilaiMin);
        $nilaiMax = max(0, $nilaiMax);

        // Ambil rate jasa
        $tipeJasa = \App\Helpers\HitungBiayaHelper::getTipeJasa($request->kategori);
        $rateMin  = \App\Helpers\HitungBiayaHelper::getJasaRate($nilaiMin, $tipeJasa);
        $rateMax  = \App\Helpers\HitungBiayaHelper::getJasaRate($nilaiMax, $tipeJasa);

        $jasaMin = round($nilaiMin * ($rateMin['jasa_30_hari'] / 100));
        $jasaMax = round($nilaiMax * ($rateMax['jasa_30_hari'] / 100));

        return response()->json([
            'kategori'            => $request->kategori,
            'harga_pasar'         => $hargaPasar,
            'kondisi'             => $request->kondisi,
            'kelengkapan'         => $labelKelengkapan,
            'kecacatan'           => $labelKecacatan,
            'nilai_min'           => $nilaiMin,
            'nilai_max'           => $nilaiMax,
            'jasa_persen'         => $rateMin['jasa_30_hari'],
            'jasa_min'            => $jasaMin,
            'jasa_max'            => $jasaMax,
            'total_tebus_min'     => $nilaiMin + $jasaMin,
            'total_tebus_max'     => $nilaiMax + $jasaMax,
            'faktor_kondisi'      => ($faktorKondisi * 100) . '%',
            'faktor_kecacatan'    => $totalFaktorKecacatan . '%',
            'faktor_kelengkapan'  => $faktorKelengkapan . '%',
        ]);
    }
}
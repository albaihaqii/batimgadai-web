<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\OfficerController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\LockerController;
use App\Http\Controllers\Api\GadaiController;
use App\Http\Controllers\Api\ApprovalController;

Route::post('/login', [AuthController::class, 'login']);

Route::prefix('mobile')->group(function () {

    Route::post('/verify-nasabah', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'no_ktp' => 'required|string',
            'no_cif' => 'required|string',
        ]);
        $nasabah = \App\Models\Customer::with('branch')
            ->where('no_ktp', trim($request->no_ktp))
            ->where('no_cif', trim($request->no_cif))
            ->where('status', 'aktif')
            ->first();
        if (!$nasabah) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => [
                'nama'   => $nasabah->nama,
                'no_cif' => $nasabah->no_cif,
                'no_ktp' => $nasabah->no_ktp,
                'no_hp'  => $nasabah->no_hp,
                'alamat' => $nasabah->alamat,
                'cabang' => ['nama' => optional($nasabah->branch)->nama ?? '-'],
            ],
        ]);
    });

    Route::get('/cabang', function () {
        $cabang = \App\Models\Branch::where('status', 'aktif')
            ->orderBy('nama')
            ->get()
            ->map(fn($c) => [
                'id'        => $c->id,
                'nama'      => $c->nama,
                'alamat'    => $c->alamat,
                'no_telp'   => $c->no_telp ?? '-',
                'latitude'  => $c->latitude,
                'longitude' => $c->longitude,
            ]);
        return response()->json(['success' => true, 'data' => $cabang]);
    });

    // Helper: hitung jasa dari tabel jasa_rates
    $getJasaRate = function (int $nilaiPinjaman, string $tipeJasa, string $col = 'jasa_30_hari'): array {
        $rate = DB::table('jasa_rates')
            ->where('tipe', $tipeJasa)
            ->where('is_active', 1)
            ->where('min_pinjaman', '<=', $nilaiPinjaman)
            ->where(function ($q) use ($nilaiPinjaman) {
                $q->whereNull('max_pinjaman')
                  ->orWhere('max_pinjaman', '>=', $nilaiPinjaman);
            })
            ->first();
        $persen  = $rate ? (float) $rate->{$col} : 5.0;
        $nominal = (int) round($nilaiPinjaman * $persen / 100);
        return ['persen' => $persen, 'nominal' => $nominal];
    };

    // GET /mobile/pinjaman — list pinjaman nasabah
    Route::get('/pinjaman', function (\Illuminate\Http\Request $request) use ($getJasaRate) {
        $request->validate(['no_cif' => 'required|string']);
        $nasabah = \App\Models\Customer::where('no_cif', trim($request->no_cif))->first();
        if (!$nasabah) {
            return response()->json(['success' => false, 'data' => []], 404);
        }

        $gadai = \App\Models\Gadai::with(['barang', 'branch'])
            ->where('nasabah_id', $nasabah->id)
            ->whereIn('status', ['aktif', 'perpanjangan', 'jatuh_tempo', 'lunas'])
            ->orderByRaw("FIELD(status,'jatuh_tempo','aktif','perpanjangan','lunas')")
            ->orderBy('tgl_jatuh_tempo', 'asc')
            ->get()
            ->map(function ($g) use ($getJasaRate) {
                $nilaiPinjaman = (int) $g->nilai_pinjaman;
                $tipeJasa      = $g->tipe_jasa ?? 'umum';
                $jasaData      = $getJasaRate($nilaiPinjaman, $tipeJasa, 'jasa_30_hari');

                $barang     = $g->barang;
                $namaBarang = $barang?->nama_barang ?? '-'; // STEP 2

                return [
                    'id'                    => $g->id,
                    'no_sbg'                => $g->no_sbg,
                    'status'                => $g->status,
                    'nama_barang'           => $namaBarang,
                    'kategori_barang'       => $barang?->kategori ?? '-',
                    'nilai_pinjaman'        => $nilaiPinjaman,
                    'jasa_persen'           => $jasaData['persen'],
                    'jasa_nominal'          => $jasaData['nominal'],
                    'total_tebus'           => $nilaiPinjaman + $jasaData['nominal'],
                    'tgl_gadai'             => $g->tgl_gadai?->format('d M Y'),
                    'tgl_jatuh_tempo'       => $g->tgl_jatuh_tempo?->format('Y-m-d'),
                    'tgl_jatuh_tempo_label' => $g->tgl_jatuh_tempo?->format('d M Y'),
                    'sisa_hari'             => (int) now()->startOfDay()
                                                ->diffInDays($g->tgl_jatuh_tempo, false),
                    'nama_cabang'           => $g->branch->nama ?? '-',
                    'tipe_jasa'             => $tipeJasa,
                ];
            });

        return response()->json(['success' => true, 'data' => $gadai]);
    });

    // GET /mobile/pinjaman/{id} — detail pinjaman
    Route::get('/pinjaman/{id}', function ($id) use ($getJasaRate) {
        $g = \App\Models\Gadai::with(['barang', 'branch', 'nasabah', 'loker'])
            ->findOrFail($id);

        $nilaiPinjaman = (int) $g->nilai_pinjaman;
        $tipeJasa      = $g->tipe_jasa ?? 'umum';
        $jasa30        = $getJasaRate($nilaiPinjaman, $tipeJasa, 'jasa_30_hari');
        $jasa15        = $getJasaRate($nilaiPinjaman, $tipeJasa, 'jasa_15_hari');

        $barang     = $g->barang;
        $namaBarang = $barang?->nama_barang ?? '-'; // STEP 2

        return response()->json([
            'success' => true,
            'data' => [
                'id'                    => $g->id,
                'no_sbg'                => $g->no_sbg,
                'status'                => $g->status,
                'nama_barang'           => $namaBarang,
                'kategori_barang'       => $barang?->kategori ?? '-',
                'kondisi_barang'        => $barang?->kondisi ?? '-',
                'merk'                  => $barang?->merk ?? '-',        // STEP 2: field baru
                'tipe_model'            => $barang?->tipe_model ?? '-',  // STEP 2: field baru
                'kelengkapan'           => $barang?->kelengkapan ?? '-', // STEP 2: field baru
                'nilai_taksiran_min'    => (int) ($g->nilai_taksiran_min ?? 0),
                'nilai_taksiran_max'    => (int) ($g->nilai_taksiran_max ?? 0),
                'nilai_pinjaman'        => $nilaiPinjaman,
                'jasa_persen'           => $jasa30['persen'],
                'jasa_persen_15'        => $jasa15['persen'],
                'jasa_persen_30'        => $jasa30['persen'],
                'jasa_nominal'          => $jasa30['nominal'],
                'total_tebus'           => $nilaiPinjaman + $jasa30['nominal'],
                'tipe_jasa'             => $tipeJasa,
                'tgl_gadai'             => $g->tgl_gadai?->format('d M Y'),
                'tgl_jatuh_tempo'       => $g->tgl_jatuh_tempo?->format('Y-m-d'),
                'tgl_jatuh_tempo_label' => $g->tgl_jatuh_tempo?->format('d M Y'),
                'sisa_hari'             => (int) now()->startOfDay()
                                            ->diffInDays($g->tgl_jatuh_tempo, false),
                'nama_cabang'           => $g->branch->nama ?? '-',
                'kode_loker'            => $g->loker?->kode_loker ?? '-',
                'nama_nasabah'          => $g->nasabah?->nama ?? '-',
                'no_hp_nasabah'         => $g->nasabah?->no_hp ?? '',
            ],
        ]);
    });

    Route::post('/pinjaman/{id}/bayar-online', function (\Illuminate\Http\Request $request, $id) use ($getJasaRate) {
        $request->validate(['tipe' => 'required|in:perpanjang,lunasi']);

        $g    = \App\Models\Gadai::with('nasabah')->findOrFail($id);
        $tipe = $request->input('tipe');

        $nilaiPinjaman = (int) $g->nilai_pinjaman;
        $tipeJasa      = $g->tipe_jasa ?? 'umum';
        $jasaData      = $getJasaRate($nilaiPinjaman, $tipeJasa, 'jasa_30_hari');

        if ($tipe === 'perpanjang') {
            $total   = $jasaData['nominal'];
            $orderId = 'PRP-MOB-' . $g->id . '-' . time();
            $desc    = 'Perpanjangan Gadai ' . $g->no_sbg;
        } else {
            $total   = $nilaiPinjaman + $jasaData['nominal'];
            $orderId = 'LNS-MOB-' . $g->id . '-' . time();
            $desc    = 'Pelunasan Gadai ' . $g->no_sbg;
        }

        \Midtrans\Config::$serverKey    = config('services.midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;

        $snapToken = \Midtrans\Snap::getSnapToken([
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $total,
            ],
            'item_details' => [[
                'id'       => $g->no_sbg,
                'price'    => $total,
                'quantity' => 1,
                'name'     => $desc,
            ]],
            'customer_details' => [
                'first_name' => $g->nasabah?->nama ?? 'Nasabah',
                'phone'      => $g->nasabah?->no_hp ?? '',
            ],
        ]);

        return response()->json([
            'success'      => true,
            'snap_token'   => $snapToken,
            'order_id'     => $orderId,
            'total'        => $total,
            'jasa_nominal' => $jasaData['nominal'],
            'jasa_persen'  => $jasaData['persen'],
        ]);
    });

    Route::post('/pinjaman/{id}/payment-success', function (\Illuminate\Http\Request $request, $id) {
        $request->validate([
            'tipe'           => 'required|in:perpanjang,lunasi',
            'order_id'       => 'required|string',
            'transaction_id' => 'required|string',
            'payment_type'   => 'required|string',
            'jasa_nominal'   => 'required|integer',
            'total'          => 'required|integer',
        ]);

        $g    = \App\Models\Gadai::with(['nasabah', 'loker'])->findOrFail($id);
        $tipe = $request->input('tipe');

        DB::transaction(function () use ($g, $tipe, $request) {
            if ($tipe === 'perpanjang') {
                \App\Models\Perpanjangan::create([
                    'gadai_id'       => $g->id,
                    'nasabah_id'     => $g->nasabah_id,
                    'tgl_bayar'      => now(),
                    'jasa_nominal'   => $request->jasa_nominal,
                    'denda'          => 0,
                    'total_bayar'    => $request->total,
                    'metode_bayar'   => $request->payment_type,
                    'status_bayar'   => 'berhasil',
                    'no_sbg'         => $g->no_sbg . '-PRP',
                ]);
                $g->update([
                    'status'          => 'perpanjangan',
                    'tgl_jatuh_tempo' => now()->addDays(30),
                    'jasa_nominal'    => $request->jasa_nominal,
                    'total_tebus'     => (int) $g->nilai_pinjaman + $request->jasa_nominal,
                ]);
            } else {
                \App\Models\Pelunasan::create([
                    'gadai_id'       => $g->id,
                    'nasabah_id'     => $g->nasabah_id,
                    'tgl_bayar'      => now(),
                    'nilai_pinjaman' => $g->nilai_pinjaman,
                    'jasa_nominal'   => $request->jasa_nominal,
                    'denda'          => 0,
                    'total_bayar'    => $request->total,
                    'metode_bayar'   => $request->payment_type,
                    'status_bayar'   => 'berhasil',
                    'no_sbg'         => $g->no_sbg . '-LNS',
                ]);
                $g->update(['status' => 'lunas']);
                if ($g->loker) {
                    $g->loker->update(['status' => 'kosong']);
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => $tipe === 'perpanjang' ? 'Perpanjangan berhasil.' : 'Pelunasan berhasil.',
        ]);
    });
});

// Protected (Web Admin Panel API)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::middleware('role:superadmin')->prefix('superadmin')->group(function () {
        Route::apiResource('nasabah', CustomerController::class)->parameters(['nasabah' => 'customer']);
        Route::apiResource('pimpinan', AdminController::class)->parameters(['pimpinan' => 'admin']);
        Route::apiResource('petugas', OfficerController::class)->parameters(['petugas' => 'officer']);
        Route::apiResource('cabang', BranchController::class)->parameters(['cabang' => 'branch']);
        Route::get('loker', [LockerController::class, 'index']);
        Route::post('loker', [LockerController::class, 'store']);
        Route::get('loker/{locker}', [LockerController::class, 'show']);
        Route::delete('loker/{locker}', [LockerController::class, 'destroy']);
        Route::apiResource('transaksi/gadai', GadaiController::class)->parameters(['gadai' => 'gadai'])->except(['update']);
        Route::get('approval/gadai', [ApprovalController::class, 'index']);
        Route::get('approval/gadai/{gadai}', [ApprovalController::class, 'show']);
        Route::post('approval/gadai/{gadai}', [ApprovalController::class, 'proses']);
    });

    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::apiResource('nasabah', CustomerController::class)->parameters(['nasabah' => 'customer']);
        Route::apiResource('petugas', OfficerController::class)->parameters(['petugas' => 'officer']);
        Route::get('loker', [LockerController::class, 'index']);
        Route::post('loker', [LockerController::class, 'store']);
        Route::get('loker/{locker}', [LockerController::class, 'show']);
        Route::delete('loker/{locker}', [LockerController::class, 'destroy']);
        Route::get('transaksi/gadai', [GadaiController::class, 'index']);
        Route::get('transaksi/gadai/{gadai}', [GadaiController::class, 'show']);
        Route::get('approval/gadai', [ApprovalController::class, 'index']);
        Route::get('approval/gadai/{gadai}', [ApprovalController::class, 'show']);
        Route::post('approval/gadai/{gadai}', [ApprovalController::class, 'proses']);
    });

    Route::middleware('role:officer')->prefix('officer')->group(function () {
        Route::apiResource('nasabah', CustomerController::class)->parameters(['nasabah' => 'customer']);
        Route::get('loker', [LockerController::class, 'index']);
        Route::get('loker/{locker}', [LockerController::class, 'show']);
        Route::get('transaksi/gadai', [GadaiController::class, 'index']);
        Route::post('transaksi/gadai', [GadaiController::class, 'store']);
        Route::get('transaksi/gadai/{gadai}', [GadaiController::class, 'show']);
        Route::delete('transaksi/gadai/{gadai}', [GadaiController::class, 'destroy']);
    });
});
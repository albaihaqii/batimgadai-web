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
        $request->validate(['no_ktp' => 'required|string', 'no_cif' => 'required|string']);
        $nasabah = \App\Models\Customer::with('branch')
            ->where('no_ktp', trim($request->no_ktp))
            ->where('no_cif', trim($request->no_cif))
            ->where('status', 'aktif')->first();
        if (!$nasabah) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true, 'data' => [
            'nama'   => $nasabah->nama,
            'no_cif' => $nasabah->no_cif,
            'no_ktp' => $nasabah->no_ktp,
            'no_hp'  => $nasabah->no_hp,
            'alamat' => $nasabah->alamat,
            'cabang' => ['nama' => optional($nasabah->branch)->nama ?? '-'],
        ]]);
    });

    Route::get('/cabang', function () {
        $cabang = \App\Models\Branch::where('status', 'aktif')->orderBy('nama')->get()
            ->map(fn($c) => [
                'id'        => $c->id,
                'kode'      => $c->kode,
                'nama'      => $c->nama,
                'alamat'    => $c->alamat,
                'no_telp'   => $c->no_telp ?? '-',
                'hari_buka' => $c->hari_buka ?? 'Senin - Sabtu',
                'jam_buka'  => $c->jam_buka ?? '07.00',
                'jam_tutup' => $c->jam_tutup ?? '17.00',
                'latitude'  => $c->latitude,
                'longitude' => $c->longitude,
            ]);
        return response()->json(['success' => true, 'data' => $cabang]);
    });

    $getJasaRate = function (int $nilaiPinjaman, string $tipeJasa, string $col = 'jasa_30_hari'): array {
        $rate = DB::table('jasa_rates')
            ->where('tipe', $tipeJasa)->where('is_active', 1)
            ->where('min_pinjaman', '<=', $nilaiPinjaman)
            ->where(function ($q) use ($nilaiPinjaman) {
                $q->whereNull('max_pinjaman')->orWhere('max_pinjaman', '>=', $nilaiPinjaman);
            })->first();
        $persen  = $rate ? (float) $rate->{$col} : 5.0;
        $nominal = (int) round($nilaiPinjaman * $persen / 100);
        return ['persen' => $persen, 'nominal' => $nominal];
    };

    Route::get('/pinjaman', function (\Illuminate\Http\Request $request) use ($getJasaRate) {
        $request->validate(['no_cif' => 'required|string']);
        $nasabah = \App\Models\Customer::where('no_cif', trim($request->no_cif))->first();
        if (!$nasabah) return response()->json(['success' => false, 'data' => []], 404);

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
                $barang        = $g->barang;
                return [
                    'id'                    => $g->id,
                    'no_sbg'                => $g->no_sbg,
                    'status'                => $g->status,
                    'nama_barang'           => $barang?->nama_barang ?? '-',
                    'kategori_barang'       => $barang?->kategori ?? '-',
                    'nilai_pinjaman'        => $nilaiPinjaman,
                    'jasa_persen'           => $jasaData['persen'],
                    'jasa_nominal'          => $jasaData['nominal'],
                    'total_tebus'           => $nilaiPinjaman + $jasaData['nominal'],
                    'tgl_gadai'             => $g->tgl_gadai?->format('d M Y'),
                    'tgl_jatuh_tempo'       => $g->tgl_jatuh_tempo?->format('Y-m-d'),
                    'tgl_jatuh_tempo_label' => $g->tgl_jatuh_tempo?->format('d M Y'),
                    'sisa_hari'             => (int) now()->startOfDay()->diffInDays($g->tgl_jatuh_tempo, false),
                    'nama_cabang'           => $g->branch?->nama ?? '-',
                    'tipe_jasa'             => $tipeJasa,
                ];
            });
        return response()->json(['success' => true, 'data' => $gadai]);
    });

    Route::get('/pinjaman/{id}', function ($id) use ($getJasaRate) {
        $g = \App\Models\Gadai::with(['barang', 'branch', 'nasabah', 'loker'])->findOrFail($id);
        $nilaiPinjaman = (int) $g->nilai_pinjaman;
        $tipeJasa      = $g->tipe_jasa ?? 'umum';
        $jasa30        = $getJasaRate($nilaiPinjaman, $tipeJasa, 'jasa_30_hari');
        $jasa15        = $getJasaRate($nilaiPinjaman, $tipeJasa, 'jasa_15_hari');
        $barang        = $g->barang;
        return response()->json(['success' => true, 'data' => [
            'id'                    => $g->id,
            'no_sbg'                => $g->no_sbg,
            'status'                => $g->status,
            'nama_barang'           => $barang?->nama_barang ?? '-',
            'kategori_barang'       => $barang?->kategori ?? '-',
            'kondisi_barang'        => $barang?->kondisi ?? '-',
            'merk'                  => $barang?->merk ?? '-',
            'tipe_model'            => $barang?->tipe_model ?? '-',
            'kelengkapan'           => $barang?->kelengkapan ?? '-',
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
            'sisa_hari'             => (int) now()->startOfDay()->diffInDays($g->tgl_jatuh_tempo, false),
            'nama_cabang'           => $g->branch?->nama ?? '-',
            'kode_loker'            => $g->loker?->kode_loker ?? '-',
            'nama_nasabah'          => $g->nasabah?->nama ?? '-',
            'no_hp_nasabah'         => $g->nasabah?->no_hp ?? '',
        ]]);
    });

    Route::post('/pinjaman/{id}/bayar-online', function (\Illuminate\Http\Request $request, $id) use ($getJasaRate) {
        $request->validate(['tipe' => 'required|in:perpanjang,lunasi']);
        $g             = \App\Models\Gadai::with('nasabah')->findOrFail($id);
        $tipe          = $request->input('tipe');
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
            'transaction_details' => ['order_id' => $orderId, 'gross_amount' => $total],
            'item_details'        => [['id' => $g->no_sbg, 'price' => $total, 'quantity' => 1, 'name' => $desc]],
            'customer_details'    => [
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

        $g    = \App\Models\Gadai::with(['nasabah', 'loker', 'branch'])->findOrFail($id);
        $tipe = $request->input('tipe');

        try {
            DB::transaction(function () use ($g, $tipe, $request) {

                if ($tipe === 'perpanjang') {

                    // Cari by midtrans_order_id dulu (paling akurat)
                    $perp = \App\Models\Perpanjangan::where('gadai_id', $g->id)
                        ->where('midtrans_order_id', $request->order_id)
                        ->first();

                    // Fallback: cari yang menunggu terbaru
                    if (!$perp) {
                        $perp = \App\Models\Perpanjangan::where('gadai_id', $g->id)
                            ->where('status_bayar', 'menunggu')
                            ->orderBy('id', 'desc')
                            ->first();
                    }

                    if ($perp) {
                        $perp->update([
                            'order_id'       => $request->order_id,
                            'transaction_id' => $request->transaction_id,
                            'status_bayar'   => 'berhasil',
                            'metode_bayar'   => 'midtrans',
                        ]);
                        $tglJtBaru = $perp->tgl_jt_baru;
                    } else {
                        // Buat baru — officer_id sekarang nullable
                        $tglJtBaru  = now()->addDays(30);
                        $branchKode = strtoupper($g->branch->kode ?? 'SMG');
                        $prefix     = now()->format('ym') . $branchKode;
                        $last       = \App\Models\Perpanjangan::where('no_sbg', 'like', $prefix . '%')->count();
                        $noSbg      = $prefix . 'P' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);

                        \App\Models\Perpanjangan::create([
                            'gadai_id'          => $g->id,
                            'nasabah_id'        => $g->nasabah_id,
                            'officer_id'        => null,
                            'no_sbg'            => $noSbg,
                            'order_id'          => $request->order_id,
                            'transaction_id'    => $request->transaction_id,
                            'nilai_pinjaman'    => $g->nilai_pinjaman,
                            'jasa_persen'       => $g->jasa_persen ?? 5,
                            'jasa_nominal'      => $request->jasa_nominal,
                            'denda_persen'      => 0,
                            'denda_nominal'     => 0,
                            'hari_terlambat'    => 0,
                            'total_bayar'       => $request->jasa_nominal,
                            'tgl_perpanjangan'  => now(),
                            'tgl_jt_lama'       => $g->tgl_jatuh_tempo,
                            'tgl_jt_baru'       => $tglJtBaru,
                            'status_bayar'      => 'berhasil',
                            'metode_bayar'      => 'midtrans',
                            'midtrans_order_id' => $request->order_id,
                        ]);
                    }

                    $g->update([
                        'status'          => 'perpanjangan',
                        'tgl_jatuh_tempo' => $tglJtBaru,
                        'jasa_nominal'    => $request->jasa_nominal,
                        'total_tebus'     => (int) $g->nilai_pinjaman + $request->jasa_nominal,
                    ]);

                } else {

                    $lun = \App\Models\Pelunasan::where('gadai_id', $g->id)
                        ->where('midtrans_order_id', $request->order_id)
                        ->first();

                    if (!$lun) {
                        $lun = \App\Models\Pelunasan::where('gadai_id', $g->id)
                            ->where('status_bayar', 'menunggu')
                            ->orderBy('id', 'desc')
                            ->first();
                    }

                    if ($lun) {
                        $lun->update([
                            'order_id'       => $request->order_id,
                            'transaction_id' => $request->transaction_id,
                            'status_bayar'   => 'berhasil',
                            'metode_bayar'   => 'midtrans',
                        ]);
                    } else {
                        $branchKode = strtoupper($g->branch->kode ?? 'SMG');
                        $prefix     = now()->format('ym') . $branchKode;
                        $last       = \App\Models\Pelunasan::where('no_sbg', 'like', $prefix . '%')->count();
                        $noSbg      = $prefix . 'L' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);

                        \App\Models\Pelunasan::create([
                            'gadai_id'          => $g->id,
                            'nasabah_id'        => $g->nasabah_id,
                            'officer_id'        => null,
                            'no_sbg'            => $noSbg,
                            'order_id'          => $request->order_id,
                            'transaction_id'    => $request->transaction_id,
                            'nilai_pinjaman'    => $g->nilai_pinjaman,
                            'jasa_persen'       => $g->jasa_persen ?? 5,
                            'jasa_nominal'      => $request->jasa_nominal,
                            'denda_persen'      => 0,
                            'denda_nominal'     => 0,
                            'hari_terlambat'    => 0,
                            'total_tebus'       => $request->total,
                            'tgl_pelunasan'     => now(),
                            'tgl_jt'            => $g->tgl_jatuh_tempo,
                            'status_bayar'      => 'berhasil',
                            'metode_bayar'      => 'midtrans',
                            'midtrans_order_id' => $request->order_id,
                        ]);
                    }

                    $g->update(['status' => 'lunas']);
                    if ($g->loker) {
                        $g->loker->update(['status' => 'kosong', 'gadai_id' => null]);
                    }
                }
            });

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('payment-success error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }

        return response()->json([
            'success' => true,
            'message' => $tipe === 'perpanjang' ? 'Perpanjangan berhasil.' : 'Pelunasan berhasil.',
        ]);
    });

    Route::get('/pinjaman/{id}/riwayat', function ($id) {
        $perpanjangan = \App\Models\Perpanjangan::where('gadai_id', $id)
            ->orderBy('tgl_perpanjangan', 'desc')->get()
            ->map(fn($p) => [
                'id'           => $p->id,
                'tipe'         => 'perpanjang',
                'tipe_label'   => 'Perpanjangan',
                'tgl_bayar'    => optional($p->tgl_perpanjangan)->format('d M Y'),
                'total_bayar'  => (int) $p->total_bayar,
                'metode_bayar' => $p->metode_bayar ?? '-',
                'status_bayar' => $p->status_bayar ?? 'berhasil',
                'order_id'     => $p->order_id ?? '-',
                'tgl_jt_baru'  => optional($p->tgl_jt_baru)->format('d M Y') ?? '-',
            ]);

        $pelunasan = \App\Models\Pelunasan::where('gadai_id', $id)
            ->orderBy('tgl_pelunasan', 'desc')->get()
            ->map(fn($p) => [
                'id'           => $p->id,
                'tipe'         => 'lunasi',
                'tipe_label'   => 'Pelunasan',
                'tgl_bayar'    => optional($p->tgl_pelunasan)->format('d M Y'),
                'total_bayar'  => (int) $p->total_tebus,
                'metode_bayar' => $p->metode_bayar ?? '-',
                'status_bayar' => $p->status_bayar ?? 'berhasil',
                'order_id'     => $p->order_id ?? '-',
                'tgl_jt_baru'  => '-',
            ]);

        $all = collect([...$perpanjangan, ...$pelunasan])
            ->sortByDesc('tgl_bayar')->values();

        return response()->json(['success' => true, 'data' => $all]);
    });

    Route::get('/riwayat-nasabah', function (\Illuminate\Http\Request $request) {
        $request->validate(['no_cif' => 'required|string']);
        $nasabah = \App\Models\Customer::where('no_cif', trim($request->no_cif))->first();
        if (!$nasabah) return response()->json(['success' => false, 'data' => []], 404);

        $gadaiIds = \App\Models\Gadai::where('nasabah_id', $nasabah->id)->pluck('id');

        $perpanjangan = \App\Models\Perpanjangan::whereIn('gadai_id', $gadaiIds)
            ->orderBy('tgl_perpanjangan', 'desc')->get()
            ->map(fn($p) => [
                'id'            => $p->id,
                'gadai_id'      => $p->gadai_id,
                'tipe'          => 'perpanjang',
                'tipe_label'    => 'Perpanjangan',
                'no_sbg'        => $p->no_sbg,
                'tgl_bayar'     => optional($p->tgl_perpanjangan)->format('d M Y'),
                'tgl_bayar_raw' => optional($p->tgl_perpanjangan)->format('Y-m-d H:i:s'),
                'total_bayar'   => (int) $p->total_bayar,
                'metode_bayar'  => $p->metode_bayar ?? '-',
                'status_bayar'  => $p->status_bayar ?? 'berhasil',
                'order_id'      => $p->order_id ?? '-',
                'tgl_jt_baru'   => optional($p->tgl_jt_baru)->format('d M Y') ?? '-',
            ]);

        $pelunasan = \App\Models\Pelunasan::whereIn('gadai_id', $gadaiIds)
            ->orderBy('tgl_pelunasan', 'desc')->get()
            ->map(fn($p) => [
                'id'            => $p->id,
                'gadai_id'      => $p->gadai_id,
                'tipe'          => 'lunasi',
                'tipe_label'    => 'Pelunasan',
                'no_sbg'        => $p->no_sbg,
                'tgl_bayar'     => optional($p->tgl_pelunasan)->format('d M Y'),
                'tgl_bayar_raw' => optional($p->tgl_pelunasan)->format('Y-m-d H:i:s'),
                'total_bayar'   => (int) $p->total_tebus,
                'metode_bayar'  => $p->metode_bayar ?? '-',
                'status_bayar'  => $p->status_bayar ?? 'berhasil',
                'order_id'      => $p->order_id ?? '-',
                'tgl_jt_baru'   => '-',
            ]);

        $all = collect([...$perpanjangan, ...$pelunasan])
            ->sortByDesc('tgl_bayar_raw')->values();

        return response()->json(['success' => true, 'data' => $all]);
    });

    // FCM Token
    Route::post('/fcm-token', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'no_hp'    => 'required|string',
            'token'    => 'required|string',
            'platform' => 'nullable|string',
        ]);

        \Illuminate\Support\Facades\DB::table('fcm_tokens')->updateOrInsert(
            ['no_hp' => $request->no_hp],
            [
                'token'      => $request->token,
                'platform'   => $request->platform ?? 'android',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return response()->json(['message' => 'Token disimpan']);
    });

    // Notifikasi — pengunjung (tanpa no_cif) dan nasabah (dengan no_cif)
    Route::get('/notifikasi', function (\Illuminate\Http\Request $request) {
        $noCif = $request->get('no_cif', '');

        $query = \App\Models\Notification::query();

        if (!empty($noCif)) {
            // Nasabah — notif info umum + notif khusus nasabah ini
            $nasabah = \App\Models\Customer::where('no_cif', $noCif)->first();
            $query->where(function ($q) use ($nasabah) {
                // Notif info umum untuk semua
                $q->where('tipe_penerima', 'semua');
                // Notif khusus nasabah ini
                if ($nasabah) {
                    $q->orWhere(function ($q2) use ($nasabah) {
                        $q2->where('tipe_penerima', 'nasabah')
                        ->where('penerima_id', $nasabah->id);
                    });
                }
            });
        } else {
            // Pengunjung — hanya notif info umum
            $query->where('tipe_penerima', 'semua')
                ->where('tipe_notif', 'info');
        }

        $notifs = $query->orderBy('created_at', 'desc')
            ->limit(30)
            ->get()
            ->map(fn($n) => [
                'id'         => $n->id,
                'judul'      => $n->judul,
                'isi'        => $n->pesan,
                'tipe_notif' => $n->tipe_notif,
                'is_read'    => (bool) $n->is_read,
                'created_at' => optional($n->created_at)->format('d M Y H:i'),
            ]);

        return response()->json(['success' => true, 'data' => $notifs]);
    });

    // Mark notifikasi sebagai sudah dibaca
    Route::post('/notifikasi/{id}/read', function ($id) {
        \App\Models\Notification::where('id', $id)->update(['is_read' => true]);
        return response()->json(['success' => true]);
    });

    // Banner mobile — pakai model Banner dengan tipe 'mobile'
    Route::get('/banners', function () {
        $banners = \App\Models\Banner::where('tipe', 'mobile')
            ->where('is_active', true)
            ->orderBy('urutan')
            ->limit(5)
            ->get()
            ->map(fn($b) => [
                'id'       => $b->id,
                'judul'    => $b->judul,
                'foto'     => $b->foto,
                'url_link' => $b->url_link,
                'foto_url' => $b->foto ? url('storage/' . $b->foto) : null,
            ]);

        return response()->json(['success' => true, 'data' => $banners]);
    });
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::middleware('role:superadmin')->prefix('superadmin')->name('api.superadmin.')->group(function () {
        Route::apiResource('nasabah', CustomerController::class)
            ->parameters(['nasabah' => 'customer']);
        Route::apiResource('pimpinan', AdminController::class)
            ->parameters(['pimpinan' => 'admin']);
        Route::apiResource('petugas', OfficerController::class)
            ->parameters(['petugas' => 'officer']);
        Route::apiResource('cabang', BranchController::class)
            ->parameters(['cabang' => 'branch']);
        Route::get('loker', [LockerController::class, 'index']);
        Route::post('loker', [LockerController::class, 'store']);
        Route::get('loker/{locker}', [LockerController::class, 'show']);
        Route::delete('loker/{locker}', [LockerController::class, 'destroy']);
        Route::apiResource('transaksi/gadai', GadaiController::class)
            ->parameters(['gadai' => 'gadai'])
            ->except(['update']);
        Route::get('approval/gadai', [ApprovalController::class, 'index']);
        Route::get('approval/gadai/{gadai}', [ApprovalController::class, 'show']);
        Route::post('approval/gadai/{gadai}', [ApprovalController::class, 'proses']);
    });

    Route::middleware('role:admin')->prefix('admin')->name('api.admin.')->group(function () {
        Route::apiResource('nasabah', CustomerController::class)
            ->parameters(['nasabah' => 'customer']);
        Route::apiResource('petugas', OfficerController::class)
            ->parameters(['petugas' => 'officer']);
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

    Route::middleware('role:officer')->prefix('officer')->name('api.officer.')->group(function () {
        Route::apiResource('nasabah', CustomerController::class)
            ->parameters(['nasabah' => 'customer']);
        Route::get('loker', [LockerController::class, 'index']);
        Route::get('loker/{locker}', [LockerController::class, 'show']);
        Route::get('transaksi/gadai', [GadaiController::class, 'index']);
        Route::post('transaksi/gadai', [GadaiController::class, 'store']);
        Route::get('transaksi/gadai/{gadai}', [GadaiController::class, 'show']);
        Route::delete('transaksi/gadai/{gadai}', [GadaiController::class, 'destroy']);
    });
});
<?php

namespace App\Http\Controllers;

use App\Models\Gadai;
use App\Models\Perpanjangan;
use App\Models\Sbg;
use App\Helpers\HitungBiayaHelper;
use App\Helpers\MidtransHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PerpanjanganController extends Controller
{
    public function index(Request $request)
    {
        $role  = Auth::user()->role;
        $query = Perpanjangan::with(['gadai', 'nasabah', 'officer']);

        if ($role !== 'superadmin') {
            $query->whereHas('gadai', fn($q) => $q->where('cabang_id', Auth::user()->cabang_id));
        } elseif ($request->filled('cabang_id')) {
            $query->whereHas('gadai', fn($q) => $q->where('cabang_id', $request->cabang_id));
        }

        if ($request->filled('status_bayar')) {
            $query->where('status_bayar', $request->status_bayar);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_sbg', 'like', "%{$search}%")
                  ->orWhereHas('nasabah', fn($q) => $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('no_cif', 'like', "%{$search}%"));
            });
        }

        $perPage      = $request->get('per_page', 10);
        $perpanjangan = $query->latest()->paginate($perPage)->withQueryString();
        $branches     = \App\Models\Branch::where('status', 'aktif')->get();

        return view("{$role}.perpanjangan.index", compact('perpanjangan', 'branches'));
    }

    public function create(Request $request)
    {
        $role = Auth::user()->role;

        $request->validate([
            'gadai_id' => 'required|exists:gadai,id',
        ]);

        $gadai = Gadai::with(['nasabah', 'barang', 'branch'])->findOrFail($request->gadai_id);

        if ($role !== 'superadmin' && $gadai->cabang_id !== Auth::user()->cabang_id) {
            abort(403);
        }

        if (!in_array($gadai->status, ['aktif', 'jatuh_tempo', 'perpanjangan'])) {
            return redirect()->back()->with('error', 'Gadai tidak dapat diperpanjang.');
        }

        // Cek apakah sudah ada perpanjangan menunggu
        $perpanjanganMenunggu = Perpanjangan::where('gadai_id', $gadai->id)
            ->where('status_bayar', 'menunggu')
            ->first();

        if ($perpanjanganMenunggu) {
            return redirect()
                ->route($role . '.transaksi.perpanjangan.show', $perpanjanganMenunggu->id)
                ->with('info', 'Ada perpanjangan yang belum selesai dibayar.');
        }

        $hitungan = HitungBiayaHelper::hitungPerpanjangan($gadai);

        return view("{$role}.perpanjangan.create", compact('gadai', 'hitungan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gadai_id'     => 'required|exists:gadai,id',
            'metode_bayar' => 'required|in:tunai,midtrans',
        ]);

        $gadai    = Gadai::with(['nasabah', 'barang', 'branch'])->findOrFail($request->gadai_id);
        $hitungan = HitungBiayaHelper::hitungPerpanjangan($gadai);

        if ($request->metode_bayar === 'tunai') {
            return $this->storeTunai($gadai, $hitungan);
        } else {
            return $this->storeMidtrans($gadai, $hitungan);
        }
    }

    private function storeTunai($gadai, $hitungan)
    {
        /** @var Perpanjangan $perpanjangan */
        $perpanjangan = null;

        DB::transaction(function () use ($gadai, $hitungan, &$perpanjangan) {
            $prefix = Carbon::today()->format('ym') . strtoupper($gadai->branch->kode);
            $last   = Perpanjangan::where('no_sbg', 'like', $prefix . '%')->count();
            $noSbg  = $prefix . 'P' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);

            $perpanjangan = Perpanjangan::create([
                'gadai_id'         => $gadai->id,
                'nasabah_id'       => $gadai->nasabah_id,
                'officer_id'       => Auth::id(),
                'no_sbg'           => $noSbg,
                'nilai_pinjaman'   => $hitungan['nilai_pinjaman'],
                'jasa_persen'      => $hitungan['jasa_persen'],
                'jasa_nominal'     => $hitungan['jasa_nominal'],
                'denda_persen'     => $hitungan['denda_persen'],
                'denda_nominal'    => $hitungan['denda_nominal'],
                'hari_terlambat'   => $hitungan['hari_terlambat'],
                'total_bayar'      => $hitungan['total_bayar'],
                'tgl_perpanjangan' => Carbon::today(),
                'tgl_jt_lama'      => $gadai->tgl_jatuh_tempo,
                'tgl_jt_baru'      => $hitungan['tgl_jt_baru_raw'],
                'status_bayar'     => 'berhasil',
                'metode_bayar'     => 'tunai',
            ]);

            $gadai->update([
                'tgl_jatuh_tempo' => $hitungan['tgl_jt_baru_raw'],
                'status'          => 'perpanjangan',
                'total_tebus'     => $gadai->nilai_pinjaman + ($gadai->nilai_pinjaman * 0.05),
            ]);

            Sbg::create([
                'no_sbg'        => $noSbg,
                'nasabah_id'    => $gadai->nasabah_id,
                'gadai_id'      => $gadai->id,
                'tipe'          => 'perpanjangan',
                'referensi_id'  => $perpanjangan->id,
                'tgl_transaksi' => Carbon::today(),
                'qr_token'      => Str::uuid()->toString(),
            ]);
        });

        return redirect()
            ->route(Auth::user()->role . '.transaksi.perpanjangan.show', $perpanjangan->id)
            ->with('success', 'Perpanjangan berhasil! Jatuh tempo baru: ' . $hitungan['tgl_jt_baru'])
            ->with('show_modal', true);
    }

    private function storeMidtrans($gadai, $hitungan)
    {
        $orderId = 'PRP-' . $gadai->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $hitungan['total_bayar'],
            ],
            'customer_details' => [
                'first_name' => $gadai->nasabah->nama,
                'phone'      => $gadai->nasabah->no_hp,
            ],
            'item_details' => [
                [
                    'id'       => 'JASA',
                    'price'    => (int) $hitungan['jasa_nominal'],
                    'quantity' => 1,
                    'name'     => 'Jasa Perpanjangan ' . $gadai->no_sbg,
                ],
            ],
        ];

        if ($hitungan['denda_nominal'] > 0) {
            $params['item_details'][] = [
                'id'       => 'DENDA',
                'price'    => (int) $hitungan['denda_nominal'],
                'quantity' => 1,
                'name'     => 'Denda ' . $hitungan['denda_persen'] . '%',
            ];
        }

        try {
            $snapToken = MidtransHelper::createSnapToken($params);

            $prefix = Carbon::today()->format('ym') . strtoupper($gadai->branch->kode);
            $last   = Perpanjangan::where('no_sbg', 'like', $prefix . '%')->count();
            $noSbg  = $prefix . 'P' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);

            $perpanjangan = Perpanjangan::create([
                'gadai_id'          => $gadai->id,
                'nasabah_id'        => $gadai->nasabah_id,
                'officer_id'        => Auth::id(),
                'no_sbg'            => $noSbg,
                'nilai_pinjaman'    => $hitungan['nilai_pinjaman'],
                'jasa_persen'       => $hitungan['jasa_persen'],
                'jasa_nominal'      => $hitungan['jasa_nominal'],
                'denda_persen'      => $hitungan['denda_persen'],
                'denda_nominal'     => $hitungan['denda_nominal'],
                'hari_terlambat'    => $hitungan['hari_terlambat'],
                'total_bayar'       => $hitungan['total_bayar'],
                'tgl_perpanjangan'  => Carbon::today(),
                'tgl_jt_lama'       => $gadai->tgl_jatuh_tempo,
                'tgl_jt_baru'       => $hitungan['tgl_jt_baru_raw'],
                'status_bayar'      => 'menunggu',
                'metode_bayar'      => 'midtrans',
                'midtrans_order_id' => $orderId,
                'midtrans_token'    => $snapToken,
                'midtrans_url'      => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
            ]);

            return redirect()
                ->route(Auth::user()->role . '.transaksi.perpanjangan.show', $perpanjangan->id)
                ->with('snap_token', $snapToken);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat transaksi Midtrans: ' . $e->getMessage());
        }
    }

    public function show(Perpanjangan $perpanjangan)
    {
        $role = Auth::user()->role;
        $perpanjangan->load(['gadai.nasabah', 'gadai.barang', 'gadai.branch', 'officer']);
        return view("{$role}.perpanjangan.show", compact('perpanjangan'));
    }

    public function retry(Perpanjangan $perpanjangan)
    {
        if ($perpanjangan->status_bayar !== 'menunggu') {
            return redirect()->back()->with('error', 'Transaksi ini tidak bisa diulang.');
        }

        $gadai    = Gadai::with(['nasabah', 'branch'])->findOrFail($perpanjangan->gadai_id);
        $hitungan = HitungBiayaHelper::hitungPerpanjangan($gadai);
        $orderId  = 'PRP-' . $gadai->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $hitungan['total_bayar'],
            ],
            'customer_details' => [
                'first_name' => $gadai->nasabah->nama,
                'phone'      => $gadai->nasabah->no_hp,
            ],
            'item_details' => [
                [
                    'id'       => 'JASA',
                    'price'    => (int) $hitungan['jasa_nominal'],
                    'quantity' => 1,
                    'name'     => 'Jasa Perpanjangan ' . $gadai->no_sbg,
                ],
            ],
        ];

        if ($hitungan['denda_nominal'] > 0) {
            $params['item_details'][] = [
                'id'       => 'DENDA',
                'price'    => (int) $hitungan['denda_nominal'],
                'quantity' => 1,
                'name'     => 'Denda ' . $hitungan['denda_persen'] . '%',
            ];
        }

        try {
            $snapToken = MidtransHelper::createSnapToken($params);

            $perpanjangan->update([
                'midtrans_order_id' => $orderId,
                'midtrans_token'    => $snapToken,
                'midtrans_url'      => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
            ]);

            return redirect()
                ->route(Auth::user()->role . '.transaksi.perpanjangan.show', $perpanjangan->id)
                ->with('snap_token', $snapToken);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat ulang transaksi: ' . $e->getMessage());
        }
    }
}
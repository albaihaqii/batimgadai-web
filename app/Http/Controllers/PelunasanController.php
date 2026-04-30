<?php

namespace App\Http\Controllers;

use App\Models\Gadai;
use App\Models\Pelunasan;
use App\Models\Locker;
use App\Models\Sbg;
use App\Helpers\HitungBiayaHelper;
use App\Helpers\MidtransHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PelunasanController extends Controller
{
    public function index(Request $request)
    {
        $role  = Auth::user()->role;
        $query = Pelunasan::with(['gadai', 'nasabah', 'officer']);

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

        $perPage   = $request->get('per_page', 10);
        $pelunasan = $query->latest()->paginate($perPage)->withQueryString();
        $branches  = \App\Models\Branch::where('status', 'aktif')->get();

        return view("{$role}.pelunasan.index", compact('pelunasan', 'branches'));
    }

    public function create(Request $request)
    {
        $role = Auth::user()->role;

        $request->validate([
            'gadai_id' => 'required|exists:gadai,id',
        ]);

        $gadai = Gadai::with(['nasabah', 'barang', 'branch', 'loker'])->findOrFail($request->gadai_id);

        if ($role !== 'superadmin' && $gadai->cabang_id !== Auth::user()->cabang_id) {
            abort(403);
        }

        // Hanya bisa lunasi jika status aktif/jatuh_tempo/perpanjangan
        if (!in_array($gadai->status, ['aktif', 'jatuh_tempo', 'perpanjangan'])) {
            return redirect()->back()->with('error', 'Gadai tidak dapat dilunasi.');
        }

        // Cek apakah sudah ada pelunasan menunggu
        $pelunasanMenunggu = Pelunasan::where('gadai_id', $gadai->id)
            ->where('status_bayar', 'menunggu')
            ->first();

        if ($pelunasanMenunggu) {
            return redirect()
                ->route($role . '.transaksi.pelunasan.show', $pelunasanMenunggu->id)
                ->with('info', 'Ada pelunasan yang belum selesai dibayar.');
        }

        $hitungan = HitungBiayaHelper::hitungPelunasan($gadai);

        return view("{$role}.pelunasan.create", compact('gadai', 'hitungan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gadai_id'     => 'required|exists:gadai,id',
            'metode_bayar' => 'required|in:tunai,midtrans',
        ]);

        $gadai    = Gadai::with(['nasabah', 'barang', 'branch', 'loker'])->findOrFail($request->gadai_id);
        $hitungan = HitungBiayaHelper::hitungPelunasan($gadai);

        if ($request->metode_bayar === 'tunai') {
            return $this->storeTunai($gadai, $hitungan);
        } else {
            return $this->storeMidtrans($gadai, $hitungan);
        }
    }

    private function storeTunai($gadai, $hitungan)
    {
        /** @var Pelunasan $pelunasan */
        $pelunasan = null;

        DB::transaction(function () use ($gadai, $hitungan, &$pelunasan) {
            $prefix = Carbon::today()->format('ym') . strtoupper($gadai->branch->kode);
            $last   = Pelunasan::where('no_sbg', 'like', $prefix . '%')->count();
            $noSbg  = $prefix . 'L' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);

            $pelunasan = Pelunasan::create([
                'gadai_id'       => $gadai->id,
                'nasabah_id'     => $gadai->nasabah_id,
                'officer_id'     => Auth::id(),
                'no_sbg'         => $noSbg,
                'nilai_pinjaman' => $hitungan['nilai_pinjaman'],
                'jasa_persen'    => $hitungan['jasa_persen'],
                'jasa_nominal'   => $hitungan['jasa_nominal'],
                'denda_persen'   => $hitungan['denda_persen'],
                'denda_nominal'  => $hitungan['denda_nominal'],
                'hari_terlambat' => $hitungan['hari_terlambat'],
                'total_tebus'    => $hitungan['total_tebus'],
                'tgl_pelunasan'  => Carbon::today(),
                'tgl_jt'         => $gadai->tgl_jatuh_tempo,
                'status_bayar'   => 'berhasil',
                'metode_bayar'   => 'tunai',
            ]);

            $gadai->update(['status' => 'lunas']);

            if ($gadai->loker_id) {
                Locker::where('id', $gadai->loker_id)->update([
                    'status'   => 'kosong',
                    'gadai_id' => null,
                ]);
            }

            Sbg::create([
                'no_sbg'        => $noSbg,
                'nasabah_id'    => $gadai->nasabah_id,
                'gadai_id'      => $gadai->id,
                'tipe'          => 'pelunasan',
                'referensi_id'  => $pelunasan->id,
                'tgl_transaksi' => Carbon::today(),
                'qr_token'      => Str::uuid()->toString(),
            ]);
        });

        return redirect()
            ->route(Auth::user()->role . '.transaksi.pelunasan.show', $pelunasan->id)
            ->with('success', 'Pelunasan berhasil! Barang dapat diambil.')
            ->with('show_modal', true);
    }

    private function storeMidtrans($gadai, $hitungan)
    {
        $orderId = 'LNS-' . $gadai->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $hitungan['total_tebus'],
            ],
            'customer_details' => [
                'first_name' => $gadai->nasabah->nama,
                'phone'      => $gadai->nasabah->no_hp,
            ],
            'item_details' => [
                [
                    'id'       => 'POKOK',
                    'price'    => (int) $hitungan['nilai_pinjaman'],
                    'quantity' => 1,
                    'name'     => 'Pokok Pinjaman ' . $gadai->no_sbg,
                ],
                [
                    'id'       => 'JASA',
                    'price'    => (int) $hitungan['jasa_nominal'],
                    'quantity' => 1,
                    'name'     => 'Biaya Jasa 5%',
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
            $last   = Pelunasan::where('no_sbg', 'like', $prefix . '%')->count();
            $noSbg  = $prefix . 'L' . str_pad($last + 1, 5, '0', STR_PAD_LEFT);

            $pelunasan = Pelunasan::create([
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
                'total_tebus'       => $hitungan['total_tebus'],
                'tgl_pelunasan'     => Carbon::today(),
                'tgl_jt'            => $gadai->tgl_jatuh_tempo,
                'status_bayar'      => 'menunggu',
                'metode_bayar'      => 'midtrans',
                'midtrans_order_id' => $orderId,
                'midtrans_token'    => $snapToken,
                'midtrans_url'      => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
            ]);

            return redirect()
                ->route(Auth::user()->role . '.transaksi.pelunasan.show', $pelunasan->id)
                ->with('snap_token', $snapToken);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat transaksi Midtrans: ' . $e->getMessage());
        }
    }

    public function show(Pelunasan $pelunasan)
    {
        $role = Auth::user()->role;
        $pelunasan->load(['gadai.nasabah', 'gadai.barang', 'gadai.branch', 'officer']);
        return view("{$role}.pelunasan.show", compact('pelunasan'));
    }

    public function retry(Pelunasan $pelunasan)
    {
        if ($pelunasan->status_bayar !== 'menunggu') {
            return redirect()->back()->with('error', 'Transaksi ini tidak bisa diulang.');
        }

        $gadai    = Gadai::with(['nasabah', 'branch'])->findOrFail($pelunasan->gadai_id);
        $hitungan = HitungBiayaHelper::hitungPelunasan($gadai);
        $orderId  = 'LNS-' . $gadai->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $hitungan['total_tebus'],
            ],
            'customer_details' => [
                'first_name' => $gadai->nasabah->nama,
                'phone'      => $gadai->nasabah->no_hp,
            ],
            'item_details' => [
                [
                    'id'       => 'POKOK',
                    'price'    => (int) $hitungan['nilai_pinjaman'],
                    'quantity' => 1,
                    'name'     => 'Pokok Pinjaman ' . $gadai->no_sbg,
                ],
                [
                    'id'       => 'JASA',
                    'price'    => (int) $hitungan['jasa_nominal'],
                    'quantity' => 1,
                    'name'     => 'Biaya Jasa 5%',
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

            $pelunasan->update([
                'midtrans_order_id' => $orderId,
                'midtrans_token'    => $snapToken,
                'midtrans_url'      => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
            ]);

            return redirect()
                ->route(Auth::user()->role . '.transaksi.pelunasan.show', $pelunasan->id)
                ->with('snap_token', $snapToken);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat ulang transaksi: ' . $e->getMessage());
        }
    }
}
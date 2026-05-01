<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gadai;
use App\Models\Barang;
use App\Models\ApprovalGadai;
use App\Models\Notification;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GadaiController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $query = Gadai::with(['nasabah', 'barang', 'branch', 'officer']);

        if ($user->role !== 'superadmin') {
            $query->where('cabang_id', $user->cabang_id);
        } elseif ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        if ($request->filled('status'))  $query->where('status', $request->status);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('no_sbg', 'like', "%{$search}%")
                ->orWhereHas('nasabah', fn($q) => $q->where('nama', 'like', "%{$search}%")));
        }

        $gadais = $query->latest()->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data'    => $gadais->map(fn($g) => [
                'id'                  => $g->id,
                'no_sbg'              => $g->no_sbg,
                'nasabah'             => ['nama' => $g->nasabah?->nama, 'no_cif' => $g->nasabah?->no_cif],
                'barang'              => ['nama_barang' => $g->barang?->nama_barang, 'kategori' => $g->barang?->kategori],
                'nilai_taksiran_min'  => $g->nilai_taksiran_min,
                'nilai_taksiran_max'  => $g->nilai_taksiran_max,
                'nilai_pinjaman'      => $g->nilai_pinjaman,
                'jasa_nominal'        => $g->jasa_nominal,
                'total_tebus'         => $g->total_tebus,
                'tgl_gadai'           => $g->tgl_gadai?->format('d M Y'),
                'tgl_jatuh_tempo'     => $g->tgl_jatuh_tempo?->format('d M Y'),
                'status'              => $g->status,
                'officer'             => $g->officer?->nama,
                'created_at'          => $g->created_at->format('d M Y'),
            ]),
            'meta' => ['total' => $gadais->total(), 'per_page' => $gadais->perPage(), 'current_page' => $gadais->currentPage(), 'last_page' => $gadais->lastPage()],
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'nasabah_id'         => 'required|exists:nasabah,id',
            'nama_barang'        => 'required|string|max:150',
            'kategori'           => 'required|in:handphone,laptop,tablet,elektronik_lainnya,kendaraan_motor,barang_rumah_tangga',
            'merk'               => 'required|string|max:100',
            'tipe_model'         => 'required|string|max:100',
            'kondisi'            => 'required|in:baik,cukup,rusak_ringan',
            'kelengkapan'        => 'required|string',
            'nilai_taksiran_min' => 'required|numeric|min:1',
            'nilai_taksiran_max' => 'required|numeric|min:1|gte:nilai_taksiran_min',
        ]);

        DB::transaction(function () use ($request, $user, &$gadai) {
            $cabangId = $user->role === 'superadmin'
                ? Customer::find($request->nasabah_id)->cabang_id
                : $user->cabang_id;

            $barang = Barang::create([
                'nasabah_id'  => $request->nasabah_id,
                'nama_barang' => $request->nama_barang,
                'kategori'    => $request->kategori,
                'merk'        => $request->merk,
                'tipe_model'  => $request->tipe_model,
                'kondisi'     => $request->kondisi,
                'kelengkapan' => $request->kelengkapan,
            ]);

            $gadai = Gadai::create([
                'nasabah_id'         => $request->nasabah_id,
                'barang_id'          => $barang->id,
                'cabang_id'          => $cabangId,
                'officer_id'         => $user->id,
                'nilai_taksiran_min' => $request->nilai_taksiran_min,
                'nilai_taksiran_max' => $request->nilai_taksiran_max,
                'status'             => 'menunggu_approval',
            ]);

            ApprovalGadai::create(['gadai_id' => $gadai->id, 'status' => 'menunggu']);

            User::where('role', 'admin')->where('cabang_id', $cabangId)->get()
                ->each(fn($a) => Notification::kirimKeUser($a->id, 'Pengajuan Gadai Baru', 'Ada pengajuan gadai baru dari nasabah ' . $gadai->nasabah->nama, 'pengajuan_gadai', 'gadai', $gadai->id));
        });

        return response()->json(['success' => true, 'message' => 'Pengajuan gadai berhasil disimpan.', 'data' => ['id' => $gadai->id, 'status' => $gadai->status]], 201);
    }

    public function show(Request $request, Gadai $gadai)
    {
        $user = $request->user();
        if ($user->role !== 'superadmin' && $gadai->cabang_id !== $user->cabang_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $gadai->load(['nasabah', 'barang', 'branch', 'officer', 'admin', 'approval', 'sbg', 'loker']);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'                  => $gadai->id,
                'no_sbg'              => $gadai->no_sbg,
                'nasabah'             => ['id' => $gadai->nasabah?->id, 'nama' => $gadai->nasabah?->nama, 'no_cif' => $gadai->nasabah?->no_cif, 'no_ktp' => $gadai->nasabah?->no_ktp, 'no_hp' => $gadai->nasabah?->no_hp, 'alamat' => $gadai->nasabah?->alamat],
                'barang'              => ['nama_barang' => $gadai->barang?->nama_barang, 'kategori' => $gadai->barang?->kategori, 'merk' => $gadai->barang?->merk, 'tipe_model' => $gadai->barang?->tipe_model, 'kondisi' => $gadai->barang?->kondisi, 'kelengkapan' => $gadai->barang?->kelengkapan, 'foto' => $gadai->barang?->foto ? asset('storage/' . $gadai->barang->foto) : null],
                'cabang'              => $gadai->branch?->nama,
                'loker'               => $gadai->loker?->kode_loker,
                'officer'             => $gadai->officer?->nama,
                'admin'               => $gadai->admin?->nama,
                'nilai_taksiran_min'  => $gadai->nilai_taksiran_min,
                'nilai_taksiran_max'  => $gadai->nilai_taksiran_max,
                'nilai_pinjaman'      => $gadai->nilai_pinjaman,
                'jasa_nominal'        => $gadai->jasa_nominal,
                'total_tebus'         => $gadai->total_tebus,
                'tgl_gadai'           => $gadai->tgl_gadai?->format('d M Y'),
                'tgl_jatuh_tempo'     => $gadai->tgl_jatuh_tempo?->format('d M Y'),
                'status'              => $gadai->status,
                'sbg'                 => $gadai->sbg->map(fn($s) => ['no_sbg' => $s->no_sbg, 'tipe' => $s->tipe, 'tgl_transaksi' => $s->tgl_transaksi->format('d M Y'), 'qr_token' => $s->qr_token]),
                'created_at'          => $gadai->created_at->format('d M Y, H:i'),
            ],
        ]);
    }

    public function destroy(Request $request, Gadai $gadai)
    {
        $user = $request->user();
        if ($user->role !== 'superadmin' && $gadai->cabang_id !== $user->cabang_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }
        if ($gadai->status !== 'menunggu_approval') {
            return response()->json(['success' => false, 'message' => 'Gadai tidak dapat dihapus karena sudah diproses.'], 422);
        }

        DB::transaction(function () use ($gadai) {
            $barangId = $gadai->barang_id;
            $gadai->approval()->delete();
            $gadai->delete();
            \App\Models\Barang::where('id', $barangId)->delete();
        });

        return response()->json(['success' => true, 'message' => 'Pengajuan gadai berhasil dihapus.']);
    }
}
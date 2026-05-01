<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gadai;
use App\Models\Locker;
use App\Models\Sbg;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApprovalController extends Controller
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

        $status = $request->get('status', 'menunggu_approval');
        $query->where('status', $status);

        $pengajuan = $query->latest()->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data'    => $pengajuan->map(fn($g) => [
                'id'                 => $g->id,
                'nasabah'            => ['nama' => $g->nasabah?->nama, 'no_cif' => $g->nasabah?->no_cif],
                'barang'             => ['nama_barang' => $g->barang?->nama_barang, 'kategori' => $g->barang?->kategori],
                'nilai_taksiran_min' => $g->nilai_taksiran_min,
                'nilai_taksiran_max' => $g->nilai_taksiran_max,
                'officer'            => $g->officer?->nama,
                'cabang'             => $g->branch?->nama,
                'status'             => $g->status,
                'created_at'         => $g->created_at->format('d M Y'),
            ]),
            'meta' => ['total' => $pengajuan->total(), 'per_page' => $pengajuan->perPage(), 'current_page' => $pengajuan->currentPage(), 'last_page' => $pengajuan->lastPage()],
        ]);
    }

    public function show(Request $request, Gadai $gadai)
    {
        $user = $request->user();
        if ($user->role !== 'superadmin' && $gadai->cabang_id !== $user->cabang_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $gadai->load(['nasabah', 'barang', 'branch', 'officer', 'approval']);
        $lokers = Locker::where('cabang_id', $gadai->cabang_id)->where('status', 'kosong')->get();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'                 => $gadai->id,
                'nasabah'            => ['nama' => $gadai->nasabah?->nama, 'no_cif' => $gadai->nasabah?->no_cif, 'no_ktp' => $gadai->nasabah?->no_ktp],
                'barang'             => ['nama_barang' => $gadai->barang?->nama_barang, 'kategori' => $gadai->barang?->kategori, 'merk' => $gadai->barang?->merk, 'kondisi' => $gadai->barang?->kondisi, 'kelengkapan' => $gadai->barang?->kelengkapan, 'foto' => $gadai->barang?->foto ? asset('storage/' . $gadai->barang->foto) : null],
                'nilai_taksiran_min' => $gadai->nilai_taksiran_min,
                'nilai_taksiran_max' => $gadai->nilai_taksiran_max,
                'officer'            => $gadai->officer?->nama,
                'cabang'             => $gadai->branch?->nama,
                'status'             => $gadai->status,
                'created_at'         => $gadai->created_at->format('d M Y'),
            ],
            'lokers_kosong' => $lokers->map(fn($l) => ['id' => $l->id, 'kode_loker' => $l->kode_loker, 'rak' => $l->rak, 'keterangan' => $l->keterangan]),
        ]);
    }

    public function proses(Request $request, Gadai $gadai)
    {
        $user = $request->user();
        if ($user->role !== 'superadmin' && $gadai->cabang_id !== $user->cabang_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'aksi'        => 'required|in:setujui,tolak',
            'nilai_final' => 'required_if:aksi,setujui|nullable|numeric|min:1',
            'loker_id'    => 'required_if:aksi,setujui|nullable|exists:loker,id',
            'catatan'     => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $gadai, $user) {
            if ($request->aksi === 'tolak') {
                $gadai->approval->update(['admin_id' => $user->id, 'status' => 'ditolak', 'catatan' => $request->catatan, 'tgl_diproses' => now()]);
                $gadai->update(['admin_id' => $user->id, 'status' => 'ditolak']);
                Notification::kirimKeUser($gadai->officer_id, 'Pengajuan Gadai Ditolak', 'Pengajuan gadai nasabah ' . $gadai->nasabah->nama . ' ditolak.' . ($request->catatan ? ' Catatan: ' . $request->catatan : ''), 'approval_gadai', 'gadai', $gadai->id);
            } else {
                $nilaiPinjaman = $request->nilai_final;
                $jasaNominal   = $nilaiPinjaman * 0.05;
                $totalTebus    = $nilaiPinjaman + $jasaNominal;
                $tglGadai      = Carbon::today();
                $tglJT         = Carbon::today()->addDays(30);
                $prefix        = $tglGadai->format('ym') . strtoupper($gadai->branch->kode);
                $last          = Gadai::where('no_sbg', 'like', $prefix . '%')->count();
                $noSbg         = $prefix . str_pad($last + 1, 6, '0', STR_PAD_LEFT);

                $gadai->approval->update(['admin_id' => $user->id, 'status' => 'disetujui', 'nilai_final' => $nilaiPinjaman, 'catatan' => $request->catatan, 'tgl_diproses' => now()]);
                $gadai->update(['no_sbg' => $noSbg, 'admin_id' => $user->id, 'loker_id' => $request->loker_id, 'nilai_taksiran_akhir' => $nilaiPinjaman, 'nilai_pinjaman' => $nilaiPinjaman, 'jasa_persen' => 5.00, 'jasa_nominal' => $jasaNominal, 'total_tebus' => $totalTebus, 'tgl_gadai' => $tglGadai, 'tgl_jatuh_tempo' => $tglJT, 'status' => 'aktif']);
                Locker::where('id', $request->loker_id)->update(['status' => 'terisi', 'gadai_id' => $gadai->id]);
                Sbg::create(['no_sbg' => $noSbg, 'nasabah_id' => $gadai->nasabah_id, 'gadai_id' => $gadai->id, 'tipe' => 'gadai', 'referensi_id' => $gadai->id, 'tgl_transaksi' => $tglGadai, 'qr_token' => \Illuminate\Support\Str::uuid()->toString()]);
                Notification::kirimKeUser($gadai->officer_id, 'Pengajuan Gadai Disetujui', 'Pengajuan gadai nasabah ' . $gadai->nasabah->nama . ' disetujui. No SBG: ' . $noSbg, 'approval_gadai', 'gadai', $gadai->id);
            }
        });

        $pesan = $request->aksi === 'setujui' ? 'Pengajuan gadai berhasil disetujui.' : 'Pengajuan gadai berhasil ditolak.';
        return response()->json(['success' => true, 'message' => $pesan]);
    }
}
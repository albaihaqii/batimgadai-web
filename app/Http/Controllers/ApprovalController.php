<?php

namespace App\Http\Controllers;

use App\Models\Gadai;
use App\Models\Locker;
use App\Models\Sbg;
use App\Models\Notification;
use App\Helpers\HitungBiayaHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $role  = Auth::user()->role;
        $query = Gadai::with(['nasabah', 'barang', 'branch', 'officer', 'approval']);

        if ($role !== 'superadmin') {
            $query->where('cabang_id', Auth::user()->cabang_id);
        }

        if ($role === 'superadmin' && $request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        $status = $request->get('status', 'menunggu_approval');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'menunggu_approval');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('nasabah', fn($q) => $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_cif', 'like', "%{$search}%"));
            });
        }

        if ($request->has('export')) {
            $pengajuan = $query->latest()->get();
            $pdf = Pdf::loadView('exports.approval', compact('pengajuan'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('data-approval-gadai-' . now()->format('Ymd') . '.pdf');
        }

        $perPage   = $request->get('per_page', 10);
        $pengajuan = $query->latest()->paginate($perPage)->withQueryString();
        $branches  = \App\Models\Branch::where('status', 'aktif')->get();

        return view("{$role}.approval.index", compact('pengajuan', 'branches', 'status'));
    }

    public function show(Gadai $gadai)
    {
        $role = Auth::user()->role;

        if ($role !== 'superadmin' && $gadai->cabang_id !== Auth::user()->cabang_id) {
            abort(403);
        }

        $gadai->load(['nasabah', 'barang', 'branch', 'officer', 'approval']);

        $lokers = Locker::where('cabang_id', $gadai->cabang_id)
            ->where('status', 'kosong')
            ->orderBy('kode_loker')
            ->get();

        return view("{$role}.approval.show", compact('gadai', 'lokers'));
    }

    public function proses(Request $request, Gadai $gadai)
    {
        $role = Auth::user()->role;

        if ($role !== 'superadmin' && $gadai->cabang_id !== Auth::user()->cabang_id) {
            abort(403);
        }

        $request->validate([
            'aksi'        => 'required|in:setujui,tolak',
            'nilai_final' => 'required_if:aksi,setujui|nullable|numeric|min:1',
            'loker_id'    => 'required_if:aksi,setujui|nullable|exists:loker,id',
            'catatan'     => 'nullable|string',
        ], [
            'nilai_final.required_if' => 'Nilai taksiran akhir wajib diisi saat menyetujui.',
            'loker_id.required_if'    => 'Pilih loker untuk menyimpan barang.',
        ]);

        DB::transaction(function () use ($request, $gadai) {

            if ($request->aksi === 'tolak') {
                $gadai->approval->update([
                    'admin_id'     => Auth::id(),
                    'status'       => 'ditolak',
                    'catatan'      => $request->catatan,
                    'tgl_diproses' => now(),
                ]);

                $gadai->update([
                    'admin_id' => Auth::id(),
                    'status'   => 'ditolak',
                ]);

                Notification::kirimKeUser(
                    $gadai->officer_id,
                    'Pengajuan Gadai Ditolak',
                    'Pengajuan gadai nasabah ' . $gadai->nasabah->nama . ' telah ditolak.' . ($request->catatan ? ' Catatan: ' . $request->catatan : ''),
                    'approval_gadai',
                    'gadai',
                    $gadai->id
                );

            } else {
                $nilaiPinjaman = (int) $request->nilai_final;
                $tipeJasa      = HitungBiayaHelper::getTipeJasa($gadai->barang->kategori ?? 'handphone');
                $rate          = HitungBiayaHelper::getJasaRate($nilaiPinjaman, $tipeJasa);

                $jasaPersen  = $rate['jasa_30_hari'];
                $jasaNominal = round($nilaiPinjaman * ($jasaPersen / 100));
                $totalTebus  = $nilaiPinjaman + $jasaNominal;
                $tglGadai    = Carbon::today();
                $tglJt       = Carbon::today()->addDays(30);

                $noSbg = Gadai::generateNoSbg($gadai->branch->kode);

                $gadai->approval->update([
                    'admin_id'     => Auth::id(),
                    'status'       => 'disetujui',
                    'nilai_final'  => $nilaiPinjaman,
                    'catatan'      => $request->catatan,
                    'tgl_diproses' => now(),
                ]);

                $gadai->update([
                    'no_sbg'               => $noSbg,
                    'admin_id'             => Auth::id(),
                    'loker_id'             => $request->loker_id,
                    'nilai_taksiran_akhir' => $nilaiPinjaman,
                    'nilai_pinjaman'       => $nilaiPinjaman,
                    'nilai_pinjaman_awal'  => $nilaiPinjaman,
                    'tipe_jasa'            => $tipeJasa,
                    'jasa_persen'          => $jasaPersen,
                    'jasa_nominal'         => $jasaNominal,
                    'total_tebus'          => $totalTebus,
                    'tgl_gadai'            => $tglGadai,
                    'tgl_jatuh_tempo'      => $tglJt,
                    'status'               => 'aktif',
                ]);

                Locker::where('id', $request->loker_id)->update([
                    'status'   => 'terisi',
                    'gadai_id' => $gadai->id,
                ]);

                Sbg::create([
                    'no_sbg'        => $noSbg,
                    'nasabah_id'    => $gadai->nasabah_id,
                    'gadai_id'      => $gadai->id,
                    'tipe'          => 'gadai',
                    'referensi_id'  => $gadai->id,
                    'tgl_transaksi' => $tglGadai,
                    'qr_token'      => Sbg::generateQrToken(),
                ]);

                Notification::kirimKeUser(
                    $gadai->officer_id,
                    'Pengajuan Gadai Disetujui',
                    'Pengajuan gadai nasabah ' . $gadai->nasabah->nama . ' telah disetujui. No SBG: ' . $noSbg,
                    'approval_gadai',
                    'gadai',
                    $gadai->id
                );
            }
        });

        $pesan = $request->aksi === 'setujui'
            ? 'Pengajuan gadai berhasil disetujui.'
            : 'Pengajuan gadai berhasil ditolak.';

        return redirect()
            ->route("{$role}.approval.gadai")
            ->with('success', $pesan);
    }
}
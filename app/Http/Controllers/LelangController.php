<?php

namespace App\Http\Controllers;

use App\Models\Lelang;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LelangController extends Controller
{
    public function index(Request $request)
    {
        $status  = $request->get('status', '');
        $perPage = (int) $request->get('per_page', 10);
        $search  = $request->get('search', '');

        $lelang = Lelang::with(['gadai.barang', 'gadai.branch', 'nasabah'])
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('no_sbg', 'like', "%{$search}%")
                  ->orWhereHas('nasabah', fn($n) => $n->where('nama', 'like', "%{$search}%"));
            }))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $totalProses  = Lelang::where('status', 'proses')->count();
        $totalSelesai = Lelang::where('status', 'selesai')->count();
        $totalBatal   = Lelang::where('status', 'batal')->count();

        return view('superadmin.lelang.index', compact(
            'lelang', 'status', 'perPage', 'search',
            'totalProses', 'totalSelesai', 'totalBatal'
        ));
    }

    public function show(int $id)
    {
        $lelang = Lelang::with(['gadai.barang', 'gadai.branch', 'gadai.nasabah', 'nasabah', 'diprosesOleh'])
            ->findOrFail($id);

        return view('superadmin.lelang.show', compact('lelang'));
    }

    public function proses(Request $request, int $id)
    {
        $request->validate([
            'harga_terjual' => 'required|numeric|min:0',
            'tgl_lelang'    => 'required|date',
            'keterangan'    => 'nullable|string|max:500',
        ]);

        $lelang     = Lelang::with('gadai')->findOrFail($id);
        $sisaHutang = (float) $lelang->sisa_hutang;
        $hargaJual  = (float) $request->harga_terjual;
        $selisih    = $hargaJual - $sisaHutang;

        $statusSelisih = match(true) {
            $selisih > 0  => 'lebih',
            $selisih < 0  => 'kurang',
            default       => 'pas',
        };

        $lelang->update([
            'harga_terjual'  => $hargaJual,
            'tgl_lelang'     => $request->tgl_lelang,
            'selisih'        => abs($selisih),
            'status_selisih' => $statusSelisih,
            'keterangan'     => $request->keterangan,
            'status'         => 'selesai',
            'diproses_oleh'  => Auth::id(),
        ]);

        // Update status gadai menjadi lunas
        $lelang->gadai->update(['status' => 'lunas']);

        // Notifikasi ke nasabah
        $pesanSelisih = match($statusSelisih) {
            'lebih'  => 'Kelebihan dana sebesar Rp ' . number_format(abs($selisih), 0, ',', '.') . ' akan dikembalikan kepada Anda.',
            'kurang' => 'Dana hasil lelang tidak mencukupi sisa hutang.',
            default  => 'Dana hasil lelang sesuai dengan sisa hutang.',
        };

        Notification::create([
            'tipe_penerima' => 'nasabah',
            'penerima_id'   => $lelang->nasabah_id,
            'tipe_notif'    => 'lelang',
            'judul'         => 'Barang Anda Telah Terjual',
            'pesan'         => 'Barang gadai No. SBG ' . $lelang->no_sbg . ' telah terjual seharga Rp ' . number_format($hargaJual, 0, ',', '.') . '. ' . $pesanSelisih,
            'is_read'       => false,
        ]);

        return redirect()->route('superadmin.lelang')
            ->with('success', 'Lelang berhasil diproses. Barang telah terjual.');
    }

    public function batal(Request $request, int $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:500',
        ]);

        $lelang = Lelang::with('gadai')->findOrFail($id);

        $lelang->update([
            'status'      => 'batal',
            'keterangan'  => $request->keterangan,
            'diproses_oleh' => Auth::id(),
        ]);

        return redirect()->route('superadmin.lelang')
            ->with('success', 'Lelang dibatalkan.');
    }
}
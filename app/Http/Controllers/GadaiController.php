<?php

namespace App\Http\Controllers;

use App\Models\Gadai;
use App\Models\Barang;
use App\Models\BarangFoto;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Sbg;
use App\Models\ApprovalGadai;
use App\Models\Notification;
use App\Models\User;
use App\Helpers\HitungBiayaHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class GadaiController extends Controller
{
    public function index(Request $request)
    {
        $role  = Auth::user()->role;
        $query = Gadai::with(['nasabah', 'barang', 'branch', 'officer']);

        if ($role !== 'superadmin') {
            $query->where('cabang_id', Auth::user()->cabang_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($role === 'superadmin' && $request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_sbg', 'like', "%{$search}%")
                  ->orWhereHas('nasabah', fn($q) => $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('no_cif', 'like', "%{$search}%"));
            });
        }

        if ($request->has('export')) {
            $gadais = $query->latest()->get();
            $pdf = Pdf::loadView('exports.gadai', compact('gadais'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('data-gadai-' . now()->format('Ymd') . '.pdf');
        }

        $perPage  = $request->get('per_page', 10);
        $gadais   = $query->latest()->paginate($perPage)->withQueryString();
        $branches = Branch::where('status', 'aktif')->get();

        return view("{$role}.gadai.index", compact('gadais', 'branches'));
    }

    public function create(Request $request)
    {
        $role      = Auth::user()->role;
        $customers = Customer::where('status', 'aktif')
            ->when($role !== 'superadmin', fn($q) => $q->where('cabang_id', Auth::user()->cabang_id))
            ->orderBy('nama')
            ->get();

        $selectedCustomer = null;
        if ($request->filled('nasabah_id')) {
            $selectedCustomer = Customer::find($request->nasabah_id);
        }

        return view("{$role}.gadai.create", compact('customers', 'selectedCustomer'));
    }

    public function store(Request $request)
    {
        $role = Auth::user()->role;

        $request->validate([
            'nasabah_id'          => 'required|exists:nasabah,id',
            'nama_barang'         => 'required|string|max:150',
            'kategori'            => 'required|in:handphone,laptop,tablet,elektronik_lainnya,kendaraan_motor,barang_rumah_tangga,perhiasan',
            'merk'                => 'required|string|max:100',
            'tipe_model'          => 'required|string|max:100',
            'kondisi'             => 'required|in:baik,cukup,rusak_ringan',
            'kelengkapan'         => 'required|string',
            'foto_barang'         => 'required|array|min:1|max:5',
            'foto_barang.*'       => 'image|mimes:jpg,jpeg,png|max:2048',
            'nilai_taksiran_min'  => 'required|numeric|min:1',
            'nilai_taksiran_max'  => 'required|numeric|min:1|gte:nilai_taksiran_min',
        ], [
            'nasabah_id.exists'           => 'Nasabah tidak ditemukan.',
            'foto_barang.required'        => 'Foto barang wajib diupload minimal 1.',
            'foto_barang.*.image'         => 'File harus berupa gambar.',
            'foto_barang.*.max'           => 'Ukuran foto maksimal 2MB.',
            'nilai_taksiran_min.required' => 'Nilai taksiran minimum wajib diisi.',
            'nilai_taksiran_max.required' => 'Nilai taksiran maksimum wajib diisi.',
            'nilai_taksiran_max.gte'      => 'Nilai taksiran maksimum harus lebih besar atau sama dengan minimum.',
        ]);

        DB::transaction(function () use ($request, $role) {
            // Buat data barang
            $barang = Barang::create([
                'nasabah_id'  => $request->nasabah_id,
                'nama_barang' => $request->nama_barang,
                'kategori'    => $request->kategori,
                'merk'        => $request->merk,
                'tipe_model'  => $request->tipe_model,
                'kondisi'     => $request->kondisi,
                'kelengkapan' => $request->kelengkapan,
                'foto'        => null,
            ]);

            // Simpan multi foto
            if ($request->hasFile('foto_barang')) {
                foreach ($request->file('foto_barang') as $idx => $file) {
                    $path = $file->store('foto-barang', 'public');
                    BarangFoto::create([
                        'barang_id' => $barang->id,
                        'foto_path' => $path,
                        'urutan'    => $idx,
                    ]);
                    if ($idx === 0) {
                        $barang->update(['foto' => $path]);
                    }
                }
            }

            $cabangId = $role === 'superadmin'
                ? Customer::find($request->nasabah_id)->cabang_id
                : Auth::user()->cabang_id;

            $tipeJasa = HitungBiayaHelper::getTipeJasa($request->kategori);

            $gadai = Gadai::create([
                'nasabah_id'         => $request->nasabah_id,
                'barang_id'          => $barang->id,
                'cabang_id'          => $cabangId,
                'officer_id'         => Auth::id(),
                'nilai_taksiran_min' => $request->nilai_taksiran_min,
                'nilai_taksiran_max' => $request->nilai_taksiran_max,
                'status'             => 'menunggu_approval',
                'tipe_jasa'          => $tipeJasa,
            ]);

            ApprovalGadai::create([
                'gadai_id' => $gadai->id,
                'status'   => 'menunggu',
            ]);

            $admins = User::where('role', 'admin')->where('cabang_id', $cabangId)->get();
            foreach ($admins as $admin) {
                Notification::kirimKeUser(
                    $admin->id,
                    'Pengajuan Gadai Baru',
                    'Ada pengajuan gadai baru dari nasabah ' . $gadai->nasabah->nama,
                    'pengajuan_gadai', 'gadai', $gadai->id
                );
            }

            $superadmins = User::where('role', 'superadmin')->get();
            foreach ($superadmins as $sa) {
                Notification::kirimKeUser(
                    $sa->id,
                    'Pengajuan Gadai Baru',
                    'Ada pengajuan gadai baru dari nasabah ' . $gadai->nasabah->nama,
                    'pengajuan_gadai', 'gadai', $gadai->id
                );
            }
        });

        return redirect()
            ->route("{$role}.transaksi.gadai")
            ->with('success', 'Pengajuan gadai berhasil disimpan dan menunggu approval.');
    }

    public function show(Gadai $gadai)
    {
        $role = Auth::user()->role;

        if ($role !== 'superadmin' && $gadai->cabang_id !== Auth::user()->cabang_id) {
            abort(403);
        }

        $gadai->load(['nasabah', 'barang.fotos', 'branch', 'officer', 'admin', 'approval', 'sbg']);

        return view("{$role}.gadai.show", compact('gadai'));
    }

    public function destroy(Gadai $gadai)
    {
        if ($gadai->status !== 'menunggu_approval') {
            return redirect()->back()->with('error', 'Gadai tidak dapat dihapus karena sudah diproses.');
        }

        DB::transaction(function () use ($gadai) {
            $barangId = $gadai->barang_id;
            $gadai->approval()->delete();
            $gadai->delete();
            BarangFoto::where('barang_id', $barangId)->delete();
            Barang::where('id', $barangId)->delete();
        });

        return redirect()
            ->route(Auth::user()->role . '.transaksi.gadai')
            ->with('success', 'Data gadai berhasil dihapus.');
    }

    public function verify(string $qr_token)
    {
        $sbg = Sbg::with(['nasabah', 'gadai.barang', 'gadai.branch'])
            ->where('qr_token', $qr_token)
            ->first();

        return view('sbg.verify', compact('sbg'));
    }

    public function downloadSbg(Gadai $gadai, Request $request)
    {
        $role = Auth::user()->role;

        if ($role !== 'superadmin' && $gadai->cabang_id !== Auth::user()->cabang_id) {
            abort(403);
        }

        if (!in_array($gadai->status, ['aktif', 'jatuh_tempo', 'perpanjangan', 'lunas'])) {
            return redirect()->back()->with('error', 'SBG belum tersedia karena gadai belum disetujui.');
        }

        $gadai->load(['nasabah', 'barang', 'branch', 'officer', 'admin', 'sbg']);

        $tipe = $request->get('tipe', 'gadai');
        $sbg  = $gadai->sbg->where('tipe', $tipe)->first();

        if (!$sbg) {
            $sbg = $gadai->sbg->where('tipe', 'gadai')->first();
        }

        if (!$sbg) {
            return redirect()->back()->with('error', 'Data SBG tidak ditemukan.');
        }

        $verifyUrl = url('/verify/' . $sbg->qr_token);
        $qrSvg     = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                        ->size(120)->margin(1)->generate($verifyUrl);
        $qrBase64  = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
        $namaFile  = 'SBG-' . strtoupper($tipe) . '-' . $gadai->no_sbg . '.pdf';

        $pdf = Pdf::loadView('exports.sbg', compact('gadai', 'sbg', 'qrBase64', 'verifyUrl'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($namaFile);
    }

    public function tambahPinjaman(Request $request, Gadai $gadai)
    {
        $role = Auth::user()->role;

        if ($role !== 'superadmin' && $gadai->cabang_id !== Auth::user()->cabang_id) {
            abort(403);
        }

        if (!in_array($gadai->status, ['aktif', 'perpanjangan'])) {
            return redirect()->back()->with('error', 'Gadai tidak dapat ditambah pinjaman saat ini.');
        }

        $request->validate([
            'nilai_tambahan' => 'required|integer|min:100000',
            'catatan'        => 'nullable|string|max:500',
        ]);

        $nilaiTambahan    = (int) $request->nilai_tambahan;
        $nilaiSaatIni     = (int) $gadai->nilai_pinjaman;
        $nilaiTotal       = $nilaiSaatIni + $nilaiTambahan;
        $nilaiTaksiranMax = (int) $gadai->nilai_taksiran_max;

        if ($nilaiTotal > $nilaiTaksiranMax) {
            return redirect()->back()->with('error',
                'Total pinjaman Rp ' . number_format($nilaiTotal, 0, ',', '.') .
                ' melebihi nilai taksiran maksimum Rp ' . number_format($nilaiTaksiranMax, 0, ',', '.') . '.'
            );
        }

        $tipeJasa    = $gadai->tipe_jasa ?? 'umum';
        $rate        = HitungBiayaHelper::getJasaRate($nilaiTotal, $tipeJasa);
        $jasaNominal = round($nilaiTotal * ($rate['jasa_30_hari'] / 100));
        $totalTebus  = $nilaiTotal + $jasaNominal;

        $gadai->update([
            'nilai_pinjaman'            => $nilaiTotal,
            'nilai_pinjaman_awal'       => $gadai->nilai_pinjaman_awal ?? $nilaiSaatIni,
            'nilai_pinjaman_tambahan'   => (int)(($gadai->nilai_pinjaman_tambahan ?? 0) + $nilaiTambahan),
            'catatan_tambahan_pinjaman' => $request->catatan,
            'jasa_persen'               => $rate['jasa_30_hari'],
            'jasa_nominal'              => $jasaNominal,
            'total_tebus'               => $totalTebus,
        ]);

        return redirect()
            ->route($role . '.transaksi.gadai.show', $gadai->id)
            ->with('success', 'Pinjaman berhasil ditambah. Total pinjaman sekarang Rp ' . number_format($nilaiTotal, 0, ',', '.') . '.');
    }
}
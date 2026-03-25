<?php

namespace App\Http\Controllers;

use App\Models\Locker;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LockerController extends Controller
{
    public function index(Request $request)
    {
        $role  = Auth::user()->role;
        $query = Locker::with('branch');

        // Filter by cabang
        if ($role === 'superadmin') {
            // Superadmin bisa filter per cabang via combobox
            if ($request->filled('cabang_id')) {
                $query->where('cabang_id', $request->cabang_id);
            }
        } else {
            // Admin dan officer otomatis filter cabang sendiri
            $query->where('cabang_id', Auth::user()->cabang_id);
        }

        // Filter by rak
        if ($request->filled('rak')) {
            $query->where('rak', $request->rak);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Hitung statistik sebelum paginate — supaya akurat meski data banyak
        $totalKosong = (clone $query)->where('status', 'kosong')->count();
        $totalTerisi = (clone $query)->where('status', 'terisi')->count();

        $perPage  = $request->get('per_page', 10);
        $lockers  = $query->orderBy('kode_loker')->paginate($perPage)->withQueryString();
        $branches = Branch::where('status', 'aktif')->get();

        return view("{$role}.lockers.index", compact('lockers', 'branches', 'totalKosong', 'totalTerisi'));
    }

    public function create()
    {
        $role     = Auth::user()->role;
        $branches = Branch::where('status', 'aktif')->get();
        $raks     = ['A', 'B', 'C', 'D', 'E', 'F'];
        return view("{$role}.lockers.create", compact('branches', 'raks'));
    }

    public function store(Request $request)
    {
        $role = Auth::user()->role;

        $request->validate([
            'cabang_id' => 'required|exists:cabang,id',
            'rak'       => 'required|in:A,B,C,D,E,F',
            'jumlah'    => 'required|integer|min:1|max:50',
            'keterangan'=> 'nullable|string',
        ], [
            'cabang_id.exists' => 'Cabang tidak valid.',
            'jumlah.max'       => 'Maksimal tambah 50 loker sekaligus.',
        ]);

        $cabang = Branch::find($request->cabang_id);

        // Generate loker sebanyak jumlah yang diminta
        for ($i = 0; $i < $request->jumlah; $i++) {
            Locker::create([
                'kode_loker'  => Locker::generateKode($cabang->kode, $request->rak),
                'cabang_id'   => $request->cabang_id,
                'rak'         => strtoupper($request->rak),
                'status'      => 'kosong',
                'keterangan'  => $request->keterangan,
            ]);
        }

        return redirect()
            ->route("{$role}.loker")
            ->with('success', "Berhasil menambahkan {$request->jumlah} loker di Rak {$request->rak}.");
    }

    public function destroy(Locker $locker)
    {
        $role = Auth::user()->role;

        if ($locker->status === 'terisi') {
            return redirect()->back()->with('error', 'Loker tidak dapat dihapus karena sedang terisi barang gadai.');
        }

        $locker->delete();

        return redirect()
            ->route("{$role}.loker")
            ->with('success', 'Loker berhasil dihapus.');
    }

    public function scan(string $kode_loker)
    {
        $locker = Locker::with(['branch'])
            ->where('kode_loker', strtoupper($kode_loker))
            ->firstOrFail();

        return view('loker.scan', compact('locker'));
    }
}
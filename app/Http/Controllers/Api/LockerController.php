<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Locker;
use App\Models\Branch;
use Illuminate\Http\Request;

class LockerController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $query = Locker::with('branch');

        if ($user->role !== 'superadmin') {
            $query->where('cabang_id', $user->cabang_id);
        } elseif ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        if ($request->filled('rak'))    $query->where('rak', $request->rak);
        if ($request->filled('status')) $query->where('status', $request->status);

        $totalKosong = (clone $query)->where('status', 'kosong')->count();
        $totalTerisi = (clone $query)->where('status', 'terisi')->count();

        $lockers = $query->orderBy('kode_loker')->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'stats'   => ['kosong' => $totalKosong, 'terisi' => $totalTerisi],
            'data'    => $lockers->map(fn($l) => [
                'id'          => $l->id,
                'kode_loker'  => $l->kode_loker,
                'cabang_id'   => $l->cabang_id,
                'cabang'      => $l->branch?->nama,
                'rak'         => $l->rak,
                'status'      => $l->status,
                'gadai_id'    => $l->gadai_id,
                'keterangan'  => $l->keterangan,
            ]),
            'meta' => ['total' => $lockers->total(), 'per_page' => $lockers->perPage(), 'current_page' => $lockers->currentPage(), 'last_page' => $lockers->lastPage()],
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'cabang_id'  => 'required|exists:cabang,id',
            'rak'        => 'required|in:A,B,C,D,E,F',
            'jumlah'     => 'required|integer|min:1|max:50',
            'keterangan' => 'nullable|string',
        ]);

        $cabang  = Branch::find($request->cabang_id);
        $created = [];

        for ($i = 0; $i < $request->jumlah; $i++) {
            $locker    = Locker::create([
                'kode_loker' => Locker::generateKode($cabang->kode, $request->rak),
                'cabang_id'  => $request->cabang_id,
                'rak'        => strtoupper($request->rak),
                'status'     => 'kosong',
                'keterangan' => $request->keterangan,
            ]);
            $created[] = $locker->kode_loker;
        }

        return response()->json(['success' => true, 'message' => "Berhasil generate {$request->jumlah} loker.", 'data' => ['kode_loker' => $created]], 201);
    }

    public function show(Request $request, Locker $locker)
    {
        $user = $request->user();
        if ($user->role !== 'superadmin' && $locker->cabang_id !== $user->cabang_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => ['id' => $locker->id, 'kode_loker' => $locker->kode_loker, 'cabang' => $locker->branch?->nama, 'rak' => $locker->rak, 'status' => $locker->status, 'gadai_id' => $locker->gadai_id, 'keterangan' => $locker->keterangan],
        ]);
    }

    public function destroy(Request $request, Locker $locker)
    {
        $user = $request->user();
        if ($user->role !== 'superadmin' && $locker->cabang_id !== $user->cabang_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }
        if ($locker->status === 'terisi') {
            return response()->json(['success' => false, 'message' => 'Loker tidak dapat dihapus karena sedang terisi.'], 422);
        }

        $locker->delete();
        return response()->json(['success' => true, 'message' => 'Loker berhasil dihapus.']);
    }
}
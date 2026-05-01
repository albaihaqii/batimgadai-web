<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = Branch::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('nama', 'like', "%{$search}%")
                ->orWhere('kode', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $branches = $query->latest()->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data'    => $branches->map(fn($b) => [
                'id'       => $b->id,
                'kode'     => $b->kode,
                'nama'     => $b->nama,
                'alamat'   => $b->alamat,
                'no_telp'  => $b->no_telp,
                'maps_url' => $b->maps_url,
                'status'   => $b->status,
            ]),
            'meta' => ['total' => $branches->total(), 'per_page' => $branches->perPage(), 'current_page' => $branches->currentPage(), 'last_page' => $branches->lastPage()],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'    => 'required|string|max:10|unique:cabang,kode',
            'nama'    => 'required|string|max:100',
            'alamat'  => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
            'status'  => 'required|in:aktif,nonaktif',
        ]);

        $branch = Branch::create($request->only(['kode', 'nama', 'alamat', 'no_telp', 'maps_url', 'status']));

        return response()->json(['success' => true, 'message' => 'Cabang berhasil ditambahkan.', 'data' => ['id' => $branch->id, 'kode' => $branch->kode, 'nama' => $branch->nama, 'status' => $branch->status]], 201);
    }

    public function show(Branch $branch)
    {
        return response()->json([
            'success' => true,
            'data'    => ['id' => $branch->id, 'kode' => $branch->kode, 'nama' => $branch->nama, 'alamat' => $branch->alamat, 'no_telp' => $branch->no_telp, 'maps_url' => $branch->maps_url, 'status' => $branch->status],
        ]);
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'kode'    => 'required|string|max:10|unique:cabang,kode,' . $branch->id,
            'nama'    => 'required|string|max:100',
            'alamat'  => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
            'status'  => 'required|in:aktif,nonaktif',
        ]);

        $branch->update($request->only(['kode', 'nama', 'alamat', 'no_telp', 'maps_url', 'status']));

        return response()->json(['success' => true, 'message' => 'Cabang berhasil diperbarui.', 'data' => ['id' => $branch->id, 'kode' => $branch->kode, 'nama' => $branch->nama, 'status' => $branch->status]]);
    }

    public function destroy(Branch $branch)
    {
        if ($branch->users()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Cabang tidak dapat dihapus karena masih memiliki data user.'], 422);
        }
        if ($branch->customers()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Cabang tidak dapat dihapus karena masih memiliki data nasabah.'], 422);
        }

        $branch->delete();
        return response()->json(['success' => true, 'message' => 'Cabang berhasil dihapus.']);
    }
}
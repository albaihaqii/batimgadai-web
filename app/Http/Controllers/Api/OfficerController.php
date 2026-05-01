<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class OfficerController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $query = User::with('branch')->where('role', 'officer');

        if ($user->role === 'admin') {
            $query->where('cabang_id', $user->cabang_id);
        } elseif ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('nama', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        $officers = $query->latest()->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data'    => $officers->map(fn($o) => [
                'id'         => $o->id,
                'nama'       => $o->nama,
                'email'      => $o->email,
                'cabang_id'  => $o->cabang_id,
                'cabang'     => $o->branch?->nama,
                'status'     => $o->status,
                'foto'       => $o->foto ? asset('storage/' . $o->foto) : null,
                'created_at' => $o->created_at->format('d M Y'),
            ]),
            'meta' => [
                'total'        => $officers->total(),
                'per_page'     => $officers->perPage(),
                'current_page' => $officers->currentPage(),
                'last_page'    => $officers->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'nama'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8',
            'cabang_id' => 'required|exists:cabang,id',
            'status'    => 'required|in:aktif,nonaktif',
        ]);

        $cabangId = $user->role === 'admin' ? $user->cabang_id : $request->cabang_id;

        $officer = User::create([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'officer',
            'cabang_id' => $cabangId,
            'status'    => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data petugas berhasil ditambahkan.',
            'data'    => ['id' => $officer->id, 'nama' => $officer->nama, 'email' => $officer->email, 'cabang_id' => $officer->cabang_id, 'status' => $officer->status],
        ], 201);
    }

    public function show(Request $request, User $officer)
    {
        $user = $request->user();
        if ($user->role === 'admin' && $officer->cabang_id !== $user->cabang_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => ['id' => $officer->id, 'nama' => $officer->nama, 'email' => $officer->email, 'cabang_id' => $officer->cabang_id, 'cabang' => $officer->branch?->nama, 'status' => $officer->status, 'foto' => $officer->foto ? asset('storage/' . $officer->foto) : null],
        ]);
    }

    public function update(Request $request, User $officer)
    {
        $user = $request->user();
        if ($user->role === 'admin' && $officer->cabang_id !== $user->cabang_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'nama'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $officer->id,
            'cabang_id' => 'required|exists:cabang,id',
            'status'    => 'required|in:aktif,nonaktif',
        ]);

        $officer->update($request->only(['nama', 'email', 'cabang_id', 'status']));

        return response()->json(['success' => true, 'message' => 'Data petugas berhasil diperbarui.', 'data' => ['id' => $officer->id, 'nama' => $officer->nama, 'status' => $officer->status]]);
    }

    public function destroy(Request $request, User $officer)
    {
        $user = $request->user();
        if ($user->role === 'admin' && $officer->cabang_id !== $user->cabang_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }
        if ($officer->foto) Storage::disk('public')->delete($officer->foto);
        $officer->delete();

        return response()->json(['success' => true, 'message' => 'Data petugas berhasil dihapus.']);
    }
}
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('branch')->where('role', 'admin');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('nama', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        if ($request->filled('cabang_id')) {
            $query->where('cabang_id', $request->cabang_id);
        }

        $admins = $query->latest()->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data'    => $admins->map(fn($a) => [
                'id'         => $a->id,
                'nama'       => $a->nama,
                'email'      => $a->email,
                'cabang_id'  => $a->cabang_id,
                'cabang'     => $a->branch?->nama,
                'status'     => $a->status,
                'foto'       => $a->foto ? asset('storage/' . $a->foto) : null,
                'created_at' => $a->created_at->format('d M Y'),
            ]),
            'meta' => [
                'total'        => $admins->total(),
                'per_page'     => $admins->perPage(),
                'current_page' => $admins->currentPage(),
                'last_page'    => $admins->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8',
            'cabang_id' => 'required|exists:cabang,id',
            'status'    => 'required|in:aktif,nonaktif',
        ]);

        $user = User::create([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'admin',
            'cabang_id' => $request->cabang_id,
            'status'    => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data pimpinan berhasil ditambahkan.',
            'data'    => ['id' => $user->id, 'nama' => $user->nama, 'email' => $user->email, 'cabang_id' => $user->cabang_id, 'status' => $user->status],
        ], 201);
    }

    public function show(User $admin)
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'id'         => $admin->id,
                'nama'       => $admin->nama,
                'email'      => $admin->email,
                'cabang_id'  => $admin->cabang_id,
                'cabang'     => $admin->branch?->nama,
                'status'     => $admin->status,
                'foto'       => $admin->foto ? asset('storage/' . $admin->foto) : null,
                'created_at' => $admin->created_at->format('d M Y'),
            ],
        ]);
    }

    public function update(Request $request, User $admin)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $admin->id,
            'cabang_id' => 'required|exists:cabang,id',
            'status'    => 'required|in:aktif,nonaktif',
        ]);

        $admin->update($request->only(['nama', 'email', 'cabang_id', 'status']));

        return response()->json([
            'success' => true,
            'message' => 'Data pimpinan berhasil diperbarui.',
            'data'    => ['id' => $admin->id, 'nama' => $admin->nama, 'email' => $admin->email, 'status' => $admin->status],
        ]);
    }

    public function destroy(User $admin)
    {
        if ($admin->foto) Storage::disk('public')->delete($admin->foto);
        $admin->delete();

        return response()->json(['success' => true, 'message' => 'Data pimpinan berhasil dihapus.']);
    }
}
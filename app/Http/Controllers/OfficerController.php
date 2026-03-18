<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class OfficerController extends Controller
{
    public function index(Request $request)
    {
        $role  = Auth::user()->role;
        $query = User::with('branch')->where('role', 'officer');

        // Filter by cabang untuk admin
        if ($role === 'admin') {
            $query->where('cabang_id', Auth::user()->cabang_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Export PDF
        if ($request->has('export')) {
            $officers = $query->latest()->get();
            $pdf = Pdf::loadView('exports.officers', compact('officers'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('data-petugas-' . now()->format('Ymd') . '.pdf');
        }

        $perPage  = $request->get('per_page', 10);
        $officers = $query->latest()->paginate($perPage)->withQueryString();
        $branches = Branch::where('status', 'aktif')->get();

        return view("{$role}.officers.index", compact('officers', 'branches'));
    }

    public function create()
    {
        $role     = Auth::user()->role;
        $branches = Branch::where('status', 'aktif')->get();
        return view("{$role}.officers.create", compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8',
            'cabang_id' => 'required|exists:cabang,id',
            'status'    => 'required|in:aktif,nonaktif',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'email.unique'     => 'Email sudah terdaftar.',
            'password.min'     => 'Password minimal 8 karakter.',
            'cabang_id.exists' => 'Cabang tidak valid.',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto-profil', 'public');
        }

        User::create([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'officer',
            'cabang_id' => $request->cabang_id,
            'status'    => $request->status,
            'foto'      => $fotoPath,
        ]);

        return redirect()
            ->route(Auth::user()->role . '.petugas')
            ->with('success', 'Data petugas berhasil ditambahkan.');
    }

    public function edit(User $officer)
    {
        $role     = Auth::user()->role;
        $branches = Branch::where('status', 'aktif')->get();
        return view("{$role}.officers.edit", compact('officer', 'branches'));
    }

    public function update(Request $request, User $officer)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $officer->id,
            'cabang_id' => 'required|exists:cabang,id',
            'status'    => 'required|in:aktif,nonaktif',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'email.unique'     => 'Email sudah terdaftar.',
            'cabang_id.exists' => 'Cabang tidak valid.',
        ]);

        $officer->nama      = $request->nama;
        $officer->email     = $request->email;
        $officer->cabang_id = $request->cabang_id;
        $officer->status    = $request->status;

        if ($request->hasFile('foto')) {
            if ($officer->foto) {
                Storage::disk('public')->delete($officer->foto);
            }
            $officer->foto = $request->file('foto')->store('foto-profil', 'public');
        }

        $officer->save();

        return redirect()
            ->route(Auth::user()->role . '.petugas')
            ->with('success', 'Data petugas berhasil diperbarui.');
    }

    public function destroy(User $officer)
    {
        if ($officer->foto) {
            Storage::disk('public')->delete($officer->foto);
        }
        $officer->delete();

        return redirect()
            ->route(Auth::user()->role . '.petugas')
            ->with('success', 'Data petugas berhasil dihapus.');
    }
}
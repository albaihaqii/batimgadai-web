<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('branch')->where('role', 'admin');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Export PDF
        if ($request->has('export')) {
            $admins = $query->latest()->get();
            $pdf = Pdf::loadView('exports.admins', compact('admins'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('data-pimpinan-' . now()->format('Ymd') . '.pdf');
        }

        $perPage  = $request->get('per_page', 10);
        $admins   = $query->latest()->paginate($perPage)->withQueryString();
        $branches = Branch::where('status', 'aktif')->get();

        return view('superadmin.admins.index', compact('admins', 'branches'));
    }

    public function create()
    {
        $branches = Branch::where('status', 'aktif')->get();
        return view('superadmin.admins.create', compact('branches'));
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
            'role'      => 'admin',
            'cabang_id' => $request->cabang_id,
            'status'    => $request->status,
            'foto'      => $fotoPath,
        ]);

        return redirect()
            ->route('superadmin.pimpinan')
            ->with('success', 'Data pimpinan berhasil ditambahkan.');
    }

    public function edit(User $admin)
    {
        $branches = Branch::where('status', 'aktif')->get();
        return view('superadmin.admins.edit', compact('admin', 'branches'));
    }

    public function update(Request $request, User $admin)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email,' . $admin->id,
            'cabang_id' => 'required|exists:cabang,id',
            'status'    => 'required|in:aktif,nonaktif',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'email.unique'     => 'Email sudah terdaftar.',
            'cabang_id.exists' => 'Cabang tidak valid.',
        ]);

        $admin->nama      = $request->nama;
        $admin->email     = $request->email;
        $admin->cabang_id = $request->cabang_id;
        $admin->status    = $request->status;

        if ($request->hasFile('foto')) {
            if ($admin->foto) {
                Storage::disk('public')->delete($admin->foto);
            }
            $admin->foto = $request->file('foto')->store('foto-profil', 'public');
        }

        $admin->save();

        return redirect()
            ->route('superadmin.pimpinan')
            ->with('success', 'Data pimpinan berhasil diperbarui.');
    }

    public function destroy(User $admin)
    {
        if ($admin->foto) {
            Storage::disk('public')->delete($admin->foto);
        }
        $admin->delete();

        return redirect()
            ->route('superadmin.pimpinan')
            ->with('success', 'Data pimpinan berhasil dihapus.');
    }
}
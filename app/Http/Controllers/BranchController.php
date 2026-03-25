<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $query = Branch::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('no_telp', 'like', "%{$search}%");
            });
        }

        // Export PDF
        if ($request->has('export')) {
            $branches = $query->latest()->get();
            $pdf = Pdf::loadView('exports.branches', compact('branches'))
                ->setPaper('a4', 'portrait');
            return $pdf->download('data-cabang-' . now()->format('Ymd') . '.pdf');
        }

        $perPage  = $request->get('per_page', 10);
        $branches = $query->latest()->paginate($perPage)->withQueryString();

        return view('superadmin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('superadmin.branches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'    => 'required|string|max:10|unique:cabang,kode',
            'nama'    => 'required|string|max:100',
            'alamat'  => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
            'status'  => 'required|in:aktif,nonaktif',
        ], [
            'kode.unique' => 'Kode cabang sudah digunakan.',
        ]);

        Branch::create($request->only(['kode', 'nama', 'alamat', 'no_telp', 'maps_url', 'status']));

        return redirect()
            ->route('superadmin.cabang')
            ->with('success', 'Data cabang berhasil ditambahkan.');
    }

    public function edit(Branch $branch)
    {
        return view('superadmin.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'kode'    => 'required|string|max:10|unique:cabang,kode,' . $branch->id,
            'nama'    => 'required|string|max:100',
            'alamat'  => 'nullable|string',
            'no_telp' => 'nullable|string|max:20',
            'status'  => 'required|in:aktif,nonaktif',
        ], [
            'kode.unique' => 'Kode cabang sudah digunakan.',
        ]);

        $branch->update($request->only(['kode', 'nama', 'alamat', 'no_telp', 'maps_url', 'status']));

        return redirect()
            ->route('superadmin.cabang')
            ->with('success', 'Data cabang berhasil diperbarui.');
    }

    public function destroy(Branch $branch)
    {
        // Cek apakah cabang masih punya user atau nasabah
        if ($branch->users()->count() > 0) {
            return redirect()->back()->with('error', 'Cabang tidak dapat dihapus karena masih memiliki data user.');
        }

        if ($branch->customers()->count() > 0) {
            return redirect()->back()->with('error', 'Cabang tidak dapat dihapus karena masih memiliki data nasabah.');
        }

        $branch->delete();

        return redirect()
            ->route('superadmin.cabang')
            ->with('success', 'Data cabang berhasil dihapus.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function index(Request $request)
    {
        $query = Branch::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
        }

        $branches = $query->latest()->paginate(10)->withQueryString();

        return view('superadmin.cabangs.index', compact('branches'));
    }

    public function create()
    {
        return view('superadmin.cabang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode'    => 'required|max:10',
            'nama'    => 'required|max:100',
            'alamat'  => 'required',
            'no_telp' => 'required',
            'status'  => 'required|in:aktif,nonaktif',
        ]);

        Branch::create($request->all());

        return redirect()->route('cabangs.index')
            ->with('success', 'Data cabang berhasil ditambahkan.');
    }

    public function edit(Branch $cabang)
    {
        return view('superadmin.cabangs.edit', compact('cabang'));
    }

    public function update(Request $request, Branch $cabang)
    {
        $request->validate([
            'kode'    => 'required|max:10',
            'nama'    => 'required|max:100',
            'alamat'  => 'required',
            'no_telp' => 'required',
            'status'  => 'required|in:aktif,nonaktif',
        ]);

        $cabang->update($request->all());

        return redirect()->route('cabangs.index')
            ->with('success', 'Data cabang berhasil diperbarui.');
    }

    public function destroy(Branch $cabang)
    {
        $cabang->delete();

        return redirect()->route('cabangs.index')
            ->with('success', 'Data cabang berhasil dihapus.');
    }
}
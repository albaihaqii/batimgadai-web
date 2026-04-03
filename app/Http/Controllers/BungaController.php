<?php

namespace App\Http\Controllers;

use App\Models\Bunga;
use Illuminate\Http\Request;

class BungaController extends Controller
{
    public function index()
    {
        $bungas = Bunga::paginate(10);
        return view('backend.superadmin.master.bunga.index', [
            'title' => 'Master Bunga',
            'bungas' => $bungas,
        ]);
    }

    public function create()
    {
        return view('backend.superadmin.master.bunga.create', [
            'title' => 'Create Bunga',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'persentase_bunga' => 'required|numeric|min:0|max:99.99',
            'deskripsi' => 'nullable|string',
        ]);

        Bunga::create($validated);

        return redirect()->route('bunga.index')->with('success', 'Bunga berhasil ditambahkan');
    }

    public function edit($id)
    {
        $bunga = Bunga::findOrFail($id);
        return view('backend.superadmin.master.bunga.edit', [
            'title' => 'Edit Bunga',
            'bunga' => $bunga,
        ]);
    }

    public function update(Request $request, $id)
    {
        $bunga = Bunga::findOrFail($id);

        $validated = $request->validate([
            'persentase_bunga' => 'required|numeric|min:0|max:99.99',
            'deskripsi' => 'nullable|string',
        ]);

        $bunga->update($validated);

        return redirect()->route('bunga.index')->with('success', 'Bunga berhasil diupdate');
    }

    public function destroy($id)
    {
        $bunga = Bunga::findOrFail($id);
        $bunga->delete();

        return redirect()->route('bunga.index')->with('success', 'Bunga berhasil dihapus');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use App\Models\Category;
use Illuminate\Http\Request;

class JenisBarangController extends Controller
{
    public function index()
    {
        $jenisBarang = JenisBarang::paginate(15);
        $categories = Category::all();
        return view('backend.superadmin.master.jenis_barang.index', [
            'title' => 'Master Jenis Barang',
            'jenisBarang' => $jenisBarang,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_categories' => 'required|string|exists:categories,id_categories',
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        JenisBarang::create($validated);

        return redirect()->route('jenis-barang.index')->with('success', 'Jenis Barang berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $jenisBarang = JenisBarang::findOrFail($id);

        $validated = $request->validate([
            'id_categories' => 'required|string|exists:categories,id_categories',
            'nama_jenis' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $jenisBarang->update($validated);

        return redirect()->route('jenis-barang.index')->with('success', 'Jenis Barang berhasil diupdate');
    }

    public function destroy($id)
    {
        $jenisBarang = JenisBarang::findOrFail($id);
        $jenisBarang->delete();

        return redirect()->route('jenis-barang.index')->with('success', 'Jenis Barang berhasil dihapus');
    }
}

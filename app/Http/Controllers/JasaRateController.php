<?php

namespace App\Http\Controllers;

use App\Models\JasaRate;
use Illuminate\Http\Request;

class JasaRateController extends Controller
{
    public function index()
    {
        $umum      = JasaRate::where('tipe', 'umum')->orderBy('min_pinjaman')->get();
        $perhiasan = JasaRate::where('tipe', 'perhiasan')->orderBy('min_pinjaman')->get();
        return view('superadmin.jasa-rate.index', compact('umum', 'perhiasan'));
    }

    public function create()
    {
        return view('superadmin.jasa-rate.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe'         => 'required|in:umum,perhiasan',
            'min_pinjaman' => 'required|integer|min:0',
            'max_pinjaman' => 'nullable|integer',
            'jasa_15_hari' => 'required|numeric|min:0|max:100',
            'jasa_30_hari' => 'required|numeric|min:0|max:100',
        ]);

        JasaRate::create($request->only([
            'tipe', 'min_pinjaman', 'max_pinjaman',
            'jasa_15_hari', 'jasa_30_hari',
        ]));

        return redirect()->route('superadmin.jasa-rate')
            ->with('success', 'Rate jasa berhasil ditambahkan.');
    }

    public function edit(JasaRate $jasaRate)
    {
        return view('superadmin.jasa-rate.edit', compact('jasaRate'));
    }

    public function update(Request $request, JasaRate $jasaRate)
    {
        $request->validate([
            'min_pinjaman' => 'required|integer|min:0',
            'max_pinjaman' => 'nullable|integer',
            'jasa_15_hari' => 'required|numeric|min:0|max:100',
            'jasa_30_hari' => 'required|numeric|min:0|max:100',
            'is_active'    => 'nullable|in:0,1',
        ]);

        $jasaRate->update([
            'min_pinjaman' => $request->min_pinjaman,
            'max_pinjaman' => $request->max_pinjaman ?: null,
            'jasa_15_hari' => $request->jasa_15_hari,
            'jasa_30_hari' => $request->jasa_30_hari,
            'is_active'    => $request->filled('is_active') ? (int) $request->is_active : $jasaRate->is_active,
        ]);

        return redirect()->route('superadmin.jasa-rate')
            ->with('success', 'Rate jasa berhasil diperbarui.');
    }

    public function destroy(JasaRate $jasaRate)
    {
        $jasaRate->delete();
        return redirect()->route('superadmin.jasa-rate')
            ->with('success', 'Rate jasa berhasil dihapus.');
    }
}
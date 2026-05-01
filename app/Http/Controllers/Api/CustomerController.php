<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Branch;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $user  = $request->user();
        $query = Customer::with('branch');

        // Filter cabang per role
        if ($user->role !== 'superadmin') {
            $query->where('cabang_id', $user->cabang_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_cif', 'like', "%{$search}%")
                  ->orWhere('no_ktp', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('cabang_id') && $user->role === 'superadmin') {
            $query->where('cabang_id', $request->cabang_id);
        }

        $perPage   = $request->get('per_page', 10);
        $customers = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $customers->map(fn($c) => [
                'id'            => $c->id,
                'no_cif'        => $c->no_cif,
                'nama'          => $c->nama,
                'no_ktp'        => $c->no_ktp,
                'no_hp'         => $c->no_hp,
                'alamat'        => $c->alamat,
                'cabang_id'     => $c->cabang_id,
                'cabang'        => $c->branch?->nama,
                'status'        => $c->status,
                'tgl_bergabung' => $c->tgl_bergabung?->format('d M Y'),
            ]),
            'meta' => [
                'total'        => $customers->total(),
                'per_page'     => $customers->perPage(),
                'current_page' => $customers->currentPage(),
                'last_page'    => $customers->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'nama'          => 'required|string|max:100',
            'no_ktp'        => 'required|digits:16|unique:nasabah,no_ktp',
            'no_hp'         => 'required|string|max:20',
            'alamat'        => 'required|string',
            'cabang_id'     => 'required_if:role,superadmin|exists:cabang,id',
            'status'        => 'required|in:aktif,nonaktif',
            'tgl_bergabung' => 'required|date',
        ]);

        $cabangId = $user->role === 'superadmin'
            ? $request->cabang_id
            : $user->cabang_id;

        $branch = Branch::find($cabangId);
        $last   = Customer::where('no_cif', 'like', "CIF-{$branch->kode}-%")->count();
        $noCif  = "CIF-{$branch->kode}-" . str_pad($last + 1, 6, '0', STR_PAD_LEFT);

        $customer = Customer::create([
            'no_cif'        => $noCif,
            'nama'          => $request->nama,
            'no_ktp'        => $request->no_ktp,
            'no_hp'         => $request->no_hp,
            'alamat'        => $request->alamat,
            'cabang_id'     => $cabangId,
            'status'        => $request->status,
            'tgl_bergabung' => $request->tgl_bergabung,
            'created_by'    => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Nasabah berhasil ditambahkan.',
            'data'    => [
                'id'            => $customer->id,
                'no_cif'        => $customer->no_cif,
                'nama'          => $customer->nama,
                'no_ktp'        => $customer->no_ktp,
                'no_hp'         => $customer->no_hp,
                'alamat'        => $customer->alamat,
                'cabang_id'     => $customer->cabang_id,
                'status'        => $customer->status,
                'tgl_bergabung' => $customer->tgl_bergabung?->format('d M Y'),
            ],
        ], 201);
    }

    public function show(Request $request, Customer $customer)
    {
        $user = $request->user();

        if ($user->role !== 'superadmin' && $customer->cabang_id !== $user->cabang_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'id'            => $customer->id,
                'no_cif'        => $customer->no_cif,
                'nama'          => $customer->nama,
                'no_ktp'        => $customer->no_ktp,
                'no_hp'         => $customer->no_hp,
                'alamat'        => $customer->alamat,
                'cabang_id'     => $customer->cabang_id,
                'cabang'        => $customer->branch?->nama,
                'status'        => $customer->status,
                'tgl_bergabung' => $customer->tgl_bergabung?->format('d M Y'),
            ],
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $user = $request->user();

        if ($user->role !== 'superadmin' && $customer->cabang_id !== $user->cabang_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $request->validate([
            'nama'          => 'required|string|max:100',
            'no_ktp'        => 'required|digits:16|unique:nasabah,no_ktp,' . $customer->id,
            'no_hp'         => 'required|string|max:20',
            'alamat'        => 'required|string',
            'status'        => 'required|in:aktif,nonaktif',
            'tgl_bergabung' => 'required|date',
        ]);

        $customer->update($request->only([
            'nama', 'no_ktp', 'no_hp', 'alamat', 'status', 'tgl_bergabung'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Data nasabah berhasil diperbarui.',
            'data'    => [
                'id'            => $customer->id,
                'no_cif'        => $customer->no_cif,
                'nama'          => $customer->nama,
                'no_ktp'        => $customer->no_ktp,
                'no_hp'         => $customer->no_hp,
                'alamat'        => $customer->alamat,
                'status'        => $customer->status,
                'tgl_bergabung' => $customer->tgl_bergabung?->format('d M Y'),
            ],
        ]);
    }

    public function destroy(Request $request, Customer $customer)
    {
        $user = $request->user();

        if ($user->role !== 'superadmin' && $customer->cabang_id !== $user->cabang_id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data nasabah berhasil dihapus.',
        ]);
    }
}
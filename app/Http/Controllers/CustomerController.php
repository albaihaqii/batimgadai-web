<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $role  = Auth::user()->role;
        $query = Customer::with('branch');

        if ($role !== 'superadmin') {
            $query->where('cabang_id', Auth::user()->cabang_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                ->orWhere('no_ktp', 'like', "%{$search}%")
                ->orWhere('no_hp', 'like', "%{$search}%")
                ->orWhere('no_cif', 'like', "%{$search}%");
            });
        }

        // Export PDF
        if ($request->has('export')) {
            $customers = $query->latest()->get();
            $pdf = Pdf::loadView('exports.customers', compact('customers'))
                ->setPaper('a4', 'landscape');
            return $pdf->download('data-nasabah-' . now()->format('Ymd') . '.pdf');
        }

        $perPage   = $request->get('per_page', 10);
        $customers = $query->latest()->paginate($perPage)->withQueryString();
        $branches  = Branch::where('status', 'aktif')->get();

        return view("{$role}.customers.index", compact('customers', 'branches'));
    }

    public function create()
    {
        $role     = Auth::user()->role;
        $branches = Branch::where('status', 'aktif')->get();
        return view("{$role}.customers.create", compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'no_ktp'    => 'required|digits:16|unique:nasabah,no_ktp',
            'no_hp'     => 'required|string|max:20',
            'alamat'    => 'required|string',
            'cabang_id' => 'required|exists:cabang,id',
            'status'    => 'required|in:aktif,nonaktif',
        ], [
            'no_ktp.digits'    => 'No KTP harus 16 digit.',
            'no_ktp.unique'    => 'No KTP sudah terdaftar.',
            'cabang_id.exists' => 'Cabang tidak valid.',
        ]);

        $cabang = Branch::find($request->cabang_id);
        $last   = Customer::where('no_cif', 'like', "CIF-{$cabang->kode}-%")->count();
        $noCif  = "CIF-{$cabang->kode}-" . str_pad($last + 1, 6, '0', STR_PAD_LEFT);

        Customer::create([
            'no_cif'        => $noCif,
            'nama'          => $request->nama,
            'no_ktp'        => $request->no_ktp,
            'no_hp'         => $request->no_hp,
            'alamat'        => $request->alamat,
            'cabang_id'     => $request->cabang_id,
            'status'        => $request->status,
            'tgl_bergabung' => now()->toDateString(),
            'created_by'    => Auth::id(),
        ]);

        return redirect()
            ->route(Auth::user()->role . '.nasabah')
            ->with('success', 'Data nasabah berhasil ditambahkan.');
    }

    public function edit(Customer $customer)
    {
        $role     = Auth::user()->role;
        $branches = Branch::where('status', 'aktif')->get();
        return view("{$role}.customers.edit", compact('customer', 'branches'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'nama'      => 'required|string|max:100',
            'no_ktp'    => 'required|digits:16|unique:nasabah,no_ktp,' . $customer->id,
            'no_hp'     => 'required|string|max:20',
            'alamat'    => 'required|string',
            'cabang_id' => 'required|exists:cabang,id',
            'status'    => 'required|in:aktif,nonaktif',
        ], [
            'no_ktp.digits' => 'No KTP harus 16 digit.',
            'no_ktp.unique' => 'No KTP sudah terdaftar.',
        ]);

        $customer->update($request->only([
            'nama', 'no_ktp', 'no_hp', 'alamat', 'cabang_id', 'status'
        ]));

        return redirect()
            ->route(Auth::user()->role . '.nasabah')
            ->with('success', 'Data nasabah berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()
            ->route(Auth::user()->role . '.nasabah')
            ->with('success', 'Data nasabah berhasil dihapus.');
    }
}
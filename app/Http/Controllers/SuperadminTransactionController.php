<?php

namespace App\Http\Controllers;

use App\Models\PawnTransaction;
use App\Models\ExtensionTransaction;
use App\Models\RedemptionTransaction;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;

class SuperadminTransactionController extends Controller
{
    public function index()
    {
        return view('superadmin.transactions.index');
    }

    public function pawn(Request $request)
    {
        $query = PawnTransaction::with(['customer', 'branch', 'officer', 'admin']);

        // Filters
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->date_from) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }
        if ($request->customer_name) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->customer_name . '%');
            });
        }

        $pawnTransactions = $query->paginate(10);

        return view('superadmin.transactions.pawn', compact('pawnTransactions'));
    }

    public function extension(Request $request)
    {
        $query = ExtensionTransaction::with(['pawnTransaction.customer', 'pawnTransaction.branch']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $extensionTransactions = $query->paginate(10);

        return view('superadmin.transactions.extension', compact('extensionTransactions'));
    }

    public function redemption(Request $request)
    {
        $query = RedemptionTransaction::with(['pawnTransaction.customer', 'pawnTransaction.branch']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $redemptionTransactions = $query->paginate(10);

        return view('superadmin.transactions.redemption', compact('redemptionTransactions'));
    }

    public function showPawn($id)
    {
        $transaction = PawnTransaction::with(['customer', 'branch', 'officer', 'admin'])->findOrFail($id);
        return view('superadmin.transactions.show_pawn', compact('transaction'));
    }

    public function createPawn()
    {
        $customers = Customer::all();
        $branches = Branch::all();
        $officers = User::where('role', 'officer')->get();

        return view('superadmin.transactions.create_pawn', compact('customers', 'branches', 'officers'))
            ->with('transaction', null);
    }

    public function storePawn(Request $request)
    {
        $validated = $request->validate([
            'no_sbg' => 'required|unique:pawn_transactions,no_sbg',
            'customer_id' => 'nullable|exists:nasabah,id',
            'branch_id' => 'required|exists:cabang,id',
            'officer_id' => 'required|exists:users,id',
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string',
            'item_category' => 'nullable|string|max:100',
            'item_condition' => 'nullable|string|max:100',
            'item_completeness' => 'nullable|string|max:255',
            'item_photos.*' => 'image|max:2048',
            'officer_appraisal_min' => 'required|numeric|min:0',
            'officer_appraisal_max' => 'required|numeric|min:0',
            'loan_amount' => 'required|numeric|min:0',
            'final_appraisal' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,approved,rejected,completed',
            'transaction_date' => 'required|date',
        ]);

        if (!$validated['customer_id']) {
            $customer = Customer::create([
                'no_cif' => $request->no_cif,
                'nama' => $request->customer_name,
                'no_hp' => $request->customer_phone,
                'alamat' => $request->customer_address,
                'cabang_id' => $validated['branch_id'],
                'status' => 'active',
                'tgl_bergabung' => now(),
                'created_by' => auth()->id(),
            ]);
            $validated['customer_id'] = $customer->id;
        }

        $itemData = [
            'name' => $validated['item_name'],
            'description' => $validated['item_description'] ?? null,
            'category' => $validated['item_category'] ?? null,
            'condition' => $validated['item_condition'] ?? null,
            'completeness' => $validated['item_completeness'] ?? null,
        ];

        $photoPaths = [];
        if ($request->hasFile('item_photos')) {
            foreach ($request->file('item_photos') as $photo) {
                $path = $photo->store('pawn_items', 'public');
                $photoPaths[] = $path;
            }
        }

        PawnTransaction::create([
            'no_sbg' => $validated['no_sbg'],
            'customer_id' => $validated['customer_id'],
            'item_data' => $itemData,
            'item_photos' => $photoPaths,
            'officer_appraisal_min' => $validated['officer_appraisal_min'],
            'officer_appraisal_max' => $validated['officer_appraisal_max'],
            'loan_amount' => $validated['loan_amount'],
            'final_appraisal' => $validated['final_appraisal'] ?? null,
            'status' => $validated['status'],
            'branch_id' => $validated['branch_id'],
            'officer_id' => $validated['officer_id'],
            'admin_id' => auth()->id(),
            'transaction_date' => $validated['transaction_date'],
            'approval_date' => $validated['status'] === 'approved' ? now() : null,
        ]);

        return redirect()->route('superadmin.transactions.pawn')->with('success', 'Transaksi gadai berhasil ditambahkan.');
    }

    public function editPawn($id)
    {
        $transaction = PawnTransaction::findOrFail($id);
        $customers = Customer::all();
        $branches = Branch::all();
        $officers = User::where('role', 'officer')->get();

        return view('superadmin.transactions.edit_pawn', compact('transaction', 'customers', 'branches', 'officers'));
    }

    public function updatePawn(Request $request, $id)
    {
        $transaction = PawnTransaction::findOrFail($id);

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:nasabah,id',
            'branch_id' => 'required|exists:cabang,id',
            'officer_id' => 'required|exists:users,id',
            'item_name' => 'required|string|max:255',
            'item_description' => 'nullable|string',
            'item_category' => 'nullable|string|max:100',
            'item_condition' => 'nullable|string|max:100',
            'item_completeness' => 'nullable|string|max:255',
            'item_photos.*' => 'image|max:2048',
            'officer_appraisal_min' => 'required|numeric|min:0',
            'officer_appraisal_max' => 'required|numeric|min:0',
            'loan_amount' => 'required|numeric|min:0',
            'final_appraisal' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,approved,rejected,completed',
            'transaction_date' => 'required|date',
        ]);

        if (!$validated['customer_id']) {
            return back()->withErrors(['customer_id' => 'Pilih nasabah yang valid atau tambahkan nasabah terlebih dahulu.']);
        }

        $itemData = [
            'name' => $validated['item_name'],
            'description' => $validated['item_description'] ?? null,
            'category' => $validated['item_category'] ?? null,
            'condition' => $validated['item_condition'] ?? null,
            'completeness' => $validated['item_completeness'] ?? null,
        ];

        $photoPaths = $transaction->item_photos ?? [];
        if ($request->hasFile('item_photos')) {
            foreach ($request->file('item_photos') as $photo) {
                $path = $photo->store('pawn_items', 'public');
                $photoPaths[] = $path;
            }
        }

        $transaction->update([
            'customer_id' => $validated['customer_id'],
            'item_data' => $itemData,
            'item_photos' => $photoPaths,
            'officer_appraisal_min' => $validated['officer_appraisal_min'],
            'officer_appraisal_max' => $validated['officer_appraisal_max'],
            'loan_amount' => $validated['loan_amount'],
            'final_appraisal' => $validated['final_appraisal'] ?? null,
            'status' => $validated['status'],
            'branch_id' => $validated['branch_id'],
            'officer_id' => $validated['officer_id'],
            'transaction_date' => $validated['transaction_date'],
            'approval_date' => $validated['status'] === 'approved' ? now() : $transaction->approval_date,
        ]);

        return redirect()->route('superadmin.transactions.pawn')->with('success', 'Transaksi gadai berhasil diperbarui.');
    }

    public function destroyPawn($id)
    {
        $transaction = PawnTransaction::findOrFail($id);

        $hasExtension = ExtensionTransaction::where('pawn_transaction_id', $transaction->id)->exists();
        $hasRedemption = RedemptionTransaction::where('pawn_transaction_id', $transaction->id)->exists();

        if ($hasExtension || $hasRedemption) {
            return redirect()->route('superadmin.transactions.pawn')->with('error', 'Transaksi tidak bisa dihapus karena sudah memiliki histori perpanjangan atau pelunasan.');
        }

        $transaction->delete();

        return redirect()->route('superadmin.transactions.pawn')->with('success', 'Transaksi gadai berhasil dihapus.');
    }

    public function updatePawnStatus(Request $request, $id)
    {
        $transaction = PawnTransaction::findOrFail($id);
        $transaction->update([
            'status' => $request->status,
            'final_appraisal' => $request->final_appraisal ?? $transaction->final_appraisal,
            'approval_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui');
    }
}

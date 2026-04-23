<?php

namespace App\Http\Controllers;

use App\Models\PawnTransaction;
use App\Models\ExtensionTransaction;
use App\Models\RedemptionTransaction;
use App\Models\Customer;
use App\Models\Branch;
use App\Models\User;
use App\Models\Locker;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SuperadminTransactionController extends Controller
{
    public function generateSbgNumber($branchId, $transactionDate = null)
    {
        $date = $transactionDate ? \Carbon\Carbon::parse($transactionDate) : now();
        $dateStr = $date->format('ymd'); // YYMMDD format

        $branch = \App\Models\Branch::find($branchId);
        $branchCode = strtoupper(substr($branch->kode, 0, 3)); // Take first 3 chars of branch code

        // Get the last SBG number for this branch and date
        $lastTransaction = \App\Models\PawnTransaction::where('branch_id', $branchId)
            ->where('no_sbg', 'like', $dateStr . $branchCode . '%')
            ->orderBy('no_sbg', 'desc')
            ->first();

        if ($lastTransaction) {
            // Extract the sequence number and increment
            $lastSequence = (int) substr($lastTransaction->no_sbg, -6); // Last 6 digits
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return $dateStr . $branchCode . str_pad($newSequence, 6, '0', STR_PAD_LEFT);
    }
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

    public function downloadSbg($id)
    {
        $transaction = PawnTransaction::with(['customer', 'branch', 'officer', 'admin', 'locker'])->findOrFail($id);

        $logoPath = public_path('images/logo/home.png');
        $hasLogo = file_exists($logoPath);

        $pdf = Pdf::loadView('superadmin.transactions.sbg', compact('transaction', 'hasLogo', 'logoPath'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('sbg-' . ($transaction->no_sbg ?: $transaction->id) . '.pdf');
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

        $photoPaths = [];
        if ($request->hasFile('item_photos')) {
            foreach ($request->file('item_photos') as $photo) {
                $path = $photo->store('pawn_items', 'public');
                $photoPaths[] = $path;
            }
        }

        PawnTransaction::create([
            'no_sbg' => $this->generateSbgNumber($validated['branch_id'], $validated['transaction_date']),
            'customer_id' => $validated['customer_id'],
            'item_name' => $validated['item_name'],
            'item_description' => $validated['item_description'] ?? null,
            'item_category' => $validated['item_category'] ?? null,
            'item_condition' => $validated['item_condition'] ?? null,
            'item_completeness' => $validated['item_completeness'] ?? null,
            'item_photos' => $photoPaths,
            'officer_appraisal_min' => $validated['officer_appraisal_min'],
            'officer_appraisal_max' => $validated['officer_appraisal_max'],
            'loan_amount' => $validated['loan_amount'],
            'final_appraisal' => $validated['final_appraisal'] ?? null,
            'status' => 'pending', // Always start as pending
            'branch_id' => $validated['branch_id'],
            'officer_id' => $validated['officer_id'],
            'admin_id' => auth()->id(),
            'transaction_date' => $validated['transaction_date'],
            'approval_date' => null,
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

        $photoPaths = $transaction->item_photos ?? [];
        if ($request->hasFile('item_photos')) {
            foreach ($request->file('item_photos') as $photo) {
                $path = $photo->store('pawn_items', 'public');
                $photoPaths[] = $path;
            }
        }

        $transaction->update([
            'customer_id' => $validated['customer_id'],
            'item_name' => $validated['item_name'],
            'item_description' => $validated['item_description'] ?? null,
            'item_category' => $validated['item_category'] ?? null,
            'item_condition' => $validated['item_condition'] ?? null,
            'item_completeness' => $validated['item_completeness'] ?? null,
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

    // Approval Methods
    public function approval()
    {
        $pendingTransactions = PawnTransaction::with(['customer', 'branch', 'officer'])
            ->where('status', 'pending')
            ->paginate(10);

        return view('superadmin.transactions.approval', compact('pendingTransactions'));
    }

    public function showApproval($id)
    {
        $transaction = PawnTransaction::with(['customer', 'branch', 'officer'])->findOrFail($id);

        if ($transaction->status !== 'pending') {
            return redirect()->route('superadmin.transactions.approval')->with('error', 'Transaksi ini sudah diproses.');
        }

        return view('superadmin.transactions.show_approval', compact('transaction'));
    }

    public function approve(Request $request, $id)
    {
        $transaction = PawnTransaction::findOrFail($id);

        if ($transaction->status !== 'pending') {
            return redirect()->route('superadmin.transactions.approval')->with('error', 'Transaksi ini sudah diproses.');
        }

        $request->validate([
            'loan_amount' => [
                'required',
                'numeric',
                'min:0',
                'gte:' . $transaction->officer_appraisal_min,
                'lte:' . $transaction->officer_appraisal_max,
            ],
        ]);

        // Find available locker
        $availableLocker = Locker::where('status', 'kosong')
            ->where('cabang_id', $transaction->branch_id)
            ->first();

        if (!$availableLocker) {
            return redirect()->route('superadmin.transactions.approval')->with('error', 'Tidak ada loker kosong di cabang ini.');
        }

        // Generate SBG number
        $sbgNumber = $this->generateSbgNumber($transaction->branch_id, $transaction->transaction_date);

        $transaction->update([
            'status' => 'approved',
            'no_sbg' => $sbgNumber,
            'locker_id' => $availableLocker->id,
            'loan_amount' => $request->loan_amount,
            'approval_date' => now(),
        ]);

        // Update locker status
        $availableLocker->update(['status' => 'terisi']);

        return redirect()->route('superadmin.transactions.approval')->with('success', 'Pengajuan gadai berhasil disetujui. SBG: ' . $sbgNumber);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $transaction = PawnTransaction::findOrFail($id);

        if ($transaction->status !== 'pending') {
            return redirect()->route('superadmin.transactions.approval')->with('error', 'Transaksi ini sudah diproses.');
        }

        $transaction->update([
            'status' => 'rejected',
            'no_sbg' => null,
            'approval_date' => now(),
        ]);

        return redirect()->route('superadmin.transactions.approval')->with('success', 'Pengajuan gadai berhasil ditolak.');
    }
}

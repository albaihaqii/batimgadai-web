<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Gadai;
use App\Models\Perpanjangan;
use App\Models\Pelunasan;
use App\Models\ApprovalGadai;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.dashboard');
    }

    public function superadmin()
    {
        $goldRateUsd = null;
        $goldRateIdr = null;
        $usdIdrRate = null;
        $goldError = null;

        $apiKey = config('services.gold_api.key');

        if ($apiKey) {
            try {
                $goldResponse = Http::timeout(10)
                    ->withHeaders(['x-access-token' => $apiKey])
                    ->get('https://api.gold-api.com/price/XAU/USD');

                if ($goldResponse->successful()) {
                    $goldPayload = $goldResponse->json();
                    $goldRateUsd = data_get($goldPayload, 'price') ?? data_get($goldPayload, 'amount') ?? data_get($goldPayload, 'ask') ?? data_get($goldPayload, 'bid');

                    if (! is_numeric($goldRateUsd)) {
                        $goldRateUsd = null;
                        $goldError = 'Respons API emas tidak valid.';
                    }
                } else {
                    $goldError = 'Gagal mengambil harga emas dari API.';
                }
            } catch (\Throwable $error) {
                $goldError = 'Gagal terhubung ke API emas.';
                report($error);
            }
        } else {
            $goldError = 'GOLD_API_KEY belum diset di .env.';
        }

        if ($goldRateUsd) {
            try {
                $rateResponse = Http::timeout(10)
                    ->get('https://open.er-api.com/v6/latest/USD');

                if ($rateResponse->successful()) {
                    $usdIdrRate = data_get($rateResponse->json(), 'rates.IDR');
                    if (is_numeric($usdIdrRate)) {
                        $goldRateIdr = $goldRateUsd * $usdIdrRate;
                    } else {
                        $goldError = $goldError ?: 'Gagal mengonversi USD ke IDR.';
                    }
                } else {
                    $goldError = $goldError ?: 'Gagal mengambil kurs USD/IDR.';
                }
            } catch (\Throwable $error) {
                $goldError = $goldError ?: 'Gagal mengambil kurs USD/IDR.';
                report($error);
            }
        }

        $currentYear = now()->year;
        $currentMonth = now()->month;

        $previousMonth = $currentMonth - 1;
        $previousYear = $currentYear;
        if ($previousMonth === 0) {
            $previousMonth = 12;
            $previousYear -= 1;
        }

        $totalNasabah = Customer::count();
        $newNasabahThisMonth = Customer::whereYear('tgl_bergabung', $currentYear)
            ->whereMonth('tgl_bergabung', $currentMonth)
            ->count();
        $newNasabahPreviousMonth = Customer::whereYear('tgl_bergabung', $previousYear)
            ->whereMonth('tgl_bergabung', $previousMonth)
            ->count();

        $activeStatuses = ['aktif', 'active', 'disetujui', 'approved', 'diproses'];
        $pendingStatuses = ['menunggu', 'pending', 'waiting', 'waiting approval'];

        $activeTransactions = Gadai::whereIn(DB::raw('LOWER(status)'), $activeStatuses)->count();
        $activeTransactionsThisMonth = Gadai::whereIn(DB::raw('LOWER(status)'), $activeStatuses)
            ->whereYear('tgl_gadai', $currentYear)
            ->whereMonth('tgl_gadai', $currentMonth)
            ->count();
        $activeTransactionsPreviousMonth = Gadai::whereIn(DB::raw('LOWER(status)'), $activeStatuses)
            ->whereYear('tgl_gadai', $previousYear)
            ->whereMonth('tgl_gadai', $previousMonth)
            ->count();

        $pendingApprovalCount = max(
            ApprovalGadai::whereIn(DB::raw('LOWER(status)'), $pendingStatuses)->count(),
            Gadai::whereIn(DB::raw('LOWER(status)'), $pendingStatuses)->count()
        );
        $pendingApprovalThisMonth = Gadai::whereIn(DB::raw('LOWER(status)'), $pendingStatuses)
            ->whereYear('tgl_gadai', $currentYear)
            ->whereMonth('tgl_gadai', $currentMonth)
            ->count();
        $pendingApprovalPreviousMonth = Gadai::whereIn(DB::raw('LOWER(status)'), $pendingStatuses)
            ->whereYear('tgl_gadai', $previousYear)
            ->whereMonth('tgl_gadai', $previousMonth)
            ->count();

        $totalPinjamanBulanIni = Gadai::whereYear('tgl_gadai', $currentYear)
            ->whereMonth('tgl_gadai', $currentMonth)
            ->sum('nilai_pinjaman_awal');
        $totalPinjamanPreviousMonth = Gadai::whereYear('tgl_gadai', $previousYear)
            ->whereMonth('tgl_gadai', $previousMonth)
            ->sum('nilai_pinjaman_awal');

        $calculatePercent = function ($current, $previous) {
            if ($previous <= 0) {
                return $current <= 0 ? 0.0 : 100.0;
            }
            return round((($current - $previous) / $previous) * 100, 1);
        };

        $nasabahGrowth = $calculatePercent($newNasabahThisMonth, $newNasabahPreviousMonth);
        $activeTransactionsGrowth = $calculatePercent($activeTransactionsThisMonth, $activeTransactionsPreviousMonth);
        $totalPinjamanGrowth = $calculatePercent($totalPinjamanBulanIni, $totalPinjamanPreviousMonth);
        $pendingApprovalGrowth = $calculatePercent($pendingApprovalThisMonth, $pendingApprovalPreviousMonth);

        $branchLabels = Branch::orderBy('nama')->pluck('nama')->toArray();
        $branchTransactionTotals = Gadai::selectRaw('cabang_id, count(*) as total')
            ->groupBy('cabang_id')
            ->pluck('total', 'cabang_id')
            ->toArray();

        $branchSeries = Branch::orderBy('nama')
            ->get()
            ->map(fn($branch) => $branchTransactionTotals[$branch->id] ?? 0)
            ->toArray();

        $gadaiMonthly = Gadai::selectRaw('MONTH(tgl_gadai) as month, count(*) as total')
            ->whereYear('tgl_gadai', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $perpanjanganMonthly = Perpanjangan::selectRaw('MONTH(tgl_perpanjangan) as month, count(*) as total')
            ->whereYear('tgl_perpanjangan', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $pelunasanMonthly = Pelunasan::selectRaw('MONTH(tgl_pelunasan) as month, count(*) as total')
            ->whereYear('tgl_pelunasan', $currentYear)
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $transactionsPerMonth = array_map(
            fn($month) => [
                'gadai' => $gadaiMonthly[$month] ?? 0,
                'perpanjangan' => $perpanjanganMonthly[$month] ?? 0,
                'pelunasan' => $pelunasanMonthly[$month] ?? 0,
            ],
            range(1, 12)
        );

        $pendingPengajuan = Gadai::with(['nasabah', 'barang', 'branch'])
            ->whereIn(DB::raw('LOWER(status)'), $pendingStatuses)
            ->orderBy('tgl_gadai', 'desc')
            ->limit(5)
            ->get();

        $pengajuanTerbaru = $pendingPengajuan->map(fn($item) => [
            'no_sbg' => $item->no_sbg,
            'nasabah' => $item->nasabah?->nama ?? '-',
            'barang' => $item->barang?->nama_barang ?? '-',
            'cabang' => $item->branch?->nama ?? '-',
            'taksiran' => 'Rp ' . number_format($item->nilai_taksiran_akhir ?? $item->nilai_taksiran_max ?? $item->nilai_taksiran_min ?? 0, 0, ',', '.'),
            'status' => $item->status ?? 'Menunggu',
        ])->toArray();

        $monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        return view('superadmin.dashboard', compact(
            'goldRateUsd',
            'goldRateIdr',
            'usdIdrRate',
            'goldError',
            'totalNasabah',
            'activeTransactions',
            'totalPinjamanBulanIni',
            'pendingApprovalCount',
            'branchLabels',
            'branchSeries',
            'transactionsPerMonth',
            'monthLabels',
            'pengajuanTerbaru',
            'nasabahGrowth',
            'activeTransactionsGrowth',
            'totalPinjamanGrowth',
            'pendingApprovalGrowth'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Exports\TransactionReportExport;
use App\Models\Gadai;
use App\Models\Pelunasan;
use App\Models\Perpanjangan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;


class ReportController extends Controller
{
    public function daily(Request $request)
    {
        $date        = $request->get('date', now()->format('Y-m-d'));
        $from        = Carbon::parse($date)->startOfDay();
        $to          = Carbon::parse($date)->endOfDay();
        $periodLabel = $from->format('d M Y');

        [$records, $summary] = $this->getReportData($from, $to);

        return view('superadmin.laporan.index', [
            'type'        => 'harian',
            'date'        => $date,
            'routePrefix' => $request->segment(1),
            'records'     => $records,
            'summary'     => $summary,
            'periodLabel' => $periodLabel,
        ]);
    }

    public function weekly(Request $request)
    {
        $weekStart   = $request->get('week_start', Carbon::now()->startOfWeek()->format('Y-m-d'));
        $from        = Carbon::parse($weekStart)->startOfWeek();
        $to          = (clone $from)->endOfWeek();
        $periodLabel = $from->format('d M Y') . ' – ' . $to->format('d M Y');

        [$records, $summary] = $this->getReportData($from, $to);

        return view('superadmin.laporan.index', [
            'type'        => 'mingguan',
            'weekStart'   => $weekStart,
            'routePrefix' => $request->segment(1),
            'records'     => $records,
            'summary'     => $summary,
            'periodLabel' => $periodLabel,
        ]);
    }

    public function monthly(Request $request)
    {
        $month       = $request->get('month', now()->format('Y-m'));
        $from        = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $to          = (clone $from)->endOfMonth();
        $periodLabel = $from->format('F Y');

        [$records, $summary] = $this->getReportData($from, $to);

        return view('superadmin.laporan.index', [
            'type'        => 'bulanan',
            'month'       => $month,
            'routePrefix' => $request->segment(1),
            'records'     => $records,
            'summary'     => $summary,
            'periodLabel' => $periodLabel,
        ]);
    }

    public function exportDaily(Request $request)
    {
        $validated = $request->validate(['date' => 'required|date']);

        $from = Carbon::parse($validated['date'])->startOfDay();
        $to   = Carbon::parse($validated['date'])->endOfDay();

        return $this->downloadReport('Harian', $from, $to, $from->format('d M Y'));
    }

    public function exportWeekly(Request $request)
    {
        $validated = $request->validate(['week_start' => 'required|date']);

        $from = Carbon::parse($validated['week_start'])->startOfWeek();
        $to   = (clone $from)->endOfWeek();

        return $this->downloadReport(
            'Mingguan',
            $from,
            $to,
            $from->format('d M Y') . ' – ' . $to->format('d M Y')
        );
    }

    public function exportMonthly(Request $request)
    {
        $validated = $request->validate(['month' => 'required|date_format:Y-m']);

        $from = Carbon::createFromFormat('Y-m', $validated['month'])->startOfMonth();
        $to   = (clone $from)->endOfMonth();

        return $this->downloadReport('Bulanan', $from, $to, $from->format('F Y'));
    }

    /**
     * Ambil records dan summary untuk rentang waktu tertentu.
     *
     * Strategi filter dua kolom:
     * 1. Gadai SUDAH DISETUJUI  → pakai tgl_gadai (tanggal resmi transaksi cair)
     * 2. Gadai PENDING / DITOLAK → pakai created_at (tgl_gadai masih NULL)
     *
     * Dengan pendekatan ini, laporan April menampilkan gadai aktif yang cair April,
     * dan laporan Mei menampilkan pengajuan baru yang masuk Mei.
     */

    // protected function getReportData(Carbon $from, Carbon $to): array
    // {
    //     $fromDate = $from->copy()->startOfDay()->toDateString();
    //     $toDate   = $to->copy()->endOfDay()->toDateString();

    //     /*
    // |--------------------------------------------------------------------------
    // | GADAI BARU (UANG KELUAR)
    // |--------------------------------------------------------------------------
    // */

    //     $gadai = Gadai::with(['nasabah', 'branch', 'barang'])
    //         ->whereIn('status', ['disetujui', 'aktif'])
    //         ->whereBetween('tgl_gadai', [$fromDate, $toDate])
    //         ->get()
    //         ->map(function ($item) {

    //             return [
    //                 'tanggal' => $item->tgl_gadai,
    //                 'jenis'   => 'gadai',
    //                 'status'  => $item->status,

    //                 'no_sbg'  => $item->no_sbg,

    //                 'nasabah' => $item->nasabah?->nama,
    //                 'cabang'  => $item->branch?->nama,

    //                 'barang'  => $item->barang?->nama_barang,

    //                 'taksiran' => (float) (
    //                     $item->nilai_taksiran_akhir
    //                     ?? $item->nilai_taksiran_max
    //                     ?? $item->nilai_taksiran_min
    //                     ?? 0
    //                 ),

    //                 'cash_out' => (float) $item->nilai_pinjaman,
    //                 'cash_in'  => 0,

    //                 'cash_flow' => - ((float) $item->nilai_pinjaman),

    //                 'keterangan' => 'Pencairan gadai',
    //             ];
    //         });

    //     /*
    // |--------------------------------------------------------------------------
    // | PELUNASAN (UANG MASUK)
    // |--------------------------------------------------------------------------
    // */

    //     $pelunasan = Pelunasan::with(['gadai.nasabah', 'gadai.branch', 'gadai.barang'])
    //         ->where('status_bayar', 'berhasil')
    //         ->whereBetween('tgl_pelunasan', [$fromDate, $toDate])
    //         ->get()
    //         ->map(function ($item) {

    //             return [
    //                 'tanggal' => $item->tgl_pelunasan,
    //                 'jenis'   => 'pelunasan',
    //                 'status'  => 'lunas',

    //                 'no_sbg'  => $item->no_sbg,

    //                 'nasabah' => $item->gadai?->nasabah?->nama,
    //                 'cabang'  => $item->gadai?->branch?->nama,

    //                 'barang'  => $item->gadai?->barang?->nama_barang,

    //                 'taksiran' => 0,

    //                 'cash_out' => 0,
    //                 'cash_in'  => (float) $item->total_tebus,

    //                 'cash_flow' => (float) $item->total_tebus,

    //                 'keterangan' => 'Pelunasan gadai',
    //             ];
    //         });

    //     /*
    // |--------------------------------------------------------------------------
    // | PERPANJANGAN (UANG MASUK JASA)
    // |--------------------------------------------------------------------------
    // */

    //     $perpanjangan = Perpanjangan::with(['gadai.nasabah', 'gadai.branch', 'gadai.barang'])
    //         ->where('status_bayar', 'berhasil')
    //         ->whereBetween('tgl_perpanjangan', [$fromDate, $toDate])
    //         ->get()
    //         ->map(function ($item) {

    //             return [
    //                 'tanggal' => $item->tgl_perpanjangan,
    //                 'jenis'   => 'perpanjangan',
    //                 'status'  => 'perpanjangan',

    //                 'no_sbg'  => $item->no_sbg,

    //                 'nasabah' => $item->gadai?->nasabah?->nama,
    //                 'cabang'  => $item->gadai?->branch?->nama,

    //                 'barang'  => $item->gadai?->barang?->nama_barang,

    //                 'taksiran' => 0,

    //                 'cash_out' => 0,

    //                 // hanya jasa+denda masuk
    //                 'cash_in' => (
    //                     (float) $item->jasa_nominal +
    //                     (float) $item->denda_nominal
    //                 ),

    //                 'cash_flow' => (
    //                     (float) $item->jasa_nominal +
    //                     (float) $item->denda_nominal
    //                 ),

    //                 'keterangan' => 'Perpanjangan gadai',
    //             ];
    //         });

    //     /*
    // |--------------------------------------------------------------------------
    // | MERGE SEMUA TRANSAKSI
    // |--------------------------------------------------------------------------
    // */

    //     $records = collect()
    //         ->merge($gadai)
    //         ->merge($pelunasan)
    //         ->merge($perpanjangan)
    //         ->sortByDesc('tanggal')
    //         ->values();

    //     /*
    // |--------------------------------------------------------------------------
    // | SUMMARY
    // |--------------------------------------------------------------------------
    // */

    //     $totalCashOut = $records->sum('cash_out');

    //     $totalCashIn = $records->sum('cash_in');

    //     $summary = [

    //         'total_up' => $gadai->sum('cash_out'),

    //         'cash_out' => $totalCashOut,

    //         'cash_in' => $totalCashIn,

    //         'net_cash_flow' => $totalCashIn - $totalCashOut,

    //         'total_sewa_modal' => $perpanjangan->sum('cash_in'),

    //         'total_admin' => 0,

    //         'active_customers' => $gadai
    //             ->pluck('nasabah')
    //             ->unique()
    //             ->count(),
    //     ];

    //     return [$records, $summary];
    // }

    protected function getReportData(Carbon $from, Carbon $to): array
    {
        $fromDate = $from->copy()->startOfDay()->toDateString();
        $toDate   = $to->copy()->endOfDay()->toDateString();
        $user     = Auth::user();

        $isBranchScoped = $user && $user->role !== 'superadmin';
        $branchId       = $isBranchScoped ? $user->cabang_id : null;

        /*
    |--------------------------------------------------------------------------
    | DATA GADAI BARU (UANG KELUAR)
    |--------------------------------------------------------------------------
    | Hanya gadai yang sudah disetujui / aktif
    | karena itu berarti uang sudah dicairkan
    |--------------------------------------------------------------------------
    */
        $gadaiBaru = Gadai::with(['nasabah', 'branch', 'barang'])
            ->whereIn('status', ['disetujui', 'aktif'])
            ->whereBetween('tgl_gadai', [$fromDate, $toDate])
            ->when($isBranchScoped, fn($query) => $query->where('cabang_id', $branchId))
            ->get()
            ->map(function ($gadai) {

                $taksiran = (float) (
                    $gadai->nilai_taksiran_akhir
                    ?? $gadai->nilai_taksiran_max
                    ?? $gadai->nilai_taksiran_min
                    ?? 0
                );

                return (object) [
                    'jenis_transaksi' => 'gadai_baru',
                    'tanggal'         => $gadai->tgl_gadai,
                    'status'          => 'aktif',

                    'no_sbg'          => $gadai->no_sbg,
                    'nasabah'         => $gadai->nasabah,
                    'branch'          => $gadai->branch,
                    'barang'          => $gadai->barang,

                    'taksiran'        => $taksiran,

                    // perusahaan keluar uang
                    'cash_out'        => (float) $gadai->nilai_pinjaman,
                    'cash_in'         => 0,

                    'row_cash_flow'   => -(float) $gadai->nilai_pinjaman,
                ];
            });

        /*
    |--------------------------------------------------------------------------
    | DATA PELUNASAN (UANG MASUK)
    |--------------------------------------------------------------------------
    */
        $pelunasan = Pelunasan::with([
            'gadai.nasabah',
            'gadai.branch',
            'gadai.barang'
        ])
            ->where('status_bayar', 'berhasil')
            ->whereBetween('tgl_pelunasan', [$fromDate, $toDate])
            ->when(
                $isBranchScoped,
                fn($query) => $query->whereHas('gadai', fn($gadaiQuery) => $gadaiQuery->where('cabang_id', $branchId))
            )
            ->get()
            ->map(function ($pelunasan) {

                $gadai = $pelunasan->gadai;

                $taksiran = (float) (
                    $gadai->nilai_taksiran_akhir
                    ?? $gadai->nilai_taksiran_max
                    ?? $gadai->nilai_taksiran_min
                    ?? 0
                );

                return (object) [
                    'jenis_transaksi' => 'pelunasan',
                    'tanggal'         => $pelunasan->tgl_pelunasan,
                    'status'          => 'lunas',

                    'no_sbg'          => $gadai->no_sbg,
                    'nasabah'         => $gadai->nasabah,
                    'branch'          => $gadai->branch,
                    'barang'          => $gadai->barang,

                    'taksiran'        => $taksiran,
                    'nilai_pinjaman'  => (float) ($gadai->nilai_pinjaman ?? 0),

                    'cash_out'        => 0,

                    // uang masuk penuh
                    'cash_in'         => (float) $pelunasan->total_tebus,

                    'row_cash_flow'   => (float) $pelunasan->total_tebus,
                ];
            });

        /*
    |--------------------------------------------------------------------------
    | DATA PERPANJANGAN
    |--------------------------------------------------------------------------
    | Perpanjangan TIDAK mengeluarkan UP baru
    | Yang masuk hanya jasa + denda
    |--------------------------------------------------------------------------
    */
        $perpanjangan = \App\Models\Perpanjangan::with([
            'gadai.nasabah',
            'gadai.branch',
            'gadai.barang'
        ])
            ->where('status_bayar', 'berhasil')
            ->whereBetween('tgl_perpanjangan', [$fromDate, $toDate])
            ->when(
                $isBranchScoped,
                fn($query) => $query->whereHas('gadai', fn($gadaiQuery) => $gadaiQuery->where('cabang_id', $branchId))
            )
            ->get()
            ->map(function ($perpanjangan) {

                $gadai = $perpanjangan->gadai;

                $taksiran = (float) (
                    $gadai->nilai_taksiran_akhir
                    ?? $gadai->nilai_taksiran_max
                    ?? $gadai->nilai_taksiran_min
                    ?? 0
                );

                $pendapatan = (float) (
                    $perpanjangan->jasa_nominal +
                    $perpanjangan->denda_nominal
                );

                return (object) [
                    'jenis_transaksi' => 'perpanjangan',
                    'tanggal'         => $perpanjangan->tgl_perpanjangan,
                    'status'          => 'perpanjangan',

                    'no_sbg'          => $gadai->no_sbg,
                    'nasabah'         => $gadai->nasabah,
                    'branch'          => $gadai->branch,
                    'barang'          => $gadai->barang,

                    'taksiran'        => $taksiran,

                    'cash_out'        => 0,

                    // hanya jasa+denda yg dianggap pemasukan
                    'cash_in'         => $pendapatan,

                    'row_cash_flow'   => $pendapatan,
                ];
            });

        /*
    |--------------------------------------------------------------------------
    | GABUNGKAN SEMUA TRANSAKSI
    |--------------------------------------------------------------------------
    */
        $records = collect()
            ->merge($gadaiBaru)
            ->merge($pelunasan)
            ->merge($perpanjangan)
            ->sortByDesc('tanggal')
            ->values();

        /*
    |--------------------------------------------------------------------------
    | SUMMARY
    |--------------------------------------------------------------------------
    */

        // uang keluar = pencairan gadai baru
        $cashOut = $gadaiBaru->sum('cash_out');

        // uang masuk = pelunasan + jasa perpanjangan
        $cashIn =
            $pelunasan->sum('cash_in') +
            $perpanjangan->sum('cash_in');

        // pendapatan jasa
        $totalSewaModal =
            $pelunasan->sum(function ($item) {
                return (float) (
                    $item->cash_in -
                    ($item->nilai_pinjaman ?? 0)
                );
            }) +
            $perpanjangan->sum('cash_in');

        $summary = [
            'total_up'         => $cashOut,

            'cash_out'         => $cashOut,

            'cash_in'          => $cashIn,

            'net_cash_flow'    => $cashIn - $cashOut,

            // laba jasa
            'total_sewa_modal' => $totalSewaModal,

            'total_admin'      => 0,

            'active_customers' => $gadaiBaru
                ->pluck('nasabah.id')
                ->filter()
                ->unique()
                ->count(),
        ];

        return [$records, $summary];
    }

    protected function downloadReport(string $type, Carbon $from, Carbon $to, string $periodLabel)
    {
        [$records, $summary] = $this->getReportData($from, $to);

        $filename = sprintf(
            'laporan-transaksi-%s-%s.xlsx',
            Str::slug(strtolower($type)),
            $from->format('Ymd')
        );

        return Excel::download(
            new TransactionReportExport($type, $periodLabel, $summary, $records),
            $filename
        );
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Gadai;
use App\Models\Perpanjangan;
use App\Models\Pelunasan;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ReportController extends Controller
{
    private function cabangId(): ?int
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->role === 'superadmin' ? null : (int) $user->cabang_id;
    }

    private function buildRecords(Carbon $start, Carbon $end, ?int $cabangId, ?string $jenis): Collection
    {
        $gadaiBaru = Gadai::with(['nasabah', 'barang', 'branch'])
            ->whereNotIn('status', ['menunggu_approval', 'ditolak'])
            ->whereNotNull('tgl_gadai')
            ->whereDate('tgl_gadai', '>=', $start)
            ->whereDate('tgl_gadai', '<=', $end)
            ->when($cabangId, fn($q) => $q->where('cabang_id', $cabangId))
            ->get()
            ->map(fn($g) => [
                'tanggal'           => $g->tgl_gadai,
                'no_sbg'            => $g->no_sbg ?? '-',
                'nasabah'           => $g->nasabah->nama ?? '-',
                'cabang'            => $g->branch->nama ?? '-',
                'barang'            => $g->barang->nama_barang ?? '-',
                'nilai_pinjaman'    => (float) ($g->nilai_pinjaman ?? 0),
                'nominal_transaksi' => (float) ($g->nilai_pinjaman ?? 0),
                'jenis'             => 'gadai_baru',
                'status'            => $g->status,
            ]);

        $perpanjangan = Perpanjangan::with(['gadai.nasabah', 'gadai.barang', 'gadai.branch'])
            ->whereDate('tgl_perpanjangan', '>=', $start)
            ->whereDate('tgl_perpanjangan', '<=', $end)
            ->when($cabangId, fn($q) => $q->whereHas('gadai', fn($g) => $g->where('cabang_id', $cabangId)))
            ->get()
            ->map(fn($p) => [
                'tanggal'           => $p->tgl_perpanjangan,
                'no_sbg'            => $p->no_sbg ?? '-',
                'nasabah'           => $p->gadai->nasabah->nama ?? '-',
                'cabang'            => $p->gadai->branch->nama ?? '-',
                'barang'            => $p->gadai->barang->nama_barang ?? '-',
                'nilai_pinjaman'    => (float) ($p->nilai_pinjaman ?? 0),
                'nominal_transaksi' => (float) ($p->jasa_nominal ?? 0),
                'jenis'             => 'perpanjangan',
                'status'            => $p->status_bayar ?? 'berhasil',
            ]);

        $pelunasan = Pelunasan::with(['gadai.nasabah', 'gadai.barang', 'gadai.branch'])
            ->whereDate('tgl_pelunasan', '>=', $start)
            ->whereDate('tgl_pelunasan', '<=', $end)
            ->when($cabangId, fn($q) => $q->whereHas('gadai', fn($g) => $g->where('cabang_id', $cabangId)))
            ->get()
            ->map(fn($p) => [
                'tanggal'           => $p->tgl_pelunasan,
                'no_sbg'            => $p->no_sbg ?? '-',
                'nasabah'           => $p->gadai->nasabah->nama ?? '-',
                'cabang'            => $p->gadai->branch->nama ?? '-',
                'barang'            => $p->gadai->barang->nama_barang ?? '-',
                'nilai_pinjaman'    => (float) ($p->nilai_pinjaman ?? 0),
                'nominal_transaksi' => (float) ($p->total_tebus ?? 0),
                'jenis'             => 'pelunasan',
                'status'            => $p->status_bayar ?? 'berhasil',
            ]);

        return collect([...$gadaiBaru, ...$perpanjangan, ...$pelunasan])
            ->when($jenis, fn($c) => $c->where('jenis', $jenis))
            ->sortBy('tanggal')
            ->values();
    }

    private function buildSummary(Collection $records): array
    {
        $totalPinjaman   = $records->where('jenis', 'gadai_baru')->sum('nilai_pinjaman');
        $pendapatanJasa  = $records->where('jenis', 'perpanjangan')->sum('nominal_transaksi')
                         + $records->where('jenis', 'pelunasan')->sum(fn($p) => max(0, $p['nominal_transaksi'] - $p['nilai_pinjaman']));
        $totalPelunasan  = $records->where('jenis', 'pelunasan')->sum('nilai_pinjaman');
        $jumlahTransaksi = $records->count();

        return compact('totalPinjaman', 'pendapatanJasa', 'totalPelunasan', 'jumlahTransaksi');
    }

    private function paginate(Collection $collection, int $perPage)
    {
        $page = (int) request()->get('page', 1);
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $collection->forPage($page, $perPage),
            $collection->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }

    private function viewData(string $type, Collection $allRecords, array $extra): array
    {
        $perPage  = (int) request()->get('per_page', 10);
        $summary  = $this->buildSummary($allRecords);
        $records  = $this->paginate($allRecords, $perPage);
        $branches = Branch::where('status', 'aktif')->orderBy('nama')->get();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        return array_merge(compact('type', 'summary', 'records', 'branches', 'perPage'), [
            'routePrefix'      => $user->role,
            'selectedCabangId' => request()->get('cabang_id') ?? $this->cabangId(),
            'jenis'            => request()->get('jenis'),
            'date'             => null,
            'weekStart'        => null,
            'month'            => null,
            'periodLabel'      => '',
        ], $extra);
    }

    public function harian(Request $request)
    {
        $date     = $request->get('date', today()->toDateString());
        $cabangId = $request->get('cabang_id') ? (int) $request->get('cabang_id') : $this->cabangId();
        $start    = Carbon::parse($date)->startOfDay();
        $end      = Carbon::parse($date)->endOfDay();

        $allRecords  = $this->buildRecords($start, $end, $cabangId, $request->get('jenis'));
        $periodLabel = Carbon::parse($date)->locale('id')->isoFormat('dddd, D MMMM YYYY');

        return view('shared.laporan.index', $this->viewData('harian', $allRecords, compact('date', 'periodLabel')));
    }

    public function mingguan(Request $request)
    {
        $weekStart = $request->get('week_start', now()->startOfWeek()->toDateString());
        $cabangId  = $request->get('cabang_id') ? (int) $request->get('cabang_id') : $this->cabangId();
        $start     = Carbon::parse($weekStart)->startOfDay();
        $end       = $start->copy()->addDays(6)->endOfDay();

        $allRecords  = $this->buildRecords($start, $end, $cabangId, $request->get('jenis'));
        $periodLabel = $start->locale('id')->isoFormat('D MMMM') . ' – ' . $end->locale('id')->isoFormat('D MMMM YYYY');

        return view('shared.laporan.index', $this->viewData('mingguan', $allRecords, compact('weekStart', 'periodLabel')));
    }

    public function bulanan(Request $request)
    {
        $month    = $request->get('month', now()->format('Y-m'));
        $cabangId = $request->get('cabang_id') ? (int) $request->get('cabang_id') : $this->cabangId();
        $start    = Carbon::parse($month . '-01')->startOfMonth();
        $end      = $start->copy()->endOfMonth();

        $allRecords  = $this->buildRecords($start, $end, $cabangId, $request->get('jenis'));
        $periodLabel = $start->locale('id')->isoFormat('MMMM YYYY');

        return view('shared.laporan.index', $this->viewData('bulanan', $allRecords, compact('month', 'periodLabel')));
    }

    public function exportHarian(Request $request)
    {
        $date     = $request->get('date', today()->toDateString());
        $cabangId = $request->get('cabang_id') ? (int) $request->get('cabang_id') : $this->cabangId();
        $start    = Carbon::parse($date)->startOfDay();
        $end      = Carbon::parse($date)->endOfDay();
        $records  = $this->buildRecords($start, $end, $cabangId, null);
        $label    = 'Harian ' . Carbon::parse($date)->format('d-m-Y');

        return $this->downloadExcel($records, $label, 'laporan-harian-' . $date . '.xlsx');
    }

    public function exportMingguan(Request $request)
    {
        $weekStart = $request->get('week_start', now()->startOfWeek()->toDateString());
        $cabangId  = $request->get('cabang_id') ? (int) $request->get('cabang_id') : $this->cabangId();
        $start     = Carbon::parse($weekStart)->startOfDay();
        $end       = $start->copy()->addDays(6)->endOfDay();
        $records   = $this->buildRecords($start, $end, $cabangId, null);
        $label     = 'Mingguan ' . $start->format('d-m-Y') . ' sd ' . $end->format('d-m-Y');

        return $this->downloadExcel($records, $label, 'laporan-mingguan-' . $weekStart . '.xlsx');
    }

    public function exportBulanan(Request $request)
    {
        $month    = $request->get('month', now()->format('Y-m'));
        $cabangId = $request->get('cabang_id') ? (int) $request->get('cabang_id') : $this->cabangId();
        $start    = Carbon::parse($month . '-01')->startOfMonth();
        $end      = $start->copy()->endOfMonth();
        $records  = $this->buildRecords($start, $end, $cabangId, null);
        $label    = 'Bulanan ' . $start->locale('id')->isoFormat('MMMM YYYY');

        return $this->downloadExcel($records, $label, 'laporan-bulanan-' . $month . '.xlsx');
    }

    private function downloadExcel(Collection $records, string $label, string $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan');

        $headers = ['No', 'Tanggal', 'No SBG', 'Nama Nasabah', 'Cabang', 'Barang', 'Nilai Pinjaman (Rp)', 'Nominal Transaksi (Rp)', 'Jenis Transaksi', 'Status'];
        $cols    = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];

        foreach ($cols as $i => $col) {
            $sheet->getCell($col . '1')->setValue($headers[$i]);
        }

        $sheet->getStyle('A1:J1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F5C3A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        foreach ($records as $i => $data) {
            $row = $i + 2;

            $tanggal = $data['tanggal'] instanceof Carbon
                ? $data['tanggal']->format('d/m/Y')
                : Carbon::parse($data['tanggal'])->format('d/m/Y');

            $jenis = match($data['jenis']) {
                'gadai_baru'   => 'Gadai Baru',
                'perpanjangan' => 'Perpanjangan',
                'pelunasan'    => 'Pelunasan',
                default        => ucfirst($data['jenis']),
            };

            $rowData = [
                'A' => $i + 1,
                'B' => $tanggal,
                'C' => $data['no_sbg'],
                'D' => $data['nasabah'],
                'E' => $data['cabang'],
                'F' => $data['barang'],
                'G' => $data['nilai_pinjaman'],
                'H' => $data['nominal_transaksi'],
                'I' => $jenis,
                'J' => ucfirst($data['status']),
            ];

            foreach ($rowData as $col => $value) {
                $sheet->getCell($col . $row)->setValue($value);
            }

            $bgColor = $row % 2 === 0 ? 'F5FAF5' : 'FFFFFF';
            $sheet->getStyle("A{$row}:J{$row}")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB($bgColor);
        }

        $lastRow = max($records->count() + 1, 1);

        $sheet->getStyle("A1:J{$lastRow}")->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)
            ->getColor()->setRGB('E5E7EB');

        $sheet->getStyle("G2:H{$lastRow}")->getNumberFormat()->setFormatCode('#,##0');

        foreach ($cols as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $footerRow = $lastRow + 2;
        $sheet->getCell("A{$footerRow}")->setValue('BATIM GADAI - Laporan ' . $label);
        $sheet->getCell("A" . ($footerRow + 1))->setValue('Dicetak: ' . now()->format('d M Y, H:i') . ' WIB');

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
<?php

namespace App\Exports;

use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TransactionReportExport implements FromArray, ShouldAutoSize, WithEvents
{
    private const COLOR_HEADER_BG    = '1E3A5F';
    private const COLOR_SUBHEADER_BG = '2E6DA4';
    private const COLOR_ACCENT_BG    = 'E8F1FB';
    private const COLOR_SUMMARY_BG   = 'F0F7FF';
    private const COLOR_WHITE        = 'FFFFFF';
    private const COLOR_TEXT_DARK    = '1A1A2E';
    private const COLOR_BORDER       = 'BDD7EE';
    private const COLS               = 11; // A–K

    private int $dataStartRow = 12;

    public function __construct(
        protected string $reportType,
        protected string $reportPeriod,
        protected array  $summary,
        protected        $records,
    ) {}

    public function array(): array
    {
        $rows = [];

        // R1–R4: judul
        $rows[] = ['BATIM GADAI'];
        $rows[] = ['Laporan Transaksi – ' . strtoupper($this->reportType)];
        $rows[] = ['Periode: ' . $this->reportPeriod];
        $rows[] = ['Dicetak: ' . now()->format('d/m/Y H:i')];

        // R5: spacer
        $rows[] = array_fill(0, self::COLS, null);

        // R6: header blok ringkasan
        $rows[] = ['RINGKASAN KEUANGAN'];

        // R7–R10: isi ringkasan (2 kolom kiri–kanan)
        $rows[] = ['Total Uang Pinjaman (UP)',     $this->fmt($this->summary['total_up']),         null, null, 'Net Cash Flow',      $this->fmtSign($this->summary['net_cash_flow'])];
        $rows[] = ['Total Cash Out (Uang Keluar)', $this->fmt($this->summary['cash_out']),          null, null, 'Total Sewa Modal',   $this->fmt($this->summary['total_sewa_modal'])];
        $rows[] = ['Total Cash In (Uang Masuk)',   $this->fmt($this->summary['cash_in']),           null, null, 'Total Biaya Admin',  $this->fmt($this->summary['total_admin'])];
        $rows[] = ['Jumlah Nasabah Aktif',         $this->summary['active_customers'] . ' nasabah', null, null, null, null];

        // R11: spacer
        $rows[] = array_fill(0, self::COLS, null);

        // R12: header tabel — catat posisinya
        $this->dataStartRow = count($rows) + 1;
        $rows[] = ['No', 'Tgl Gadai', 'No. SBG', 'Nasabah', 'Cabang', 'Barang Jaminan', 'Kategori', 'Nilai Taksiran', 'Uang Pinjaman', 'Uang Masuk', 'Status'];

        // Baris data
        if ($this->records->isEmpty()) {
            $row = array_fill(0, self::COLS, null);
            $row[4] = 'Tidak ada data transaksi untuk periode ini.';
            $rows[] = $row;
        } else {
            foreach ($this->records as $i => $gadai) {
                // pelunasan adalah hasMany → ambil yang berhasil pakai first()
                $pelunasanBerhasil = $gadai->pelunasan
                    ->where('status_bayar', 'berhasil')
                    ->first();

                $cashIn = (float) ($pelunasanBerhasil?->total_tebus ?? 0);

                $rows[] = [
                    $i + 1,
                    $gadai->tgl_gadai
                        ? \Carbon\Carbon::parse($gadai->tgl_gadai)->format('d/m/Y')
                        : '-',
                    $gadai->no_sbg ?? '-',
                    $gadai->nasabah?->nama ?? '-',
                    $gadai->branch?->nama ?? '-',
                    $gadai->barang?->nama_barang ?? '-',
                    $this->labelKategori($gadai->barang?->kategori),
                    $this->fmt((float) ($gadai->nilai_taksiran_akhir
                        ?? $gadai->nilai_taksiran_max
                        ?? $gadai->nilai_taksiran_min
                        ?? 0)),
                    $this->fmt((float) ($gadai->nilai_pinjaman ?? 0)),
                    $cashIn > 0 ? $this->fmt($cashIn) : '-',
                    $this->labelStatus($gadai->status),
                ];
            }
        }

        // Spacer + baris total
        $rows[] = array_fill(0, self::COLS, null);
        $rows[] = [
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            'TOTAL',
            $this->fmt($this->summary['cash_out']), 
            $this->fmt($this->summary['cash_in']),
            null
        ];

        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet     = $event->sheet->getDelegate();
                $colLast   = 'K';
                $allData   = $this->array();
                $totalRows = count($allData);

                // ── Merge & gaya baris judul (R1–R4)
                foreach ([1, 2, 3, 4] as $r) {
                    $sheet->mergeCells("A{$r}:{$colLast}{$r}");
                }

                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 18, 'color' => ['argb' => 'FF' . self::COLOR_WHITE], 'name' => 'Arial'],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . self::COLOR_HEADER_BG]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(36);

                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 13, 'color' => ['argb' => 'FF' . self::COLOR_WHITE], 'name' => 'Arial'],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . self::COLOR_SUBHEADER_BG]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(24);

                foreach ([3, 4] as $r) {
                    $sheet->getStyle("A{$r}")->applyFromArray([
                        'font'      => ['italic' => true, 'size' => 10, 'color' => ['argb' => 'FF' . self::COLOR_TEXT_DARK], 'name' => 'Arial'],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD6E4F7']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                    ]);
                    $sheet->getRowDimension($r)->setRowHeight(18);
                }

                $sheet->getRowDimension(5)->setRowHeight(6);

                // ── Blok ringkasan (R6–R10)
                $sheet->mergeCells("A6:{$colLast}6");
                $sheet->getStyle('A6')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 11, 'color' => ['argb' => 'FF' . self::COLOR_WHITE], 'name' => 'Arial'],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . self::COLOR_SUBHEADER_BG]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 1],
                ]);
                $sheet->getRowDimension(6)->setRowHeight(20);

                foreach (range(7, 10) as $r) {
                    $sheet->getStyle("A{$r}:{$colLast}{$r}")->applyFromArray([
                        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . self::COLOR_SUMMARY_BG]],
                        'font' => ['name' => 'Arial', 'size' => 10],
                    ]);
                    foreach (['A', 'E'] as $col) {
                        $sheet->getStyle("{$col}{$r}")->applyFromArray([
                            'font'      => ['bold' => true, 'color' => ['argb' => 'FF' . self::COLOR_HEADER_BG]],
                            'alignment' => ['indent' => 1],
                        ]);
                    }
                    foreach (['B', 'F'] as $col) {
                        $sheet->getStyle("{$col}{$r}")->getFont()->setBold(true);
                    }
                    $sheet->getRowDimension($r)->setRowHeight(18);
                }

                // Net cash flow merah jika negatif
                $netFlow      = $this->summary['net_cash_flow'];
                $netFlowColor = $netFlow >= 0 ? '1B5E20' : 'B71C1C';
                $sheet->getStyle('F7')->getFont()->setBold(true)->getColor()->setARGB('FF' . $netFlowColor);

                $sheet->getRowDimension(11)->setRowHeight(6);

                // ── Header tabel
                $headerRow = $this->dataStartRow;
                $sheet->getStyle("A{$headerRow}:{$colLast}{$headerRow}")->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FF' . self::COLOR_WHITE], 'name' => 'Arial'],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . self::COLOR_HEADER_BG]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF' . self::COLOR_WHITE]]],
                ]);
                $sheet->getRowDimension($headerRow)->setRowHeight(28);

                // ── Baris data
                $dataFirstRow = $headerRow + 1;
                $dataLastRow  = $totalRows - 2; // -2: spacer + baris total di akhir

                for ($r = $dataFirstRow; $r <= $dataLastRow; $r++) {
                    $isEven = (($r - $dataFirstRow) % 2 === 1);
                    $sheet->getStyle("A{$r}:{$colLast}{$r}")->applyFromArray([
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $isEven ? ('FF' . self::COLOR_ACCENT_BG) : 'FFFFFFFF']],
                        'font'      => ['name' => 'Arial', 'size' => 9],
                        'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_HAIR, 'color' => ['argb' => 'FF' . self::COLOR_BORDER]]],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    ]);

                    $sheet->getStyle("A{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle("B{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    foreach (['H', 'I', 'J'] as $col) {
                        $sheet->getStyle("{$col}{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    }
                    $sheet->getStyle("K{$r}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $statusVal = $sheet->getCell("K{$r}")->getValue();
                    $this->applyStatusColor($sheet, "K{$r}", $statusVal);

                    $sheet->getRowDimension($r)->setRowHeight(16);
                }

                // ── Baris total
                $totalRow = $totalRows;
                $sheet->getStyle("A{$totalRow}:{$colLast}{$totalRow}")->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 10, 'color' => ['argb' => 'FF' . self::COLOR_WHITE], 'name' => 'Arial'],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF' . self::COLOR_HEADER_BG]],
                    'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                ]);
                foreach (['H', 'I', 'J'] as $col) {
                    $sheet->getStyle("{$col}{$totalRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                }
                $sheet->getRowDimension($totalRow)->setRowHeight(20);

                // ── Outer border seluruh tabel
                $sheet->getStyle("A{$headerRow}:{$colLast}{$totalRow}")->applyFromArray([
                    'borders' => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => 'FF' . self::COLOR_SUBHEADER_BG]]],
                ]);

                // ── Freeze pane di bawah header tabel
                $sheet->freezePane("A{$dataFirstRow}");

                // ── Lebar kolom
                $widths = [
                    'A' => 5,
                    'B' => 13,
                    'C' => 18,
                    'D' => 24,
                    'E' => 20,
                    'F' => 28,
                    'G' => 18,
                    'H' => 18,
                    'I' => 18,
                    'J' => 18,
                    'K' => 18
                ];
                foreach ($widths as $col => $w) {
                    $sheet->getColumnDimension($col)->setWidth($w);
                }

                $event->sheet->setTitle('Laporan Transaksi');
            },
        ];
    }

    protected function fmt(float $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }

    protected function fmtSign(float $value): string
    {
        return ($value >= 0 ? '+' : '') . 'Rp ' . number_format($value, 0, ',', '.');
    }

    protected function labelKategori(?string $k): string
    {
        return match ($k) {
            'handphone'           => 'Handphone',
            'laptop'              => 'Laptop',
            'tablet'              => 'Tablet',
            'elektronik_lainnya'  => 'Elektronik Lainnya',
            'kendaraan_motor'     => 'Kendaraan Motor',
            'barang_rumah_tangga' => 'Barang Rumah Tangga',
            'perhiasan'           => 'Perhiasan',
            default               => Str::title(str_replace('_', ' ', $k ?? '-')),
        };
    }

    protected function labelStatus(?string $s): string
    {
        return match ($s) {
            'menunggu_approval' => 'Menunggu Approval',
            'disetujui'         => 'Disetujui',
            'ditolak'           => 'Ditolak',
            'aktif'             => 'Aktif',
            'jatuh_tempo'       => 'Jatuh Tempo',
            'perpanjangan'      => 'Perpanjangan',
            'lunas'             => 'Lunas',
            'lelang'            => 'Lelang',
            default             => Str::title(str_replace('_', ' ', $s ?? '-')),
        };
    }

    protected function applyStatusColor($sheet, string $cell, ?string $label): void
    {
        $color = match ($label) {
            'Aktif'             => '1B5E20',
            'Lunas'             => '0D47A1',
            'Jatuh Tempo'       => 'E65100',
            'Ditolak', 'Lelang' => 'B71C1C',
            'Perpanjangan'      => '4A148C',
            'Menunggu Approval' => '795548',
            default             => self::COLOR_TEXT_DARK,
        };
        $sheet->getStyle($cell)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FF' . $color]],
        ]);
    }
}

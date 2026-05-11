<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class TransactionReportExport implements FromArray, WithEvents
{
    private const COLS     = 11;
    private const COL_LAST = 'K';
    private const TEMPLATE = 'app/Exports/laporan-transaksi-v4.xlsx';

    private int  $dataStartRow = 14;
    private int  $dataCount    = 0;
    private int  $totalRow     = 17;
    private bool $hasData      = false;

    public function __construct(
        protected string $reportType,
        protected string $reportPeriod,
        protected array  $summary,
        protected        $records,
    ) {}

    // -------------------------------------------------------------------------
    // Data
    // -------------------------------------------------------------------------

    public function array(): array
    {
        $records = $this->recordsCollection();
        $this->hasData   = $records->isNotEmpty();
        $this->dataCount = max(1, $records->count());
        $this->totalRow  = $this->dataStartRow + $this->dataCount + 2;

        $rows   = [];
        $rows[] = $this->blankRow(); // 1  – accent bar
        $rows[] = $this->row([       // 2  – banner
            'A' => 'BATIM GADAI',
            'H' => $this->summary['active_customers'] ?? 0,
            'J' => "Nasabah Aktif\n" . $this->reportPeriod,
        ]);
        $rows[] = $this->row([       // 3  – subtitle
            'A' => 'Laporan Transaksi ' . Str::title(strtolower($this->reportType))
                 . '  —  Dicetak: ' . now()->format('d/m/Y H:i'),
        ]);
        $rows[] = $this->blankRow(); // 4
        $rows[] = $this->blankRow(); // 5
        $rows[] = $this->row(['A' => 'RINGKASAN KEUANGAN']); // 6
        $rows[] = $this->row([       // 7
            'A' => 'Uang Pinjaman (UP)',
            'D' => $this->fmt($this->summary['total_up'] ?? 0),
            'G' => 'Arus Kas Bersih (Net)',
            'J' => $this->fmtSign($this->summary['net_cash_flow'] ?? 0),
        ]);
        $rows[] = $this->row([       // 8
            'A' => 'Total Uang Keluar',
            'D' => $this->fmt($this->summary['cash_out'] ?? 0),
            'G' => 'Total Sewa Modal',
            'J' => $this->fmt($this->summary['total_sewa_modal'] ?? 0),
        ]);
        $rows[] = $this->row([       // 9
            'A' => 'Total Uang Masuk',
            'D' => $this->fmt($this->summary['cash_in'] ?? 0),
            'G' => 'Total Biaya Admin',
            'J' => $this->fmt($this->summary['total_admin'] ?? 0),
        ]);
        $rows[] = $this->blankRow(); // 10
        $rows[] = $this->blankRow(); // 11
        $rows[] = [                  // 12 – column headers
            'No', 'Tgl Gadai', 'No. SBG', 'Nama Nasabah', 'Cabang',
            'Barang Jaminan', 'Kategori', 'Nilai Taksiran',
            'Uang Pinjaman', 'Uang Masuk', 'Status',
        ];
        $rows[] = $this->blankRow(); // 13 – blank data-template row

        if ($records->isEmpty()) {
            $rows[] = $this->row(['A' => 'Tidak ada data transaksi untuk periode ini.']);
        } else {
            foreach ($records as $i => $record) {
                $rows[] = $this->recordRow($record, $i + 1);
            }
        }

        $rows[] = $this->blankRow(); // spacer
        $rows[] = $this->blankRow(); // spacer
        $rows[] = $this->row([       // TOTAL
            'A' => 'TOTAL',
            'I' => $this->fmt($this->summary['cash_out'] ?? 0),
            'J' => $this->fmt($this->summary['cash_in'] ?? 0),
        ]);

        return $rows;
    }

    // -------------------------------------------------------------------------
    // Events
    // -------------------------------------------------------------------------

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet    = $event->sheet->getDelegate();
                $template = IOFactory::load(base_path(self::TEMPLATE))->getActiveSheet();

                $this->applyColumnWidths($sheet, $template);
                $this->applyStaticStyles($sheet);
                $this->applyMerges($sheet, $template);
                $this->applyDynamicTableStyle($sheet);

                $sheet->freezePane('A13');
                $event->sheet->setTitle('Laporan Transaksi');
            },
        ];
    }

    // -------------------------------------------------------------------------
    // Column widths (from template file)
    // -------------------------------------------------------------------------

    private function applyColumnWidths($sheet, $template): void
    {
        foreach (range('A', self::COL_LAST) as $col) {
            $sheet->getColumnDimension($col)
                  ->setWidth($template->getColumnDimension($col)->getWidth());
        }
    }

    // -------------------------------------------------------------------------
    // Static styles (rows 1-13)
    //
    // Every value here is taken directly from template inspection.
    // Using applyFromArray() instead of duplicateStyle() because
    // duplicateStyle() only copies the top-left cell's style when the source
    // range contains merged cells, silently flattening per-cell fills/borders.
    // -------------------------------------------------------------------------

    private function applyStaticStyles($sheet): void
    {
        // Row heights
        foreach ([
            1 => 4.05,  2 => 42.0,  3 => 18.0,  4 => 4.05,
            5 => 10.05, 6 => 22.05, 7 => 22.05, 8 => 22.05,
            9 => 22.05, 10 => 4.05, 11 => 7.95,  12 => 28.05, 13 => 24.0,
        ] as $row => $h) {
            $sheet->getRowDimension($row)->setRowHeight($h);
        }

        // ── Row 1 – thin accent bar ───────────────────────────────────────────
        $sheet->getStyle('A1:K1')->applyFromArray($this->sf('FF28A455'));

        // ── Row 2 – banner ────────────────────────────────────────────────────
        $sheet->getStyle('A2:G2')->applyFromArray(
            $this->sf('FF1E6B3C') + $this->fnt(20, true, 'FFFFFFFF', Alignment::HORIZONTAL_CENTER)
        );
        $sheet->getStyle('H2:I2')->applyFromArray(
            $this->sf('FF256E42') + $this->fnt(28, true, 'FFFFFFFF', Alignment::HORIZONTAL_CENTER)
        );
        // J2:K2 needs wrapText=true so the \n in "Nasabah Aktif\n{period}" renders
        $sheet->getStyle('J2:K2')->applyFromArray(
            $this->sf('FF1E6B3C') + $this->fnt(9, false, 'FFA8D5B5', Alignment::HORIZONTAL_CENTER, true)
        );

        // ── Row 3 – subtitle bar ──────────────────────────────────────────────
        $sheet->getStyle('A3:K3')->applyFromArray(
            $this->sf('FF28A455') + $this->fnt(10, false, 'FFFFFFFF', Alignment::HORIZONTAL_CENTER)
        );

        // ── Rows 4-5 – spacers ────────────────────────────────────────────────
        $sheet->getStyle('A4:K4')->applyFromArray($this->sf('FFA8D5B5'));
        $sheet->getStyle('A5:K5')->applyFromArray($this->sf('FFF2FAF5'));

        // ── Row 6 – Ringkasan header ──────────────────────────────────────────
        $sheet->getStyle('A6:K6')->applyFromArray(
            $this->sf('FFE8F5ED')
            + $this->fnt(10, true, 'FF1A6035', Alignment::HORIZONTAL_LEFT)
            + ['borders' => [
                'top'    => $this->bd(Border::BORDER_MEDIUM, 'FFA8D5B5'),
                'bottom' => $this->bd(Border::BORDER_THIN,   'FFA8D5B5'),
            ]]
        );
        $sheet->getStyle('A6')->applyFromArray(['borders' => ['left'  => $this->bd(Border::BORDER_MEDIUM, 'FF28A455')]]);
        $sheet->getStyle('K6')->applyFromArray(['borders' => ['right' => $this->bd(Border::BORDER_MEDIUM, 'FF28A455')]]);

        // ── Rows 7-9 – summary data ───────────────────────────────────────────
        //   Row 7: label fill FFF2FAF5, bottom=hair
        //   Row 8: label fill FFE8F5ED, bottom=hair
        //   Row 9: label fill FFF2FAF5, bottom=medium (closing border)
        $this->applySummaryRow($sheet, 7, 'FFF2FAF5', Border::BORDER_HAIR,   false);
        $this->applySummaryRow($sheet, 8, 'FFE8F5ED', Border::BORDER_HAIR,   false);
        $this->applySummaryRow($sheet, 9, 'FFF2FAF5', Border::BORDER_MEDIUM, true);

        // ── Rows 10-11 – spacers ──────────────────────────────────────────────
        $sheet->getStyle('A10:K10')->applyFromArray($this->sf('FFA8D5B5'));
        $sheet->getStyle('A11:K11')->applyFromArray($this->sf('FFF2FAF5'));

        // ── Row 12 – table column headers ─────────────────────────────────────
        $sheet->getStyle('A12:K12')->applyFromArray(
            $this->sf('FF1E6B3C')
            + $this->fnt(9, true, 'FFFFFFFF', Alignment::HORIZONTAL_CENTER, true)
            + ['borders' => [
                'top'        => $this->bd(Border::BORDER_MEDIUM, 'FF0D3322'),
                'bottom'     => $this->bd(Border::BORDER_MEDIUM, 'FF0D3322'),
                'allBorders' => $this->bd(Border::BORDER_THIN,   'FF28A455'),
            ]]
        );

        // ── Row 13 – blank data-template row ──────────────────────────────────
        $sheet->getStyle('A13:K13')->applyFromArray(
            $this->sf('FFFFFFFF')
            + $this->fnt(9, false, 'FF1A1A2E')
            + ['borders' => ['allBorders' => $this->bd(Border::BORDER_HAIR, 'FFA8D5B5')]]
        );
        $sheet->getStyle('A13')->applyFromArray(['borders' => ['left'  => $this->bd(Border::BORDER_THIN, 'FFA8D5B5')]]);
        $sheet->getStyle('K13')->applyFromArray(['borders' => ['right' => $this->bd(Border::BORDER_THIN, 'FFA8D5B5')]]);
    }

    /**
     * Style one row of the Ringkasan (summary) section.
     *
     * Layout per row:
     *   A:C  label  (left panel)   – $labelFill background
     *   D:E  value  (left panel)   – cream FFFEF9EC background, gold text
     *   F    separator column      – mint FFA8D5B5 fill
     *   G:I  label  (right panel)  – $labelFill background
     *   J:K  value  (right panel)  – cream FFFEF9EC background, gold text
     */
    private function applySummaryRow($sheet, int $row, string $labelFill, string $btmBorder, bool $isLast): void
    {
        // Left label
        $sheet->getStyle("A{$row}:C{$row}")->applyFromArray(
            $this->sf($labelFill)
            + $this->fnt(9, false, 'FF0F3D22', Alignment::HORIZONTAL_LEFT)
            + ['borders' => ['bottom' => $this->bd($btmBorder, 'FFA8D5B5')]]
        );
        $sheet->getStyle("A{$row}")->applyFromArray(['borders' => ['left' => $this->bd(Border::BORDER_MEDIUM, 'FF28A455')]]);

        // Left value
        $sheet->getStyle("D{$row}:E{$row}")->applyFromArray(
            $this->sf('FFFEF9EC')
            + $this->fnt(11, true, 'FFC47D0E', Alignment::HORIZONTAL_CENTER)
            + ['borders' => ['bottom' => $this->bd($btmBorder, 'FFA8D5B5')]]
        );

        // Separator
        $sheet->getStyle("F{$row}")->applyFromArray($this->sf('FFA8D5B5'));

        // Right label
        $sheet->getStyle("G{$row}:I{$row}")->applyFromArray(
            $this->sf($labelFill)
            + $this->fnt(9, false, 'FF0F3D22', Alignment::HORIZONTAL_LEFT)
            + ['borders' => ['bottom' => $this->bd($btmBorder, 'FFA8D5B5')]]
        );

        // Right value
        $sheet->getStyle("J{$row}:K{$row}")->applyFromArray(
            $this->sf('FFFEF9EC')
            + $this->fnt(11, true, 'FFC47D0E', Alignment::HORIZONTAL_CENTER)
            + ['borders' => [
                'bottom' => $this->bd($btmBorder, 'FFA8D5B5'),
                'right'  => $this->bd(Border::BORDER_MEDIUM, 'FF28A455'),
            ]]
        );

        // Row 9 (last): override bottom with a medium green border across the full width
        if ($isLast) {
            $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                'borders' => ['bottom' => $this->bd(Border::BORDER_MEDIUM, 'FF28A455')],
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // Merge cells (copied from template, skipping dynamic rows)
    // -------------------------------------------------------------------------

    private function applyMerges($sheet, $template): void
    {
        foreach ($template->getMergeCells() as $range) {
            if (str_starts_with($range, 'A14:') || str_starts_with($range, 'A17:')) {
                continue;
            }
            $sheet->mergeCells($range);
        }
    }

    // -------------------------------------------------------------------------
    // Dynamic table body (data rows + spacer + total row)
    // -------------------------------------------------------------------------

    private function applyDynamicTableStyle($sheet): void
    {
        $dataStyle = $this->sf('FFFFFFFF')
            + $this->fnt(9, false, 'FF1A1A2E')
            + ['borders' => ['allBorders' => $this->bd(Border::BORDER_HAIR, 'FFA8D5B5')]];

        if (!$this->hasData) {
            $sheet->mergeCells("A{$this->dataStartRow}:K{$this->dataStartRow}");
            $sheet->getStyle("A{$this->dataStartRow}:K{$this->dataStartRow}")->applyFromArray(
                $this->sf('FFF2FAF5')
                + $this->fnt(9, false, 'FF666666', Alignment::HORIZONTAL_CENTER)
                + ['borders' => ['allBorders' => $this->bd(Border::BORDER_HAIR, 'FFA8D5B5')]]
            );
            $sheet->getRowDimension($this->dataStartRow)->setRowHeight(24);
        } else {
            for ($row = $this->dataStartRow; $row < $this->dataStartRow + $this->dataCount; $row++) {
                $sheet->getStyle("A{$row}:K{$row}")->applyFromArray($dataStyle);
                $sheet->getStyle("A{$row}")->applyFromArray(['borders' => ['left'  => $this->bd(Border::BORDER_THIN, 'FFA8D5B5')]]);
                $sheet->getStyle("K{$row}")->applyFromArray(['borders' => ['right' => $this->bd(Border::BORDER_THIN, 'FFA8D5B5')]]);

                $sheet->getRowDimension($row)->setRowHeight(24);

                $sheet->getStyle("A{$row}:C{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("H{$row}:J{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("K{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("A{$row}:K{$row}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                $this->applyStatusColor($sheet, "K{$row}", $sheet->getCell("K{$row}")->getValue());
            }
        }

        // Spacer before total
        $sheet->getRowDimension($this->totalRow - 1)->setRowHeight(7.95);

        // ── TOTAL row ─────────────────────────────────────────────────────────
        $sheet->mergeCells("A{$this->totalRow}:H{$this->totalRow}");
        $sheet->getRowDimension($this->totalRow)->setRowHeight(22.05);

        // A:H – label
        $sheet->getStyle("A{$this->totalRow}:H{$this->totalRow}")->applyFromArray(
            $this->sf('FF1E6B3C')
            + $this->fnt(11, true, 'FFFFFFFF', Alignment::HORIZONTAL_CENTER)
            + ['borders' => ['right' => $this->bd(Border::BORDER_THIN, 'FF28A455')]]
        );
        // I:J – totals
        $sheet->getStyle("I{$this->totalRow}:J{$this->totalRow}")->applyFromArray(
            $this->sf('FF1A5530')
            + $this->fnt(11, true, 'FFC8F0D8', Alignment::HORIZONTAL_CENTER)
            + ['borders' => [
                'top'        => $this->bd(Border::BORDER_MEDIUM, 'FF28A455'),
                'allBorders' => $this->bd(Border::BORDER_THIN,   'FF28A455'),
            ]]
        );
        // K – right cap
        $sheet->getStyle("K{$this->totalRow}")->applyFromArray(
            $this->sf('FF1E6B3C')
            + ['borders' => ['top' => $this->bd(Border::BORDER_MEDIUM, 'FF28A455')]]
        );

        // Outer outline around the whole table section
        $sheet->getStyle('A12:K' . $this->totalRow)->applyFromArray([
            'borders' => ['outline' => $this->bd(Border::BORDER_MEDIUM, 'FF1E6B3C')],
        ]);
    }

    // -------------------------------------------------------------------------
    // Row builders
    // -------------------------------------------------------------------------

    private function recordRow($record, int $number): array
    {
        $tanggal        = data_get($record, 'tanggal') ?? data_get($record, 'tgl_gadai');
        $barangKategori = data_get($record, 'barang.kategori');
        $taksiran       = data_get($record, 'taksiran')
            ?? data_get($record, 'nilai_taksiran_akhir')
            ?? data_get($record, 'nilai_taksiran_max')
            ?? data_get($record, 'nilai_taksiran_min')
            ?? 0;

        return [
            $number,
            $tanggal ? Carbon::parse($tanggal)->format('d/m/Y') : '-',
            data_get($record, 'no_sbg')            ?? '-',
            data_get($record, 'nasabah.nama')       ?? '-',
            data_get($record, 'branch.nama')        ?? '-',
            data_get($record, 'barang.nama_barang') ?? '-',
            $this->labelKategori($barangKategori),
            $this->fmt((float) $taksiran),
            $this->fmt((float) (data_get($record, 'cash_out') ?? data_get($record, 'nilai_pinjaman') ?? 0)),
            $this->fmt((float) (data_get($record, 'cash_in') ?? 0)),
            $this->labelStatus(data_get($record, 'status')),
        ];
    }

    private function row(array $values): array
    {
        $row = $this->blankRow();
        foreach ($values as $col => $val) {
            $row[ord($col) - ord('A')] = $val;
        }
        return $row;
    }

    private function blankRow(): array
    {
        return array_fill(0, self::COLS, null);
    }

    // -------------------------------------------------------------------------
    // Style micro-helpers
    // -------------------------------------------------------------------------

    /** Solid fill array fragment */
    private function sf(string $argb): array
    {
        return ['fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $argb]]];
    }

    /**
     * Font + alignment array fragment.
     * @param bool $wrap  Set wrapText=true (required for \n line-breaks in cells).
     */
    private function fnt(
        int    $size,
        bool   $bold,
        string $argb,
        string $hAlign = Alignment::HORIZONTAL_LEFT,
        bool   $wrap   = false,
    ): array {
        return [
            'font'      => ['name' => 'Calibri', 'size' => $size, 'bold' => $bold, 'color' => ['argb' => $argb]],
            'alignment' => ['horizontal' => $hAlign, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => $wrap],
        ];
    }

    /** Single border-side array fragment */
    private function bd(string $style, string $argb): array
    {
        return ['borderStyle' => $style, 'color' => ['argb' => $argb]];
    }

    // -------------------------------------------------------------------------
    // Collection helper
    // -------------------------------------------------------------------------

    private function recordsCollection(): Collection
    {
        return $this->records instanceof Collection ? $this->records : collect($this->records);
    }

    // -------------------------------------------------------------------------
    // Formatters
    // -------------------------------------------------------------------------

    private function fmt(float $value): string
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }

    private function fmtSign(float $value): string
    {
        return ($value >= 0 ? '+' : '') . 'Rp ' . number_format($value, 0, ',', '.');
    }

    // -------------------------------------------------------------------------
    // Label maps
    // -------------------------------------------------------------------------

    private function labelKategori(?string $category): string
    {
        return match ($category) {
            'handphone'           => 'Handphone',
            'laptop'              => 'Laptop',
            'tablet'              => 'Tablet',
            'elektronik_lainnya'  => 'Elektronik Lainnya',
            'kendaraan_motor'     => 'Kendaraan Motor',
            'barang_rumah_tangga' => 'Barang Rumah Tangga',
            'perhiasan'           => 'Perhiasan',
            default               => Str::title(str_replace('_', ' ', $category ?? '-')),
        };
    }

    private function labelStatus(?string $status): string
    {
        return match ($status) {
            'menunggu_approval' => 'Menunggu Approval',
            'disetujui'         => 'Disetujui',
            'ditolak'           => 'Ditolak',
            'aktif'             => 'Aktif',
            'jatuh_tempo'       => 'Jatuh Tempo',
            'perpanjangan'      => 'Perpanjangan',
            'lunas'             => 'Lunas',
            'lelang'            => 'Lelang',
            default             => Str::title(str_replace('_', ' ', $status ?? '-')),
        };
    }

    // -------------------------------------------------------------------------
    // Status colour
    // -------------------------------------------------------------------------

    private function applyStatusColor($sheet, string $cell, ?string $label): void
    {
        $color = match ($label) {
            'Aktif'              => '1B5E20',
            'Lunas'              => '0D47A1',
            'Jatuh Tempo'        => 'E65100',
            'Ditolak', 'Lelang'  => 'B71C1C',
            'Perpanjangan'       => '4A148C',
            'Menunggu Approval'  => '795548',
            default              => '1A1A2E',
        };

        $sheet->getStyle($cell)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['argb' => 'FF' . $color]],
        ]);
    }
}
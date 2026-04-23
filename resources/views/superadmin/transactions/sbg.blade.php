<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm;
        }
        body {
            font-family: "Courier New", Courier, monospace;
            font-size: 12px;
            line-height: 1.3;
        }
        .container {
            width: 100%;
            border: 2px solid #000;
            padding: 20px;
        }
        /* Header */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .company-info {
            font-size: 11px;
            padding-bottom: 10px;
        }

        /* Struktur Kolom Menggunakan Tabel Murni */
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }
        .col-left {
            width: 48%;
            padding-right: 25px;
            vertical-align: top;
            border-right: 1px dashed #000;
        }
        .col-right {
            width: 52%;
            padding-left: 25px;
            vertical-align: top;
        }

        /* Form Input */
        .form-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .form-table td {
            padding: 8px 0;
            vertical-align: bottom;
        }
        .dotted-line {
            border-bottom: 1px dotted #000;
        }

        /* Tanda Tangan */
        .signature-table {
            width: 100%;
            margin-top: 30px;
            text-align: center;
        }
        .sig-space {
            height: 70px;
        }

        /* Perjanjian */
        .perjanjian-text {
            font-size: 10px;
            text-align: justify;
            line-height: 1.6;
        }
        .jam-operasional {
            border: 1px solid #000;
            padding: 10px;
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <table class="header-table">
            <tr>
                <td align="center">
                    <div class="company-name">Bintang Timur Pergadaian (Batim Gadai) [cite: 1, 38]</div>
                    <div class="company-info">
                        Jl. Bamba II No. 30 RT.02 RW.27, Sumbersari, Jember | WA: 0852 3313 3366 [cite: 1, 40]<br>
                        BH: AHU-000973.AH.01. TAHUN 2020 | NIB: 0220000071638 [cite: 1, 41]
                    </div>
                </td>
            </tr>
        </table>

        <table class="main-table">
            <tr>
                <td class="col-left">
                    <div style="text-align: center; font-weight: bold; font-size: 15px; margin-bottom: 15px;">
                        SURAT BUKTI GADAI (SBG) [cite: 6, 43]
                    </div>
                    <table class="form-table">
                        <tr>
                            <td width="35%">Nomor SBG</td>
                            <td width="5%">:</td>
                            <td class="dotted-line">{{ $transaction->no_sbg }}</td>
                        </tr>
                        <tr>
                            <td>Nama Nasabah</td>
                            <td>:</td>
                            <td class="dotted-line">{{ $transaction->customer?->nama }}</td>
                        </tr>
                        <tr>
                            <td>No. KTP/SIM</td>
                            <td>:</td>
                            <td class="dotted-line">{{ $transaction->customer?->no_ktp }}</td>
                        </tr>
                        <tr>
                            <td>Barang Jaminan</td>
                            <td>:</td>
                            <td class="dotted-line">{{ $transaction->item_name }}</td>
                        </tr>
                        <tr>
                            <td>Uang Pinjaman</td>
                            <td>:</td>
                            <td class="dotted-line">Rp {{ number_format($transaction->loan_amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Pinjaman</td>
                            <td>:</td>
                            <td class="dotted-line">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td>Jatuh Tempo</td>
                            <td>:</td>
                            <td class="dotted-line">{{ $transaction->due_date ? \Carbon\Carbon::parse($transaction->due_date)->format('d/m/Y') : '-' }}</td>
                        </tr>
                    </table>

                    <table class="signature-table">
                        <tr>
                            <td width="50%">
                                NASABAH [cite: 7, 62]<br>
                                <div class="sig-space"></div>
                                ({{ $transaction->customer?->nama ?? '________________' }}) [cite: 9, 63]
                            </td>
                            <td width="50%">
                                Pimpinan Cabang [cite: 14, 65]<br>
                                <div class="sig-space"></div>
                                (________________)
                            </td>
                        </tr>
                    </table>

                    <div class="jam-operasional">
                        BUKA JAM 07.00 - 17.00 WIB [cite: 11, 66]<br>
                        HARI MINGGU / LIBUR NASIONAL TUTUP [cite: 11, 67]
                    </div>
                </td>

                <td class="col-right">
                    <div style="text-align: center; font-weight: bold; margin-bottom: 10px; text-decoration: underline;">
                        PERJANJIAN PERGADAIAN [cite: 16, 68]
                    </div>
                    <div class="perjanjian-text">
                        <p>1. Barang jaminan telah diserahkan dan milik sah NASABAH menurut hukum[cite: 18, 69].</p>
                        <p>2. Jangka waktu pinjaman 1 (satu) bulan. Hitungan 1-15 hari dihitung 1 bulan, 16-30 hari dihitung bulan berikutnya[cite: 21, 70].</p>
                        <p>3. Masa tenggang diberikan selama 6 (enam) bulan dari tanggal pinjaman[cite: 22, 71].</p>
                        <p>4. Apabila sampai jatuh tempo barang tidak ditebus, BINTANG TIMUR PERGADAIAN berhak melelang[cite: 26, 72].</p>
                        <p>5. NASABAH wajib membawa SBG asli saat pengambilan barang[cite: 29, 73].</p>
                        <p>6. Risiko kerusakan akibat force majeure bukan tanggung jawab kami[cite: 32, 74].</p>
                        <p>7. Data nasabah dijaga kerahasiaannya sesuai undang-undang[cite: 34, 75].</p>
                    </div>
                    <div style="margin-top: 50px; font-size: 9px; font-style: italic; color: #666;">
                        Dicetak secara sistem pada: {{ now()->format('d/m/Y H:i') }} [cite: 37, 76]
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
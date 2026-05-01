<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SBG {{ $gadai->no_sbg }} — BATIM GADAI</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            font-size: 11.5px;
            color: #1a1a1a;
            padding: 16px 21px;
        }

        /* ── KOP ── */
        .kop {
            display: table;
            width: 100%;
            padding-bottom: 9px;
            border-bottom: 3px solid #1F5C3A;
            margin-bottom: 11px;
        }
        .kop-logo {
            display: table-cell;
            width: 12%;
            vertical-align: middle;
            text-align: center;
        }
        .kop-logo img {
            width: 47px;
            height: 47px;
            object-fit: contain;
        }
        .kop-tengah {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding: 0 10px;
        }
        .kop-tengah .nama {
            font-size: 16.5px;
            font-weight: bold;
            color: #1F5C3A;
            letter-spacing: 0.5px;
        }
        .kop-tengah .nama-pt {
            font-size: 12.5px;
            font-weight: bold;
            color: #1a1a1a;
            margin-top: 1px;
        }
        .kop-tengah .bh {
            font-size: 9px;
            color: #555;
            margin-top: 2px;
        }
        .kop-tengah .alamat {
            font-size: 9px;
            color: #555;
            margin-top: 1px;
        }
        .kop-ojk {
            display: table-cell;
            width: 12%;
            vertical-align: middle;
            text-align: center;
        }
        .kop-ojk .ojk-label {
            font-size: 8px;
            color: #888;
            margin-bottom: 3px;
        }
        .kop-ojk img {
            width: 45px;
            height: auto;
        }

        /* ── JUDUL ── */
        .judul-sbg {
            text-align: center;
            border: 2px solid #1a1a1a;
            padding: 7px;
            margin-bottom: 11px;
        }
        .judul-sbg h2 {
            font-size: 13.5px;
            font-weight: bold;
            letter-spacing: 1.5px;
        }

        /* ── 2 KOLOM ── */
        .two-col {
            display: table;
            width: 100%;
        }
        .col-left {
            display: table-cell;
            width: 51%;
            vertical-align: top;
            padding-right: 13px;
            border-right: 1.5px solid #bbb;
        }
        .col-right {
            display: table-cell;
            width: 49%;
            vertical-align: top;
            padding-left: 13px;
        }

        /* ── FORM ROWS ── */
        .form-row {
            display: table;
            width: 100%;
            margin-bottom: 7px;
        }
        .form-label {
            display: table-cell;
            width: 40%;
            font-size: 11px;
            color: #444;
            vertical-align: top;
            padding-top: 2px;
        }
        .form-sep {
            display: table-cell;
            width: 4%;
            font-size: 11px;
            color: #444;
            vertical-align: top;
            padding-top: 2px;
        }
        .form-value {
            display: table-cell;
            vertical-align: top;
        }
        .form-line {
            border-bottom: 1px solid #555;
            min-height: 16px;
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
            padding-bottom: 2px;
        }
        .form-line-tall {
            border-bottom: 1px solid #555;
            min-height: 34px;
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
            padding-bottom: 2px;
            line-height: 1.55;
        }

        /* ── RINCIAN ── */
        .rincian {
            margin-top: 10px;
            border: 1px solid #bbb;
        }
        .rincian-row {
            display: table;
            width: 100%;
            border-bottom: 1px solid #e0e0e0;
        }
        .rincian-row:last-child { border-bottom: none; }
        .rincian-label {
            display: table-cell;
            padding: 5px 7px;
            font-size: 11px;
            color: #444;
            width: 55%;
        }
        .rincian-value {
            display: table-cell;
            padding: 5px 7px;
            font-size: 11px;
            font-weight: bold;
            text-align: right;
        }
        .rincian-highlight { background-color: #fff8e1; }
        .rincian-highlight .rincian-label { font-weight: bold; color: #b45309; }
        .rincian-highlight .rincian-value { color: #b45309; }
        .rincian-total { background-color: #f0f7f2; }
        .rincian-total .rincian-label { font-weight: bold; color: #1F5C3A; font-size: 11.5px; }
        .rincian-total .rincian-value { color: #1F5C3A; font-size: 11.5px; }

        /* ── INFO JAM ── */
        .info-box {
            border: 1.5px solid #1a1a1a;
            padding: 7px;
            margin-top: 10px;
            text-align: center;
        }
        .info-box .jam-title { font-size: 10.5px; font-weight: bold; }
        .info-box .jam-val { font-size: 13.5px; font-weight: bold; color: #1F5C3A; margin-top: 1px; }
        .info-box .jam-note { font-size: 9.5px; color: #555; margin-top: 1px; }
        .info-box .tutup { font-size: 10.5px; font-weight: bold; color: #dc2626; margin-top: 2px; }

        /* ── PERSETUJUAN ── */
        .persetujuan-box {
            border: 1px solid #bbb;
            padding: 7px 9px;
            margin-top: 10px;
            background-color: #fafafa;
        }
        .persetujuan-box p {
            font-size: 10px;
            color: #333;
            line-height: 1.6;
            text-align: justify;
        }

        /* ── TTD ── */
        .ttd-section { margin-top: 10px; }
        .ttd-row { display: table; width: 100%; }
        .ttd-cell {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 0 7px;
            vertical-align: top;
        }
        .ttd-label {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 2px;
        }
        .ttd-sub {
            font-size: 9.5px;
            color: #555;
            margin-bottom: 3px;
            min-height: 14px;
        }
        .ttd-space {
            height: 52px;
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
        }
        .ttd-nama {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
        }
        .ttd-jabatan {
            font-size: 9.5px;
            color: #666;
            margin-top: 2px;
        }

        /* ── PERJANJIAN ── */
        .perjanjian-title {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 6px;
            color: #1a1a1a;
            text-decoration: underline;
        }
        .perjanjian-intro {
            font-size: 9.5px;
            color: #333;
            line-height: 1.6;
            margin-bottom: 6px;
            text-align: justify;
        }
        .perjanjian-list {
            counter-reset: item;
            padding-left: 0;
        }
        .perjanjian-list li {
            font-size: 8.8px;
            color: #333;
            line-height: 1.58;
            margin-bottom: 4px;
            list-style: none;
            padding-left: 15px;
            position: relative;
            text-align: justify;
        }
        .perjanjian-list li::before {
            content: counter(item) ".";
            counter-increment: item;
            position: absolute;
            left: 0;
            font-weight: bold;
            color: #1a1a1a;
        }
        .perjanjian-footer {
            font-size: 8.8px;
            color: #333;
            line-height: 1.58;
            margin-top: 6px;
            text-align: justify;
        }

        /* ── FOOTER QR ── */
        .bottom-bar {
            display: table;
            width: 100%;
            margin-top: 12px;
            border-top: 2px solid #1F5C3A;
            padding-top: 9px;
        }
        .bottom-left {
            display: table-cell;
            vertical-align: middle;
            width: 70%;
            padding-right: 10px;
        }
        .bottom-right {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            width: 30%;
        }
        .bottom-right img { width: 92px; height: 92px; }
        .bottom-right p { font-size: 8.5px; color: #666; margin-top: 3px; }
        .verify-title {
            font-size: 11px;
            font-weight: bold;
            color: #1F5C3A;
            margin-bottom: 4px;
        }
        .verify-text {
            font-size: 10px;
            color: #333;
            line-height: 1.65;
        }
        .verify-url {
            font-size: 9.5px;
            color: #1F5C3A;
            font-weight: bold;
            word-break: break-all;
            margin-top: 3px;
        }
        .footer-note {
            font-size: 8.5px;
            color: #999;
            margin-top: 4px;
        }
    </style>
</head>
<body>

@php
    $nasabah   = $gadai->nasabah;
    $barang    = $gadai->barang;
    $branch    = $gadai->branch;
    $officer   = $gadai->officer;
    $adminUser = $gadai->admin;
@endphp

{{-- KOP --}}
<div class="kop">
    <div class="kop-logo">
        <img src="{{ public_path('frontend/images/logo.png') }}" alt="Logo BATIM GADAI">
    </div>
    <div class="kop-tengah">
        <div class="nama">BINTANG TIMUR PERGADAIAN</div>
        <div class="nama-pt">(BATIM GADAI)</div>
        <div class="bh">BH : AHU-000979.AH.01.01. TAHUN 2020 &nbsp;|&nbsp; NIB : 0220000871658</div>
        <div class="alamat">{{ $branch->alamat ?? '-' }} &nbsp;|&nbsp; Telp/WA: {{ $branch->no_telp ?? '-' }}</div>
    </div>
    <div class="kop-ojk">
        <div class="ojk-label">Terdaftar di</div>
        <img src="{{ public_path('frontend/images/ojk.png') }}" alt="OJK">
    </div>
</div>

{{-- JUDUL --}}
<div class="judul-sbg">
    @if($sbg->tipe === 'perpanjangan')
    <h2>SURAT BUKTI PERPANJANGAN GADAI</h2>
    @elseif($sbg->tipe === 'pelunasan')
    <h2>SURAT BUKTI PELUNASAN GADAI</h2>
    @else
    <h2>SURAT BUKTI GADAI (SBG)</h2>
    @endif
    <p style="font-size:10px; margin-top:3px; color:#555;">
        Tipe: {{ strtoupper($sbg->tipe) }} |
        Tgl Transaksi: {{ $sbg->tgl_transaksi->format('d M Y') }}
    </p>
</div>

{{-- 2 KOLOM --}}
<div class="two-col">

    {{-- KIRI --}}
    <div class="col-left">

        <div class="form-row">
            <div class="form-label">Nomor SBG</div>
            <div class="form-sep">:</div>
            <div class="form-value">
                <div class="form-line" style="font-size:13px; letter-spacing:1px; color:#1F5C3A;">
                    {{ $gadai->no_sbg }}
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-label">Nama NASABAH</div>
            <div class="form-sep">:</div>
            <div class="form-value">
                <div class="form-line">{{ $nasabah->nama ?? '-' }}</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-label">No. KTP / SIM</div>
            <div class="form-sep">:</div>
            <div class="form-value">
                <div class="form-line">{{ $nasabah->no_ktp ?? '-' }}</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-label">No. CIF</div>
            <div class="form-sep">:</div>
            <div class="form-value">
                <div class="form-line">{{ $nasabah->no_cif ?? '-' }}</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-label">No. HP</div>
            <div class="form-sep">:</div>
            <div class="form-value">
                <div class="form-line">{{ $nasabah->no_hp ?? '-' }}</div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-label">Alamat Domisili</div>
            <div class="form-sep">:</div>
            <div class="form-value">
                <div class="form-line-tall">{{ $nasabah->alamat ?? '-' }}</div>
            </div>
        </div>

        <div class="form-row" style="margin-top:5px;">
            <div class="form-label">Keterangan Barang Jaminan</div>
            <div class="form-sep">:</div>
            <div class="form-value">
                <div class="form-line-tall">
                    {{ $barang->nama_barang ?? '-' }},
                    {{ $barang ? ucfirst(str_replace('_', ' ', $barang->kategori)) : '' }},
                    Merk: {{ $barang->merk ?? '-' }},
                    Tipe: {{ $barang->tipe_model ?? '-' }},
                    Kondisi: {{ $barang ? ucfirst(str_replace('_', ' ', $barang->kondisi)) : '-' }},
                    Kelengkapan: {{ $barang->kelengkapan ?? '-' }}
                </div>
            </div>
        </div>

        {{-- Rincian --}}
        <div class="rincian">
            <div class="rincian-row">
                <div class="rincian-label">Taksiran Awal (Range Petugas)</div>
                <div class="rincian-value">
                    Rp {{ number_format($gadai->nilai_taksiran_min ?? 0, 0, ',', '.') }} –
                    Rp {{ number_format($gadai->nilai_taksiran_max ?? 0, 0, ',', '.') }}
                </div>
            </div>
            <div class="rincian-row">
                <div class="rincian-label">Uang Pinjaman</div>
                <div class="rincian-value">Rp {{ number_format($gadai->nilai_pinjaman ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="rincian-row">
                <div class="rincian-label">Biaya Jasa ({{ number_format($gadai->jasa_persen ?? 5, 0) }}%)</div>
                <div class="rincian-value">Rp {{ number_format($gadai->jasa_nominal ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="rincian-row">
                <div class="rincian-label">Tanggal Pinjaman</div>
                <div class="rincian-value">{{ $gadai->tgl_gadai ? $gadai->tgl_gadai->format('d M Y') : '-' }}</div>
            </div>
            <div class="rincian-row rincian-highlight">
                <div class="rincian-label">Tanggal Jatuh Tempo</div>
                <div class="rincian-value">{{ $gadai->tgl_jatuh_tempo ? $gadai->tgl_jatuh_tempo->format('d M Y') : '-' }}</div>
            </div>
            <div class="rincian-row rincian-total">
                <div class="rincian-label">TOTAL TEBUS</div>
                <div class="rincian-value">Rp {{ number_format($gadai->total_tebus ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>

        {{-- Jam Buka --}}
        <div class="info-box">
            <div class="jam-title">BUKA JAM</div>
            <div class="jam-val">07.00 – 17.00 WIB</div>
            <div class="jam-note">HARI MINGGU LIBUR &nbsp;|&nbsp; HARI LIBUR NASIONAL</div>
            <div class="tutup">TUTUP</div>
        </div>

        {{-- Persetujuan --}}
        <div class="persetujuan-box">
            <p>Dengan menandatangani Surat Bukti Gadai ini, kedua belah pihak sepakat dan setuju untuk tunduk dan mematuhi seluruh ketentuan sebagaimana tertera di Perjanjian Pergadaian yang tercantum.</p>
        </div>

        {{-- TTD --}}
        <div class="ttd-section">
            <div class="ttd-row">
                <div class="ttd-cell">
                    <div class="ttd-label">NASABAH</div>
                    <div class="ttd-sub">&nbsp;</div>
                    <div class="ttd-space"></div>
                    <div class="ttd-nama">{{ $nasabah->nama ?? '................................' }}</div>
                    <div class="ttd-jabatan">Nama Terang</div>
                </div>
                <div class="ttd-cell">
                    <div class="ttd-label">BINTANG TIMUR PERGADAIAN</div>
                    <div class="ttd-sub">Pimpinan Cabang / Outlet</div>
                    <div class="ttd-space"></div>
                    <div class="ttd-nama">{{ $adminUser->nama ?? '................................' }}</div>
                    <div class="ttd-jabatan">Pimpinan Cabang {{ $branch->nama ?? '' }}</div>
                </div>
            </div>
        </div>

    </div>

    {{-- KANAN: Perjanjian --}}
    <div class="col-right">

        <div class="perjanjian-title">PERJANJIAN PERGADAIAN</div>

        <div class="perjanjian-intro">
            Kami yang bertandatangan Kepala Cabang/Outlet bertindak untuk dan atas nama
            <strong>BINTANG TIMUR PERGADAIAN</strong> dengan <strong>NASABAH</strong>
            sepakat untuk membuat dan menandatangani Perjanjian Pegadaian
            (Selanjutnya disebut Perjanjian) dengan ketentuan sebagai berikut:
        </div>

        <ul class="perjanjian-list">
            <li>NASABAH menerima dan setuju terhadap aturan BINTANG TIMUR PERGADAIAN, penetapan taksiran barang jaminan, penetapan uang pinjaman, penetapan tarif jasa pinjaman yang tertera pada Surat Bukti Gadai (SBG).</li>
            <li>Barang yang diserahkan sebagai barang jaminan adalah milik sah NASABAH sesuai hukum yang berlaku dan bukan berasal dari hasil kejahatan, tidak dalam objek sengketa dan/atau sita jaminan.</li>
            <li>NASABAH menyatakan telah berhutang kepada BINTANG TIMUR PERGADAIAN dan berkewajiban untuk membayar uang pinjaman dan membayar jasa pinjaman pada saat pelunasan atau pada saat perpanjangan (Gadai Ulang).</li>
            <li>Tarif jasa pinjaman dihitung per 15 (lima belas) hari, 1 (satu) sampai 15 (lima belas) hari dihitung sama dengan setengah bulan, lewat dari 15 (lima belas) hari sampai 30 (tiga puluh) hari dihitung 1 (satu) bulan.</li>
            <li>Jangka waktu pinjaman adalah 1 (satu) bulan, apabila sampai dengan jatuh tempo tidak dilakukan pelunasan/perpanjangan (Gadai Ulang), maka BINTANG TIMUR PERGADAIAN akan memberikan waktu 60 (enam puluh) hari untuk masa tunggu sebelum barang jaminan dijual, sesuai peraturan OJK No. 31/POJK.05/2016 pasal 24 ayat 4.</li>
            <li>BINTANG TIMUR PERGADAIAN akan memberi ganti kerugian apabila barang jaminan mengalami kehilangan yang tidak disebabkan oleh bencana alam (force majeur), santunan rugi diberikan sebesar 1x jumlah pinjaman.</li>
            <li>Untuk kerusakan yang terjadi pada masa gadai (30 hari), BINTANG TIMUR PERGADAIAN akan mengganti maksimal Rp. 200.000,- (Dua Ratus Ribu Rupiah) untuk biaya service/perbaikan. Jika lewat dari tanggal jatuh tempo, BINTANG TIMUR PERGADAIAN tidak bertanggung jawab atas kerusakan yang terjadi.</li>
            <li>NASABAH dapat melakukan gadai ulang dan minta tambahan uang pinjaman selama nilai taksiran masih memenuhi syarat, sesuai ketentuan yang berlaku di BINTANG TIMUR PERGADAIAN.</li>
            <li>NASABAH menyatakan telah meng-copy/backup data-data pada barang jaminan elektronik yang digadaikan. Jika terjadi kerusakan dan data hilang/rusak, BINTANG TIMUR PERGADAIAN tidak bertanggung jawab atas data tersebut.</li>
            <li>Pengumuman jatuh tempo diingatkan pada saat awal transaksi dan di MADING (Papan Pengumuman) BINTANG TIMUR PERGADAIAN (tidak melalui SMS, Telepon dan Surat menyurat).</li>
            <li>Apabila NASABAH melanggar ketentuan dalam perjanjian ini maka NASABAH wajib: (1) mengganti setiap kerugian yang timbul akibat pelanggaran tersebut, (2) membebaskan BINTANG TIMUR PERGADAIAN dari segala akibat hukum, apabila timbul akibat hukum maka akan diambil alih dan menjadi tanggung jawab NASABAH.</li>
            <li>NASABAH menyatakan tunduk dan patuh mengikuti segala ketentuan peraturan di BINTANG TIMUR PERGADAIAN sepanjang yang menyangkut Perjanjian Pegadaian.</li>
            <li>Apabila terjadi perselisihan di kemudian hari, akan diselesaikan secara musyawarah dan kekeluargaan, dan apabila tidak tercapai kesepakatan akan diselesaikan melalui lembaga alternatif penyelesaian sengketa di bidang usaha pergadaian.</li>
        </ul>

        <div class="perjanjian-footer">
            Demikian Perjanjian Pergadaian ini berlaku dan mengikat <strong>BINTANG TIMUR PERGADAIAN</strong>
            dengan <strong>NASABAH</strong> sejak Surat Bukti Gadai (SBG) ini ditandatangani oleh kedua belah
            pihak pada kolom tersedia.
        </div>

    </div>

</div>

{{-- FOOTER QR --}}
<div class="bottom-bar">
    <div class="bottom-left">
        <div class="verify-title">Verifikasi Keaslian SBG</div>
        <div class="verify-text">
            Scan QR Code untuk memverifikasi keaslian dokumen ini secara digital.<br>
            No SBG: <strong>{{ $gadai->no_sbg }}</strong> &nbsp;|&nbsp;
            Cabang: <strong>{{ $branch->nama ?? '-' }}</strong> &nbsp;|&nbsp;
            Dicetak: {{ now()->format('d M Y, H:i') }} WIB
        </div>
        <div class="verify-url">{{ $verifyUrl }}</div>
        <div class="footer-note">
            Dokumen ini diterbitkan resmi oleh sistem BATIM GADAI — PT Bintang Timur. BATIM GADAI © {{ now()->format('Y') }}
        </div>
    </div>
    <div class="bottom-right">
        <img src="{{ $qrBase64 }}" alt="QR Verifikasi">
        <p>Scan untuk verifikasi</p>
    </div>
</div>

</body>
</html>
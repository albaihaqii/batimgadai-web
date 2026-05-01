<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Approval Gadai BATIM GADAI</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #1F5C3A; padding-bottom: 10px; }
        .header h1 { font-size: 16px; color: #1F5C3A; font-weight: bold; }
        .header p { font-size: 10px; color: #666; margin-top: 3px; }
        .info { margin-bottom: 12px; font-size: 10px; color: #555; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background-color: #1F5C3A; color: white; }
        thead th { padding: 8px 6px; text-align: left; font-size: 10px; font-weight: 600; }
        tbody tr:nth-child(even) { background-color: #f5faf5; }
        tbody tr:nth-child(odd) { background-color: #ffffff; }
        tbody td { padding: 6px; font-size: 10px; border-bottom: 1px solid #e5e7eb; }
        .badge { padding: 2px 6px; border-radius: 10px; font-size: 9px; }
        .badge-menunggu { background-color: #fef3c7; color: #d97706; }
        .badge-aktif { background-color: #dcfce7; color: #16a34a; }
        .badge-ditolak { background-color: #fee2e2; color: #dc2626; }
        .footer { margin-top: 16px; font-size: 9px; color: #999; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BATIM GADAI — Sistem Informasi Gadai Elektronik</h1>
        <p>Data Approval Pengajuan Gadai - Dicetak pada {{ now()->format('d M Y, H:i') }} WIB</p>
    </div>

    <div class="info">
        Total Data: <strong>{{ $pengajuan->count() }} pengajuan</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:4%">No</th>
                <th style="width:16%">Nasabah</th>
                <th style="width:10%">No CIF</th>
                <th style="width:18%">Barang</th>
                <th style="width:12%">Taksiran Awal</th>
                <th style="width:12%">Petugas</th>
                <th style="width:10%">Tgl Pengajuan</th>
                <th style="width:10%">Cabang</th>
                <th style="width:8%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengajuan as $index => $gadai)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $gadai->nasabah->nama ?? '-' }}</td>
                <td>{{ $gadai->nasabah->no_cif ?? '-' }}</td>
                <td>{{ $gadai->barang->nama_barang ?? '-' }}</td>
                <td>Rp {{ number_format($gadai->nilai_taksiran_awal, 0, ',', '.') }}</td>
                <td>{{ $gadai->officer->nama ?? '-' }}</td>
                <td>{{ $gadai->created_at->format('d M Y') }}</td>
                <td>{{ $gadai->branch->nama ?? '-' }}</td>
                <td>
                    @php
                        $badgeClass = match($gadai->status) {
                            'menunggu_approval' => 'badge-menunggu',
                            'aktif'             => 'badge-aktif',
                            'ditolak'           => 'badge-ditolak',
                            default             => 'badge-menunggu',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">
                        {{ ucfirst(str_replace('_', ' ', $gadai->status)) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        BATIM GADAI © {{ now()->format('Y') }} - Dokumen ini digenerate otomatis oleh sistem
    </div>
</body>
</html>
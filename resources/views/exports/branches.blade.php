<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Cabang BATIM GADAI</title>
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
        .badge-aktif { background-color: #dcfce7; color: #16a34a; padding: 2px 6px; border-radius: 10px; font-size: 9px; }
        .badge-nonaktif { background-color: #fee2e2; color: #dc2626; padding: 2px 6px; border-radius: 10px; font-size: 9px; }
        .footer { margin-top: 16px; font-size: 9px; color: #999; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BATIM GADAI - Sistem Informasi Gadai Elektronik</h1>
        <p>Data Cabang - Dicetak pada {{ now()->format('d M Y, H:i') }} WIB</p>
    </div>

    <div class="info">
        Total Data: <strong>{{ $branches->count() }} cabang</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:10%">Kode</th>
                <th style="width:25%">Nama Cabang</th>
                <th style="width:40%">Alamat</th>
                <th style="width:12%">No Telepon</th>
                <th style="width:8%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($branches as $index => $branch)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $branch->kode }}</td>
                <td>{{ $branch->nama }}</td>
                <td>{{ $branch->alamat ?? '-' }}</td>
                <td>{{ $branch->no_telp ?? '-' }}</td>
                <td>
                    <span class="{{ $branch->status === 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                        {{ ucfirst($branch->status) }}
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
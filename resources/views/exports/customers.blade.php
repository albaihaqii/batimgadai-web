<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Nasabah BATIM GADAI</title>
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
        <p>Data Nasabah - Dicetak pada {{ now()->format('d M Y, H:i') }} WIB</p>
    </div>

    <div class="info">
        Total Data: <strong>{{ $customers->count() }} nasabah</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:4%">No</th>
                <th style="width:12%">No CIF</th>
                <th style="width:20%">Nama Nasabah</th>
                <th style="width:16%">No KTP</th>
                <th style="width:13%">No HP</th>
                <th style="width:18%">Cabang</th>
                <th style="width:11%">Tgl Bergabung</th>
                <th style="width:6%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $index => $customer)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $customer->no_cif }}</td>
                <td>{{ $customer->nama }}</td>
                <td>{{ $customer->no_ktp }}</td>
                <td>{{ $customer->no_hp }}</td>
                <td>{{ $customer->branch->nama ?? '-' }}</td>
                <td>{{ $customer->tgl_bergabung->format('d M Y') }}</td>
                <td>
                    <span class="{{ $customer->status === 'aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                        {{ ucfirst($customer->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        BATIM GADAI © {{ now()->format('Y') }} — Dokumen ini digenerate otomatis oleh sistem
    </div>
</body>
</html>
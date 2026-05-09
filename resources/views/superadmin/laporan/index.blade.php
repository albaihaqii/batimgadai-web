@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">
                Laporan
                @if ($type === 'harian')
                    Harian
                @elseif ($type === 'mingguan')
                    Mingguan
                @else
                    Bulanan
                @endif
            </h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Lihat data transaksi dan unduh laporan Excel.
            </p>
        </div>
    </div>

    <div class="min-w-0 overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]"
        style="min-width: 0; overflow: hidden;">

        {{-- Header: Filter + Export --}}
        <div class="flex flex-col gap-4 px-6 pt-5 pb-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-wrap items-center gap-3">
                @if ($type === 'harian')
                    <form action="{{ route($routePrefix . '.laporan.harian') }}" method="GET"
                        class="flex flex-wrap items-center gap-3">
                        <input name="date" type="date" value="{{ $date }}"
                            class="h-11 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-theme-xs focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" />
                        <button type="submit"
                            class="inline-flex h-11 items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="stroke-current">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Tampilkan
                        </button>
                    </form>
                @elseif ($type === 'mingguan')
                    <form action="{{ route($routePrefix . '.laporan.mingguan') }}" method="GET"
                        class="flex flex-wrap items-center gap-3">
                        <label class="text-sm font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">
                            Minggu mulai:
                        </label>
                        <input name="week_start" type="date" value="{{ $weekStart }}"
                            class="h-11 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-theme-xs focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" />
                        <button type="submit"
                            class="inline-flex h-11 items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="stroke-current">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Tampilkan
                        </button>
                    </form>
                @elseif ($type === 'bulanan')
                    <form action="{{ route($routePrefix . '.laporan.bulanan') }}" method="GET"
                        class="flex flex-wrap items-center gap-3">
                        <input name="month" type="month" value="{{ $month }}"
                            class="h-11 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-theme-xs focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" />
                        <button type="submit"
                            class="inline-flex h-11 items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" class="stroke-current">
                                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Tampilkan
                        </button>
                    </form>
                @endif
            </div>

            {{-- Tombol Export --}}
            @if ($type === 'harian')
                <a href="{{ route($routePrefix . '.laporan.harian.export', ['date' => $date]) }}"
                    class="inline-flex h-11 items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none" class="stroke-current">
                        <path d="M10 2.5V13.5M10 13.5L6 9.5M10 13.5L14 9.5M3 15.5H17" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Export Excel
                </a>
            @elseif ($type === 'mingguan')
                <a href="{{ route($routePrefix . '.laporan.mingguan.export', ['week_start' => $weekStart]) }}"
                    class="inline-flex h-11 items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none" class="stroke-current">
                        <path d="M10 2.5V13.5M10 13.5L6 9.5M10 13.5L14 9.5M3 15.5H17" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Export Excel
                </a>
            @elseif ($type === 'bulanan')
                <a href="{{ route($routePrefix . '.laporan.bulanan.export', ['month' => $month]) }}"
                    class="inline-flex h-11 items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none" class="stroke-current">
                        <path d="M10 2.5V13.5M10 13.5L6 9.5M10 13.5L14 9.5M3 15.5H17" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Export Excel
                </a>
            @endif
        </div>

        {{-- Summary Cards --}}
        @if (isset($summary))
            <div class="grid grid-cols-2 gap-3 px-6 pb-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7">
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-3 dark:border-gray-800 dark:bg-gray-900/50">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total UP</p>
                    <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                        Rp {{ number_format($summary['total_up'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="rounded-xl border border-red-100 bg-red-50 p-3 dark:border-red-500/20 dark:bg-red-500/5">
                    <p class="text-xs text-red-500 dark:text-red-400">Uang Keluar</p>
                    <p class="mt-1 text-sm font-semibold text-red-600 dark:text-red-400">
                        Rp {{ number_format($summary['cash_out'], 0, ',', '.') }}
                    </p>
                </div>
                <div
                    class="rounded-xl border border-green-100 bg-green-50 p-3 dark:border-green-500/20 dark:bg-green-500/5">
                    <p class="text-xs text-green-500 dark:text-green-400">Uang Masuk</p>
                    <p class="mt-1 text-sm font-semibold text-green-600 dark:text-green-400">
                        Rp {{ number_format($summary['cash_in'], 0, ',', '.') }}
                    </p>
                </div>
                <div
                    class="rounded-xl border p-3
                    {{ $summary['net_cash_flow'] >= 0
                        ? 'border-blue-100 bg-blue-50 dark:border-blue-500/20 dark:bg-blue-500/5'
                        : 'border-orange-100 bg-orange-50 dark:border-orange-500/20 dark:bg-orange-500/5' }}">
                    <p class="text-xs {{ $summary['net_cash_flow'] >= 0 ? 'text-blue-500' : 'text-orange-500' }}">
                        Net Cash Flow
                    </p>
                    <p
                        class="mt-1 text-sm font-semibold
                        {{ $summary['net_cash_flow'] >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-orange-600 dark:text-orange-400' }}">
                        Rp {{ number_format($summary['net_cash_flow'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-3 dark:border-gray-800 dark:bg-gray-900/50">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Sewa Modal</p>
                    <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                        Rp {{ number_format($summary['total_sewa_modal'], 0, ',', '.') }}
                    </p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-3 dark:border-gray-800 dark:bg-gray-900/50">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Admin</p>
                    <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white/90">
                        Rp {{ number_format($summary['total_admin'], 0, ',', '.') }}
                    </p>
                </div>
                <div
                    class="rounded-xl border border-purple-100 bg-purple-50 p-3 dark:border-purple-500/20 dark:bg-purple-500/5">
                    <p class="text-xs text-purple-500 dark:text-purple-400">Nasabah Aktif</p>
                    <p class="mt-1 text-sm font-semibold text-purple-600 dark:text-purple-400">
                        {{ $summary['active_customers'] }}
                    </p>
                </div>
            </div>
        @endif

        {{-- Period Label --}}
        @if (isset($periodLabel))
            <div class="px-6 pb-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Periode: <span class="font-semibold text-gray-800 dark:text-white/90">{{ $periodLabel }}</span>
                    <span class="ml-2 text-gray-400">•</span>
                    <span class="ml-2">{{ isset($records) ? $records->count() : 0 }} transaksi</span>
                </p>
            </div>
        @endif

        {{-- Table --}}
        <div class="custom-scrollbar max-w-full overflow-x-auto" style="max-width: 100%; overflow-x: auto;">
            <table class="w-full min-w-[1500px]" style="min-width: 1500px;">
                <thead class="border-y border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No</th>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Tgl
                            Gadai</th>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No. SBG
                        </th>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nasabah
                        </th>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Cabang
                        </th>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Barang
                            Jaminan</th>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Taksiran
                        </th>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Uang
                            Keluar</th>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Uang
                            Masuk</th>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Saldo
                            Cash Flow</th>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status
                        </th>
                    </tr>
                </thead>
                {{-- <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @if (isset($records) && $records->count() > 0)
                        @php
                            $statusConfig = [
                                'menunggu_approval' =>
                                    'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
                                'disetujui' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                                'aktif' =>
                                    'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                                'ditolak' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                                'jatuh_tempo' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                                'perpanjangan' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                                'lunas' => 'bg-gray-100 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
                                'lelang' => 'bg-purple-50 text-purple-600 dark:bg-purple-500/15 dark:text-purple-400',
                            ];

                            $statusLabel = [
                                'menunggu_approval' => 'Menunggu Approval',
                                'disetujui' => 'Disetujui',
                                'aktif' => 'Aktif',
                                'ditolak' => 'Ditolak',
                                'jatuh_tempo' => 'Jatuh Tempo',
                                'perpanjangan' => 'Perpanjangan',
                                'lunas' => 'Lunas',
                                'lelang' => 'Lelang',
                            ];
                        @endphp

                        @foreach ($records as $index => $gadai)
                            @php
                                $cashOut = (float) ($gadai->nilai_pinjaman ?? 0);

                                // collect() agar aman: null → collect([]), hasOne → collect([model]), hasMany → normal
                                $pelunasan = $gadai->pelunasan;

                                if ($pelunasan instanceof \Illuminate\Database\Eloquent\Collection) {
                                    $cashIn = (float) $pelunasan->where('status_bayar', 'berhasil')->sum('total_tebus');
                                } else {
                                    $cashIn =
                                        $pelunasan && $pelunasan->status_bayar === 'berhasil'
                                            ? (float) $pelunasan->total_tebus
                                            : 0;
                                }

                                $rowCashFlow = $cashIn - $cashOut;

                                $taksiran =
                                    (float) ($gadai->nilai_taksiran_akhir ??
                                        ($gadai->nilai_taksiran_max ?? ($gadai->nilai_taksiran_min ?? 0)));
                            @endphp

                            <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                                <td class="px-5 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-5 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ $gadai->tgl_gadai ? \Carbon\Carbon::parse($gadai->tgl_gadai)->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="font-medium text-theme-sm text-gray-800 dark:text-white/90">
                                        {{ $gadai->no_sbg ?: '-' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-theme-sm font-medium text-gray-800 dark:text-white/90">
                                        {{ $gadai->nasabah?->nama ?? '-' }}
                                    </p>
                                    @if ($gadai->nasabah?->no_cif)
                                        <p class="text-xs text-gray-400">{{ $gadai->nasabah->no_cif }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                                    {{ $gadai->branch?->nama ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="text-theme-sm text-gray-800 dark:text-white/90">
                                        {{ $gadai->barang?->nama_barang ?? '-' }}
                                    </p>
                                    @if ($gadai->barang?->kategori)
                                        <p class="text-xs text-gray-400 capitalize">
                                            {{ str_replace('_', ' ', $gadai->barang->kategori) }}
                                        </p>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    Rp {{ number_format($taksiran, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="text-theme-sm font-medium text-red-600 dark:text-red-400">
                                        Rp {{ number_format($cashOut, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="text-theme-sm font-medium text-green-600 dark:text-green-400">
                                        Rp {{ number_format($cashIn, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span
                                        class="text-theme-sm font-semibold
                                        {{ $rowCashFlow >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-orange-600 dark:text-orange-400' }}">
                                        Rp {{ number_format($rowCashFlow, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span
                                        class="rounded-full px-2 py-0.5 text-theme-xs font-medium
                                        {{ $statusConfig[$gadai->status] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $statusLabel[$gadai->status] ?? ucfirst(str_replace('_', ' ', $gadai->status ?? '-')) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="11" class="px-6 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                                Tidak ada data transaksi untuk periode ini.
                            </td>
                        </tr>
                    @endif
                </tbody> --}}
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @php
                        $statusConfig = [
                            'menunggu_approval' =>
                                'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
                            'disetujui' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                            'aktif' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                            'ditolak' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                            'jatuh_tempo' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                            'perpanjangan' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                            'lunas' => 'bg-gray-100 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
                            'lelang' => 'bg-purple-50 text-purple-600 dark:bg-purple-500/15 dark:text-purple-400',
                        ];

                        $statusLabel = [
                            'menunggu_approval' => 'Menunggu Approval',
                            'disetujui' => 'Disetujui',
                            'aktif' => 'Aktif',
                            'ditolak' => 'Ditolak',
                            'jatuh_tempo' => 'Jatuh Tempo',
                            'perpanjangan' => 'Perpanjangan',
                            'lunas' => 'Lunas',
                            'lelang' => 'Lelang',
                        ];
                    @endphp

                    @forelse ($records as $trx)
                        @php
                            // Karena di controller menggunakan (object), akses menggunakan ->
                            $status = $trx->status ?? '';
                            $cashOut = (float) ($trx->cash_out ?? 0);
                            $cashIn = (float) ($trx->cash_in ?? 0);
                            $rowCashFlow = (float) ($trx->row_cash_flow ?? 0); // Diubah dari cash_flow ke row_cash_flow
                            $taksiran = (float) ($trx->taksiran ?? 0);
                            $jenis = $trx->jenis_transaksi ?? '-'; // Diubah dari jenis ke jenis_transaksi
                        @endphp

                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            {{-- No --}}
                            <td class="px-5 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                                {{ $loop->iteration }}
                            </td>

                            {{-- Tanggal --}}
                            <td class="whitespace-nowrap px-5 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                                {{ !empty($trx->tanggal) ? \Carbon\Carbon::parse($trx->tanggal)->format('d/m/Y') : '-' }}
                            </td>

                            {{-- No SBG --}}
                            <td class="px-5 py-3.5">
                                <span class="font-medium text-theme-sm text-gray-800 dark:text-white/90">
                                    {{ $trx->no_sbg ?? '-' }}
                                </span>
                            </td>

                            {{-- Nasabah --}}
                            <td class="px-5 py-3.5">
                                <p class="text-theme-sm font-medium text-gray-800 dark:text-white/90">
                                    {{-- Mengasumsikan model Nasabah punya property 'nama' --}}
                                    {{ $trx->nasabah->nama ?? '-' }}
                                </p>
                            </td>

                            {{-- Cabang --}}
                            <td class="px-5 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                                {{-- Mengasumsikan model Branch punya property 'nama' --}}
                                {{ $trx->branch->nama ?? '-' }}
                            </td>

                            {{-- Barang --}}
                            <td class="px-5 py-3.5">
                                <p class="text-theme-sm text-gray-800 dark:text-white/90">
                                    {{-- Mengasumsikan model Barang punya property 'nama_barang' atau 'merk' --}}
                                    {{ $trx->barang->nama_barang ?? '-' }}
                                </p>

                                <p class="mt-1">
                                    @if ($jenis === 'gadai_baru')
                                        <span
                                            class="rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-600 dark:bg-red-500/15 dark:text-red-400">
                                            Gadai Baru
                                        </span>
                                    @elseif ($jenis === 'pelunasan')
                                        <span
                                            class="rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-600 dark:bg-green-500/15 dark:text-green-400">
                                            Pelunasan
                                        </span>
                                    @elseif ($jenis === 'perpanjangan')
                                        <span
                                            class="rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">
                                            Perpanjangan
                                        </span>
                                    @endif
                                </p>
                            </td>

                            {{-- Taksiran --}}
                            <td class="whitespace-nowrap px-5 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                                Rp {{ number_format($taksiran, 0, ',', '.') }}
                            </td>

                            {{-- Uang Keluar --}}
                            <td class="whitespace-nowrap px-5 py-3.5">
                                <span class="text-theme-sm font-medium text-red-600 dark:text-red-400">
                                    Rp {{ number_format($cashOut, 0, ',', '.') }}
                                </span>
                            </td>

                            {{-- Uang Masuk --}}
                            <td class="whitespace-nowrap px-5 py-3.5">
                                <span class="text-theme-sm font-medium text-green-600 dark:text-green-400">
                                    Rp {{ number_format($cashIn, 0, ',', '.') }}
                                </span>
                            </td>

                            {{-- Cash Flow --}}
                            <td class="whitespace-nowrap px-5 py-3.5">
                                <span
                                    class="text-theme-sm font-semibold {{ $rowCashFlow >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-orange-600 dark:text-orange-400' }}">
                                    Rp {{ number_format($rowCashFlow, 0, ',', '.') }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-3.5">
                                <span
                                    class="rounded-full px-2 py-0.5 text-theme-xs font-medium {{ $statusConfig[$status] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400' }}">
                                    {{ $statusLabel[$status] ?? ucfirst(str_replace('_', ' ', $status ?: '-')) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-6 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                                Tidak ada data transaksi untuk periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Total {{ isset($records) ? $records->count() : 0 }} transaksi
            </p>
        </div>
    </div>
@endsection

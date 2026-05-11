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
                <a href="{{ route($routePrefix . '.laporan.harian.export', array_merge(['date' => $date], request()->query())) }}"
                    class="inline-flex h-11 items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none" class="stroke-current">
                        <path d="M10 2.5V13.5M10 13.5L6 9.5M10 13.5L14 9.5M3 15.5H17" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Export Excel
                </a>
            @elseif ($type === 'mingguan')
                <a href="{{ route($routePrefix . '.laporan.mingguan.export', array_merge(['week_start' => $weekStart], request()->query())) }}"
                    class="inline-flex h-11 items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none" class="stroke-current">
                        <path d="M10 2.5V13.5M10 13.5L6 9.5M10 13.5L14 9.5M3 15.5H17" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Export Excel
                </a>
            @elseif ($type === 'bulanan')
                <a href="{{ route($routePrefix . '.laporan.bulanan.export', array_merge(['month' => $month], request()->query())) }}"
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

        {{-- Filter --}}
        <div class="px-6 mb-3">
            <form method="GET"
                action="@if ($type === 'harian') {{ route($routePrefix . '.laporan.harian') }}@elseif ($type === 'mingguan'){{ route($routePrefix . '.laporan.mingguan') }}@else{{ route($routePrefix . '.laporan.bulanan') }} @endif"
                class="flex flex-wrap items-center gap-3">
                @if ($type === 'harian')
                    <input type="hidden" name="date" value="{{ $date }}">
                @elseif ($type === 'mingguan')
                    <input type="hidden" name="week_start" value="{{ $weekStart }}">
                @else
                    <input type="hidden" name="month" value="{{ $month }}">
                @endif

                @if (auth()->user()->role === 'superadmin')
                    <div class="relative">
                        <select name="cabang_id" onchange="this.form.submit()"
                            class="h-9 rounded-lg border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                            <option value="">Semua Cabang</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ $selectedCabangId == $branch->id ? 'selected' : '' }}>{{ $branch->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <select name="status" onchange="this.form.submit()"
                    class="h-9 rounded-lg border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ $selectedStatus == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="lunas" {{ $selectedStatus == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="perpanjangan" {{ $selectedStatus == 'perpanjangan' ? 'selected' : '' }}>Perpanjangan
                    </option>
                    <option value="gadai_baru" {{ $selectedStatus == 'gadai_baru' ? 'selected' : '' }}>Gadai Baru</option>
                    <option value="pelunasan" {{ $selectedStatus == 'pelunasan' ? 'selected' : '' }}>Pelunasan</option>
                </select>

                <select name="per_page" onchange="this.form.submit()"
                    class="h-9 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    @foreach ([10, 20, 50] as $val)
                        <option value="{{ $val }}" {{ request('per_page', 10) == $val ? 'selected' : '' }}>
                            {{ $val }} data</option>
                    @endforeach
                </select>

                @if (request()->hasAny(['cabang_id', 'status']))
                    <a href="@if ($type === 'harian') {{ route($routePrefix . '.laporan.harian', ['date' => $date]) }}@elseif ($type === 'mingguan'){{ route($routePrefix . '.laporan.mingguan', ['week_start' => $weekStart]) }}@else{{ route($routePrefix . '.laporan.bulanan', ['month' => $month]) }} @endif"
                        class="h-9 inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Period Label --}}
        @if (isset($periodLabel))
            <div class="px-6 pb-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Periode: <span class="font-semibold text-gray-800 dark:text-white/90">{{ $periodLabel }}</span>
                    <span class="ml-2 text-gray-400">•</span>
                    <span class="ml-2">{{ isset($records) ? $records->total() : 0 }} transaksi</span>
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
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Jenis
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @php
                        $jenisList = [
                            'gadai_baru' => [
                                'label' => 'Gadai Baru',
                                'color' => 'bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-400',
                            ],
                            'pelunasan' => [
                                'label' => 'Pelunasan',
                                'color' => 'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-400',
                            ],
                            'perpanjangan' => [
                                'label' => 'Perpanjangan',
                                'color' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                            ],
                        ];
                    @endphp

                    @forelse ($records as $trx)
                        @php
                            $status = $trx->status ?? '';
                            $cashOut = (float) ($trx->cash_out ?? 0);
                            $cashIn = (float) ($trx->cash_in ?? 0);
                            $rowCashFlow = (float) ($trx->row_cash_flow ?? 0);
                            $taksiran = (float) ($trx->taksiran ?? 0);
                            $jenis = $trx->jenis_transaksi ?? '-';
                        @endphp

                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            {{-- No --}}
                            <td class="px-5 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                                {{ $records->firstItem() + $loop->index }}
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
                                    {{ $trx->nasabah?->nama ?? '-' }}
                                </p>
                                @if ($trx->nasabah?->no_cif)
                                    <p class="text-xs text-gray-400">{{ $trx->nasabah->no_cif }}</p>
                                @endif
                            </td>

                            {{-- Cabang --}}
                            <td class="px-5 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                                {{ $trx->branch?->nama ?? '-' }}
                            </td>

                            {{-- Barang --}}
                            <td class="px-5 py-3.5">
                                <p class="text-theme-sm text-gray-800 dark:text-white/90">
                                    {{ $trx->barang?->nama_barang ?? '-' }}
                                </p>
                                @if ($trx->barang?->kategori)
                                    <p class="text-xs text-gray-400 capitalize">
                                        {{ str_replace('_', ' ', $trx->barang->kategori) }}
                                    </p>
                                @endif
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

                            {{-- Jenis --}}
                            <td class="px-5 py-3.5">
                                @if (isset($jenisList[$jenis]))
                                    <span
                                        class="rounded-full px-2 py-0.5 text-theme-xs font-medium {{ $jenisList[$jenis]['color'] }}">
                                        {{ $jenisList[$jenis]['label'] }}
                                    </span>
                                @else
                                    <span
                                        class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400">
                                        {{ ucfirst(str_replace('_', ' ', $jenis)) }}
                                    </span>
                                @endif
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

        {{-- Footer dengan Pagination --}}
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex flex-col gap-4">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Total {{ $records->total() }} transaksi
            </p>

            @if ($records->hasPages())
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        @if ($records->onFirstPage())
                            <span
                                class="h-9 px-3 py-2 rounded-lg border border-gray-300 text-sm text-gray-400 bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-600 cursor-not-allowed">
                                ← Sebelumnya
                            </span>
                        @else
                            <a href="{{ $records->previousPageUrl() }}"
                                class="h-9 px-3 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                ← Sebelumnya
                            </a>
                        @endif

                        <div class="flex items-center gap-1">
                            @if ($records->currentPage() > 2)
                                <a href="{{ $records->url(1) }}"
                                    class="h-9 w-9 flex items-center justify-center rounded-lg border border-gray-300 text-sm text-gray-700 bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">1</a>
                                @if ($records->currentPage() > 3)
                                    <span class="text-gray-400">...</span>
                                @endif
                            @endif

                            @foreach ($records->getUrlRange(max($records->currentPage() - 1, 1), min($records->currentPage() + 1, $records->lastPage())) as $page => $url)
                                @if ($page == $records->currentPage())
                                    <span
                                        class="h-9 w-9 flex items-center justify-center rounded-lg border border-brand-500 bg-brand-50 text-sm font-medium text-brand-600 dark:border-brand-500 dark:bg-brand-500/15 dark:text-brand-400">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="h-9 w-9 flex items-center justify-center rounded-lg border border-gray-300 text-sm text-gray-700 bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if ($records->currentPage() < $records->lastPage() - 1)
                                @if ($records->currentPage() < $records->lastPage() - 2)
                                    <span class="text-gray-400">...</span>
                                @endif
                                <a href="{{ $records->url($records->lastPage()) }}"
                                    class="h-9 w-9 flex items-center justify-center rounded-lg border border-gray-300 text-sm text-gray-700 bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">{{ $records->lastPage() }}</a>
                            @endif
                        </div>

                        @if ($records->hasMorePages())
                            <a href="{{ $records->nextPageUrl() }}"
                                class="h-9 px-3 py-2 rounded-lg border border-gray-300 text-sm text-gray-700 bg-white hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                                Selanjutnya →
                            </a>
                        @else
                            <span
                                class="h-9 px-3 py-2 rounded-lg border border-gray-300 text-sm text-gray-400 bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-600 cursor-not-allowed">
                                Selanjutnya →
                            </span>
                        @endif
                    </div>

                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Halaman {{ $records->currentPage() }} dari {{ $records->lastPage() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

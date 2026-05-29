@extends('layouts.app')
@section('content')

{{-- 4 Stat Cards --}}
<div class="grid grid-cols-2 gap-4 md:gap-6 mb-6 xl:grid-cols-4">

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800 mb-4">
            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.5 8.187V17.25C3.5 17.6642 3.83579 18 4.25 18H19.75C20.1642 18 20.5 17.6642 20.5 17.25V8.18747L13.2873 13.2171C12.5141 13.7563 11.4866 13.7563 10.7134 13.2171L3.5 8.187ZM20.5 6.2286V6.24336C20.4976 6.31753 20.4604 6.38643 20.3992 6.42905L12.4293 11.9867C12.1716 12.1664 11.8291 12.1664 11.5713 11.9867L3.60116 6.42885C3.538 6.38481 3.50035 6.31268 3.50032 6.23568C3.50028 6.10553 3.60577 6 3.73592 6H20.2644C20.3922 6 20.4963 6.10171 20.5 6.2286ZM22 17.25C22 18.4926 20.9926 19.5 19.75 19.5H4.25C3.00736 19.5 2 18.4926 2 17.25V6.23398C2.01781 5.25971 2.78812 4.5 3.73592 4.5H20.2644C21.2229 4.5 22 5.27697 22 6.23549V17.25Z" fill=""/>
            </svg>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">Total Pinjaman Baru</p>
        <h4 class="font-bold text-gray-800 text-title-sm dark:text-white/90 mt-1">
            Rp {{ number_format($summary['totalPinjaman'], 0, ',', '.') }}
        </h4>
        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <span class="text-xs text-gray-400 dark:text-gray-500">Dana keluar ke nasabah</span>
            <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500">Keluar</span>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800 mb-4">
            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.25C6.61522 2.25 2.25 6.61522 2.25 12C2.25 17.3848 6.61522 21.75 12 21.75C17.3848 21.75 21.75 17.3848 21.75 12C21.75 6.61522 17.3848 2.25 12 2.25ZM12 5.25C12.4142 5.25 12.75 5.58579 12.75 6V6.31673C14.3804 6.60867 15.75 7.83361 15.75 9.5C15.75 9.91421 15.4142 10.25 15 10.25C14.5858 10.25 14.25 9.91421 14.25 9.5C14.25 8.58007 13.3132 7.75 12 7.75C10.6868 7.75 9.75 8.58007 9.75 9.5C9.75 10.4199 10.6868 11.25 12 11.25C13.9372 11.25 15.75 12.4066 15.75 14.5C15.75 16.1664 14.3804 17.3913 12.75 17.6833V18C12.75 18.4142 12.4142 18.75 12 18.75C11.5858 18.75 11.25 18.4142 11.25 18V17.6833C9.61957 17.3913 8.25 16.1664 8.25 14.5C8.25 14.0858 8.58579 13.75 9 13.75C9.41421 13.75 9.75 14.0858 9.75 14.5C9.75 15.4199 10.6868 16.25 12 16.25C13.3132 16.25 14.25 15.4199 14.25 14.5C14.25 13.5801 13.3132 12.75 12 12.75C10.0628 12.75 8.25 11.5934 8.25 9.5C8.25 7.83361 9.61957 6.60867 11.25 6.31673V6C11.25 5.58579 11.5858 5.25 12 5.25Z" fill=""/>
            </svg>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">Pendapatan Jasa</p>
        <h4 class="font-bold text-gray-800 text-title-sm dark:text-white/90 mt-1">
            Rp {{ number_format($summary['pendapatanJasa'], 0, ',', '.') }}
        </h4>
        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <span class="text-xs text-gray-400 dark:text-gray-500">Keuntungan murni</span>
            <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">Profit</span>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800 mb-4">
            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.665 3.75621C11.8762 3.65064 12.1247 3.65064 12.3358 3.75621L18.7807 6.97856L12.3358 10.2009C12.1247 10.3065 11.8762 10.3065 11.665 10.2009L5.22014 6.97856L11.665 3.75621ZM4.29297 8.19203V16.0946C4.29297 16.3787 4.45347 16.6384 4.70757 16.7654L11.25 20.0366V11.6513C11.1631 11.6205 11.0777 11.5843 10.9942 11.5426L4.29297 8.19203ZM12.75 20.037L19.2933 16.7654C19.5474 16.6384 19.7079 16.3787 19.7079 16.0946V8.19202L13.0066 11.5426C12.9229 11.5844 12.8372 11.6208 12.75 11.6516V20.037ZM13.0066 2.41456C12.3732 2.09786 11.6277 2.09786 10.9942 2.41456L4.03676 5.89319C3.27449 6.27432 2.79297 7.05342 2.79297 7.90566V16.0946C2.79297 16.9469 3.27448 17.726 4.03676 18.1071L10.9942 21.5857C11.6277 21.9024 12.3732 21.9024 13.0066 21.5857L19.9641 18.1071C20.7264 17.726 21.2079 16.9469 21.2079 16.0946V7.90566C21.2079 7.05342 20.7264 6.27432 19.9641 5.89319L13.0066 2.41456Z" fill=""/>
            </svg>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">Total Pelunasan</p>
        <h4 class="font-bold text-gray-800 text-title-sm dark:text-white/90 mt-1">
            Rp {{ number_format($summary['totalPelunasan'], 0, ',', '.') }}
        </h4>
        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <span class="text-xs text-gray-400 dark:text-gray-500">Pokok kembali ke kas</span>
            <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">Masuk</span>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800 mb-4">
            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H18.5001C19.7427 20.75 20.7501 19.7426 20.7501 18.5V5.5C20.7501 4.25736 19.7427 3.25 18.5001 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H18.5001C18.9143 4.75 19.2501 5.08579 19.2501 5.5V18.5C19.2501 18.9142 18.9143 19.25 18.5001 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V5.5ZM6.25005 9.7143C6.25005 9.30008 6.58583 8.9643 7.00005 8.9643L17 8.96429C17.4143 8.96429 17.75 9.30008 17.75 9.71429C17.75 10.1285 17.4143 10.4643 17 10.4643L7.00005 10.4643C6.58583 10.4643 6.25005 10.1285 6.25005 9.7143ZM6.25005 14.2857C6.25005 13.8715 6.58583 13.5357 7.00005 13.5357H12C12.4143 13.5357 12.75 13.8715 12.75 14.2857C12.75 14.6999 12.4143 15.0357 12 15.0357H7.00005C6.58583 15.0357 6.25005 14.6999 6.25005 14.2857Z" fill=""/>
            </svg>
        </div>
        <p class="text-sm text-gray-500 dark:text-gray-400">Jumlah Transaksi</p>
        <h4 class="font-bold text-gray-800 text-title-sm dark:text-white/90 mt-1">
            {{ number_format($summary['jumlahTransaksi']) }}
        </h4>
        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
            <span class="text-xs text-gray-400 dark:text-gray-500">Periode ini</span>
            <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">Total</span>
        </div>
    </div>
</div>

{{-- Main Card --}}
<div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- Header --}}
    <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Laporan {{ $type === 'harian' ? 'Harian' : ($type === 'mingguan' ? 'Mingguan' : 'Bulanan') }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                Periode: <span class="font-medium text-gray-700 dark:text-white/70">{{ $periodLabel }}</span>
            </p>
        </div>
        @if($type === 'harian')
        <a href="{{ route($routePrefix . '.laporan.harian.export', array_merge(['date' => $date], request()->only('cabang_id'))) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M10 2.5V13.5M10 13.5L6 9.5M10 13.5L14 9.5M3 15.5H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Export Excel
        </a>
        @elseif($type === 'mingguan')
        <a href="{{ route($routePrefix . '.laporan.mingguan.export', array_merge(['week_start' => $weekStart], request()->only('cabang_id'))) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M10 2.5V13.5M10 13.5L6 9.5M10 13.5L14 9.5M3 15.5H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Export Excel
        </a>
        @else
        <a href="{{ route($routePrefix . '.laporan.bulanan.export', array_merge(['month' => $month], request()->only('cabang_id'))) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none"><path d="M10 2.5V13.5M10 13.5L6 9.5M10 13.5L14 9.5M3 15.5H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Export Excel
        </a>
        @endif
    </div>

    {{-- Filter --}}
    <div class="px-6 mb-4">
        @php
            $filterAction = $type === 'harian'
                ? route($routePrefix . '.laporan.harian')
                : ($type === 'mingguan'
                    ? route($routePrefix . '.laporan.mingguan')
                    : route($routePrefix . '.laporan.bulanan'));
        @endphp
        <form method="GET" action="{{ $filterAction }}" class="flex flex-wrap items-center gap-3">
            @if($type === 'harian')
            <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()"
                class="h-9 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
            @elseif($type === 'mingguan')
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-500 whitespace-nowrap">Minggu mulai:</span>
                <input type="date" name="week_start" value="{{ $weekStart }}" onchange="this.form.submit()"
                    class="h-9 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
            </div>
            @else
            <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()"
                class="h-9 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
            @endif

            @if($routePrefix === 'superadmin')
            <select name="cabang_id" onchange="this.form.submit()"
                class="h-9 rounded-lg border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                <option value="">Semua Cabang</option>
                @foreach($branches as $branch)
                <option value="{{ $branch->id }}" {{ $selectedCabangId == $branch->id ? 'selected' : '' }}>
                    {{ $branch->nama }}
                </option>
                @endforeach
            </select>
            @endif

            <select name="jenis" onchange="this.form.submit()"
                class="h-9 rounded-lg border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                <option value="">Semua Jenis</option>
                <option value="gadai_baru"   {{ $jenis === 'gadai_baru'   ? 'selected' : '' }}>Gadai Baru</option>
                <option value="perpanjangan" {{ $jenis === 'perpanjangan' ? 'selected' : '' }}>Perpanjangan</option>
                <option value="pelunasan"    {{ $jenis === 'pelunasan'    ? 'selected' : '' }}>Pelunasan</option>
            </select>

            <select name="per_page" onchange="this.form.submit()"
                class="h-9 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                @foreach([10, 20, 50] as $val)
                <option value="{{ $val }}" {{ $perPage == $val ? 'selected' : '' }}>{{ $val }} data</option>
                @endforeach
            </select>

            @if(request()->hasAny(['cabang_id', 'jenis']))
            @php
                $resetUrl = $type === 'harian'
                    ? route($routePrefix . '.laporan.harian', ['date' => $date])
                    : ($type === 'mingguan'
                        ? route($routePrefix . '.laporan.mingguan', ['week_start' => $weekStart])
                        : route($routePrefix . '.laporan.bulanan', ['month' => $month]));
            @endphp
            <a href="{{ $resetUrl }}"
                class="h-9 inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Info --}}
    <div class="px-6 mb-3">
        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $records->total() }} transaksi ditemukan</p>
    </div>

    {{-- Table --}}
    <div class="max-w-full overflow-x-auto">
        <table class="w-full">
            <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                <tr>
                    <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No</th>
                    <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Tanggal</th>
                    <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No SBG</th>
                    <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nasabah</th>
                    <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Cabang</th>
                    <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Barang</th>
                    <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nilai Pinjaman</th>
                    <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nominal Transaksi</th>
                    <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Jenis</th>
                    <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($records as $index => $row)
                @php
                    $jenisConfig = [
                        'gadai_baru'   => ['label' => 'Gadai Baru',   'class' => 'bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-400'],
                        'perpanjangan' => ['label' => 'Perpanjangan', 'class' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400'],
                        'pelunasan'    => ['label' => 'Pelunasan',    'class' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500'],
                    ];
                    $jenisCfg = $jenisConfig[$row['jenis']] ?? ['label' => ucfirst($row['jenis']), 'class' => 'bg-gray-100 text-gray-600'];
                    $tanggal  = $row['tanggal'] instanceof \Carbon\Carbon
                        ? $row['tanggal']
                        : \Carbon\Carbon::parse($row['tanggal']);
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-5 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                        {{ ($records->currentPage() - 1) * $records->perPage() + $index + 1 }}
                    </td>
                    <td class="px-5 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                        {{ $tanggal->format('d M Y') }}
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="font-medium text-theme-sm text-gray-800 dark:text-white/90">{{ $row['no_sbg'] }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-theme-sm text-gray-800 dark:text-white/90">{{ $row['nasabah'] }}</td>
                    <td class="px-5 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">{{ $row['cabang'] }}</td>
                    <td class="px-5 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">{{ $row['barang'] }}</td>
                    <td class="px-5 py-3.5 text-theme-sm font-medium text-gray-800 dark:text-white/90 whitespace-nowrap">
                        Rp {{ number_format($row['nilai_pinjaman'], 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3.5 whitespace-nowrap">
                        <span class="text-theme-sm font-semibold {{ $row['jenis'] === 'gadai_baru' ? 'text-red-600 dark:text-red-400' : 'text-success-600 dark:text-success-500' }}">
                            {{ $row['jenis'] === 'gadai_baru' ? '−' : '+' }}Rp {{ number_format($row['nominal_transaksi'], 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium {{ $jenisCfg['class'] }}">
                            {{ $jenisCfg['label'] }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium
                            {{ in_array($row['status'], ['aktif','berhasil','lunas','perpanjangan'])
                                ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500'
                                : 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400' }}">
                            {{ ucfirst(str_replace('_', ' ', $row['status'])) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="px-6 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                        Tidak ada transaksi pada periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer Pagination --}}
    <x-common.pagination :paginator="$records" />
</div>

@endsection
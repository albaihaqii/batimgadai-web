@extends('layouts.app')
@section('content')
    
@php
    $formatRupiah = fn($value) => 'Rp ' . number_format($value, 0, ',', '.');
    $formatRingkas = function ($value) {
        if ($value >= 1000000000) return 'Rp ' . number_format($value / 1000000000, 1, ',', '.') . ' M';
        if ($value >= 1000000) return 'Rp ' . number_format($value / 1000000, 1, ',', '.') . ' Jt';
        return 'Rp ' . number_format($value, 0, ',', '.');
    };
@endphp

<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Dashboard Admin</h1>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Ringkasan operasional {{ $namaCabang }} tahun {{ now()->year }}</p>
</div>

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6 mb-6">
    @foreach([
        ['label' => 'Total Nasabah', 'value' => number_format($totalNasabah), 'pct' => $pctNasabah, 'icon' => 'users'],
        ['label' => 'Transaksi Aktif', 'value' => number_format($transaksiAktif), 'pct' => $pctAktif, 'icon' => 'box'],
        ['label' => 'Pinjaman Bulan Ini', 'value' => $formatRingkas($totalPinjamanBulanIni), 'pct' => $pctPinjaman, 'icon' => 'mail'],
        ['label' => 'Menunggu Approval', 'value' => number_format($menungguApproval), 'pct' => null, 'icon' => 'doc'],
    ] as $card)
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
            <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none">
                @if($card['icon'] === 'users')
                <path fill-rule="evenodd" clip-rule="evenodd" d="M8.804 5.602a2.197 2.197 0 1 0 0 4.394 2.197 2.197 0 0 0 0-4.394ZM5.107 7.799a3.697 3.697 0 1 1 7.394 0 3.697 3.697 0 0 1-7.394 0Zm-1.3 6.456c1.065-1.054 2.649-1.8 4.943-1.8s3.879.746 4.944 1.8c1.046 1.035 1.527 2.306 1.751 3.265.321 1.377-.837 2.379-2.02 2.379h-9.35c-1.181 0-2.34-1.002-2.019-2.379.224-.959.705-2.23 1.751-3.265Z"/>
                @elseif($card['icon'] === 'box')
                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.665 3.756a.75.75 0 0 1 .671 0l6.445 3.222-6.445 3.223a.75.75 0 0 1-.671 0L5.22 6.978l6.445-3.222ZM4.293 8.192v7.903c0 .284.16.543.415.67l6.542 3.272v-8.386a2.25 2.25 0 0 1-.256-.108L4.293 8.192Zm8.457 11.845 6.543-3.272a.75.75 0 0 0 .415-.67V8.192l-6.701 3.351c-.084.042-.17.078-.257.109v8.385Z"/>
                @elseif($card['icon'] === 'mail')
                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.5 8.187v9.063c0 .414.336.75.75.75h15.5a.75.75 0 0 0 .75-.75V8.187l-7.213 5.03a2.25 2.25 0 0 1-2.574 0L3.5 8.187ZM20.5 6.229a.236.236 0 0 0-.236-.229H3.736a.236.236 0 0 0-.135.429l7.97 5.558c.258.179.6.179.858 0l7.97-5.558a.236.236 0 0 0 .101-.2Z"/>
                @else
                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25h13A2.25 2.25 0 0 1 20.75 5.5v13a2.25 2.25 0 0 1-2.25 2.25h-13a2.25 2.25 0 0 1-2.25-2.25v-13A2.25 2.25 0 0 1 5.5 3.25Zm.75 6.464c0-.414.336-.75.75-.75h10a.75.75 0 0 1 0 1.5H7a.75.75 0 0 1-.75-.75Zm0 4.572c0-.414.336-.75.75-.75h5a.75.75 0 0 1 0 1.5H7a.75.75 0 0 1-.75-.75Z"/>
                @endif
            </svg>
        </div>
        <div class="flex items-end justify-between mt-5">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $card['label'] }}</span>
                <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $card['value'] }}</h4>
            </div>
            @if($card['pct'])
                <span class="rounded-full py-0.5 px-2.5 text-sm font-medium {{ $card['pct']['naik'] ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500' }}">
                    {{ $card['pct']['nilai'] }}%
                </span>
            @elseif($menungguApproval > 0)
                <span class="rounded-full bg-warning-50 py-0.5 px-2.5 text-sm font-medium text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">Pending</span>
            @endif
        </div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-12 gap-4 md:gap-6 mb-6">
    <div class="col-span-12 min-w-0 xl:col-span-8 overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pt-5 sm:px-6 sm:pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="mb-2">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Transaksi per Bulan</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Rekap gadai, perpanjangan, pelunasan, dan jatuh tempo cabang</p>
        </div>
        <div class="min-w-0 max-w-full">
            <div id="chartSix" class="h-[315px] w-full"></div>
        </div>
    </div>

    <div class="col-span-12 min-w-0 xl:col-span-4 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Audit Kas Cabang</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">{{ $namaCabang }}</p>
        <div class="mt-6 space-y-4">
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-800">
                <span class="text-sm text-gray-500 dark:text-gray-400">Uang Keluar</span>
                <span class="font-semibold text-error-600 dark:text-error-500">{{ $formatRingkas($totalUangKeluar) }}</span>
            </div>
            <div class="flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-800">
                <span class="text-sm text-gray-500 dark:text-gray-400">Uang Didapatkan</span>
                <span class="font-semibold text-success-600 dark:text-success-500">{{ $formatRingkas($totalUangMasuk) }}</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500 dark:text-gray-400">Selisih Audit</span>
                <span class="font-bold {{ $saldoAudit >= 0 ? 'text-success-600 dark:text-success-500' : 'text-error-600 dark:text-error-500' }}">{{ $formatRupiah($saldoAudit) }}</span>
            </div>
        </div>
    </div>
</div>

<div class="mb-6 overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pt-5 sm:px-6 sm:pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="mb-2">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Arus Kas Audit</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Stacked area uang keluar dan uang didapatkan per bulan</p>
    </div>
    <div class="min-w-0 max-w-full">
        <div id="chartAuditKas" class="h-[360px] w-full"></div>
    </div>
</div>

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Pengajuan Gadai Terbaru</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Menunggu persetujuan admin cabang</p>
        </div>
        <a href="{{ route('admin.approval.gadai') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">Lihat Semua</a>
    </div>
    <div class="max-w-full overflow-hidden">
        <table class="w-full table-fixed">
            <thead>
                <tr class="border-t border-gray-100 dark:border-gray-800">
                    <th class="py-3 text-left pr-3"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nasabah</p></th>
                    <th class="py-3 text-left pr-3"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Barang</p></th>
                    <th class="py-3 text-left pr-3"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Taksiran</p></th>
                    <th class="py-3 text-left"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p></th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuanTerbaru as $g)
                <tr class="border-t border-gray-100 dark:border-gray-800">
                    <td class="py-3 pr-3 align-top"><p class="break-words text-gray-500 text-theme-sm dark:text-gray-400">{{ $g->nasabah->nama ?? '-' }}</p></td>
                    <td class="py-3 pr-3 align-top"><p class="break-words text-gray-500 text-theme-sm dark:text-gray-400">{{ $g->barang->nama_barang ?? '-' }}</p></td>
                    <td class="py-3 pr-3 align-top"><p class="break-words text-gray-500 text-theme-sm dark:text-gray-400">{{ $formatRupiah($g->nilai_taksiran_min) }}</p></td>
                    <td class="py-3 align-top"><span class="inline-flex rounded-full px-2 py-0.5 text-theme-xs font-medium bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">Menunggu</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-8 text-center text-sm text-gray-400">Tidak ada pengajuan menunggu approval</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    window.__bulanData = @json($bulan);
    window.__auditKas = @json($auditKas);
</script>
@endpush

@endsection

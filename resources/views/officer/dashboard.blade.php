@extends('layouts.app')
@section('content')

@php
    $formatRupiah = fn($value) => 'Rp ' . number_format($value, 0, ',', '.');
    $statusClass = [
        'menunggu_approval' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
        'aktif' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
        'jatuh_tempo' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
        'perpanjangan' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
        'lunas' => 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400',
        'ditolak' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
    ];
@endphp

<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
    <div>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $namaCabang }}</p>
        <h1 class="mt-1 text-2xl font-bold text-gray-800 dark:text-white">Dashboard Officer</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Meja kerja input nasabah dan transaksi harian.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('officer.nasabah.create') }}" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white shadow-theme-xs hover:bg-brand-600">Tambah Nasabah</a>
        <a href="{{ route('officer.transaksi.gadai.create') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">Input Gadai</a>
    </div>
</div>

<div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6">
    @foreach([
        ['label' => 'Nasabah Baru Hari Ini', 'value' => $nasabahHariIni, 'hint' => 'Data customer yang kamu input', 'tone' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400'],
        ['label' => 'Pengajuan Gadai', 'value' => $pengajuanHariIni, 'hint' => 'Pengajuan yang kamu kirim hari ini', 'tone' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400'],
        ['label' => 'Perpanjangan', 'value' => $perpanjanganHariIni, 'hint' => 'Transaksi perpanjangan hari ini', 'tone' => 'bg-violet-50 text-violet-700 dark:bg-violet-500/10 dark:text-violet-400'],
        ['label' => 'Pelunasan', 'value' => $pelunasanHariIni, 'hint' => 'Transaksi pelunasan hari ini', 'tone' => 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400'],
    ] as $card)
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-start justify-between">
            <div class="rounded-xl px-3 py-2 text-sm font-semibold {{ $card['tone'] }}">{{ $card['label'] }}</div>
            <span class="text-2xl font-bold text-gray-800 dark:text-white/90">{{ number_format($card['value']) }}</span>
        </div>
        <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">{{ $card['hint'] }}</p>
    </div>
    @endforeach
</div>

<div class="mb-6 grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12 min-w-0 xl:col-span-7 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
        <div class="mb-5 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Flow Kerja Officer</h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Urutan kerja paling sering di loket.</p>
            </div>
            <span class="rounded-full bg-gray-50 px-3 py-1 text-xs font-medium text-gray-500 dark:bg-gray-900 dark:text-gray-400">{{ $lokerKosong }} loker kosong</span>
        </div>

        <div class="grid gap-3 sm:grid-cols-2">
            <a href="{{ route('officer.nasabah.create') }}" class="group rounded-xl border border-gray-200 p-4 transition hover:border-brand-300 hover:bg-brand-50/40 dark:border-gray-800 dark:hover:border-brand-800 dark:hover:bg-brand-500/5">
                <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">1</div>
                <h3 class="font-semibold text-gray-800 dark:text-white/90">Daftarkan nasabah</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Input identitas dan cabang sebelum transaksi.</p>
            </a>
            <a href="{{ route('officer.transaksi.gadai.create') }}" class="group rounded-xl border border-gray-200 p-4 transition hover:border-brand-300 hover:bg-brand-50/40 dark:border-gray-800 dark:hover:border-brand-800 dark:hover:bg-brand-500/5">
                <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">2</div>
                <h3 class="font-semibold text-gray-800 dark:text-white/90">Ajukan gadai</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Isi barang, foto, dan nilai taksiran awal.</p>
            </a>
            <a href="{{ route('officer.transaksi.gadai') }}" class="group rounded-xl border border-gray-200 p-4 transition hover:border-brand-300 hover:bg-brand-50/40 dark:border-gray-800 dark:hover:border-brand-800 dark:hover:bg-brand-500/5">
                <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">3</div>
                <h3 class="font-semibold text-gray-800 dark:text-white/90">Pantau status</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Cek pengajuan menunggu, aktif, atau jatuh tempo.</p>
            </a>
            <a href="{{ route('officer.transaksi.pelunasan') }}" class="group rounded-xl border border-gray-200 p-4 transition hover:border-brand-300 hover:bg-brand-50/40 dark:border-gray-800 dark:hover:border-brand-800 dark:hover:bg-brand-500/5">
                <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400">4</div>
                <h3 class="font-semibold text-gray-800 dark:text-white/90">Proses pembayaran</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perpanjangan atau pelunasan dari data gadai aktif.</p>
            </a>
        </div>
    </div>

    <div class="col-span-12 min-w-0 xl:col-span-5 rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Ringkasan Cabang</h2>
        <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Status operasional yang perlu kamu tahu.</p>
        <div class="mt-5 space-y-3">
            <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3 dark:bg-gray-900">
                <span class="text-sm text-gray-500 dark:text-gray-400">Transaksi aktif cabang</span>
                <span class="font-semibold text-gray-800 dark:text-white/90">{{ number_format($transaksiAktif) }}</span>
            </div>
            <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3 dark:bg-gray-900">
                <span class="text-sm text-gray-500 dark:text-gray-400">Menunggu approval</span>
                <span class="font-semibold text-warning-600 dark:text-orange-400">{{ number_format($menungguApproval) }}</span>
            </div>
            <div class="flex items-center justify-between rounded-xl bg-gray-50 px-4 py-3 dark:bg-gray-900">
                <span class="text-sm text-gray-500 dark:text-gray-400">Total nasabah cabang</span>
                <span class="font-semibold text-gray-800 dark:text-white/90">{{ number_format($totalNasabah) }}</span>
            </div>
        </div>
    </div>
</div>

<div class="mb-6 grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12 min-w-0 xl:col-span-7 overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
        <div class="mb-4 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Siap Diproses</h2>
                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Gadai aktif yang bisa diperpanjang atau dilunasi.</p>
            </div>
            <a href="{{ route('officer.transaksi.gadai') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700 dark:text-brand-400">Lihat data</a>
        </div>
        <div class="max-w-full overflow-hidden">
            <table class="w-full table-fixed">
                <thead>
                    <tr class="border-t border-gray-100 dark:border-gray-800">
                        <th class="py-3 pr-3 text-left"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No SBG</p></th>
                        <th class="py-3 pr-3 text-left"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nasabah</p></th>
                        <th class="py-3 pr-3 text-left"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tempo</p></th>
                        <th class="py-3 text-left"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aksi</p></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gadaiSiapTransaksi as $g)
                    <tr class="border-t border-gray-100 dark:border-gray-800">
                        <td class="py-3 pr-3 align-top"><p class="break-words font-medium text-gray-800 text-theme-sm dark:text-white/90">{{ $g->no_sbg ?? '-' }}</p></td>
                        <td class="py-3 pr-3 align-top"><p class="break-words text-gray-500 text-theme-sm dark:text-gray-400">{{ $g->nasabah->nama ?? '-' }}</p></td>
                        <td class="py-3 pr-3 align-top"><p class="break-words text-gray-500 text-theme-sm dark:text-gray-400">{{ $g->tgl_jatuh_tempo ? $g->tgl_jatuh_tempo->format('d M Y') : '-' }}</p></td>
                        <td class="py-3 align-top">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('officer.transaksi.perpanjangan.create', ['gadai_id' => $g->id]) }}" class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300">Perpanjang</a>
                                <a href="{{ route('officer.transaksi.pelunasan.create', ['gadai_id' => $g->id]) }}" class="rounded-lg bg-brand-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-brand-600">Lunasi</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-8 text-center text-sm text-gray-400">Belum ada gadai aktif yang bisa diproses.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-span-12 min-w-0 xl:col-span-5 overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
        <div class="mb-4">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">Aktivitas Input Terakhir</h2>
            <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">Pengajuan gadai yang kamu input.</p>
        </div>
        <div class="space-y-3">
            @forelse($aktivitasOfficer as $g)
            <div class="rounded-xl border border-gray-100 p-4 dark:border-gray-800">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="font-medium text-gray-800 dark:text-white/90">{{ $g->nasabah->nama ?? '-' }}</p>
                        <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">{{ $g->barang->nama_barang ?? '-' }}</p>
                    </div>
                    <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $statusClass[$g->status] ?? 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }}">
                        {{ str_replace('_', ' ', $g->status) }}
                    </span>
                </div>
                <div class="mt-3 flex items-center justify-between text-xs text-gray-400">
                    <span>{{ $g->created_at?->format('d M Y H:i') }}</span>
                    <a href="{{ route('officer.transaksi.gadai.show', $g->id) }}" class="font-semibold text-brand-600 hover:text-brand-700 dark:text-brand-400">Detail</a>
                </div>
            </div>
            @empty
            <div class="py-8 text-center text-sm text-gray-400">Belum ada aktivitas input.</div>
            @endforelse
        </div>
    </div>
</div>

@endsection

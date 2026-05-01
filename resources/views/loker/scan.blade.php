<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loker {{ $locker->kode_loker }} — BATIM GADAI</title>
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="min-h-full bg-gray-50 dark:bg-gray-900 flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden shadow-xl">

        {{-- Header Card --}}
        <div class="px-6 py-6 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="{{ asset('frontend/images/logo.png') }}" alt="BATIM GADAI" class="h-9 w-auto">
                <div>
                    <h1 class="text-base font-bold text-gray-800 dark:text-white">BATIM GADAI</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Sistem Informasi Gadai Elektronik</p>
                </div>
            </div>
            <span class="rounded-full px-3 py-1 text-xs font-semibold
                {{ $locker->status === 'kosong'
                    ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500'
                    : 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400' }}">
                {{ ucfirst($locker->status) }}
            </span>
        </div>

        {{-- Info Loker --}}
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Kode Loker</p>
                    <p class="text-lg font-bold text-gray-800 dark:text-white mt-0.5">{{ $locker->kode_loker }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Cabang</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white mt-0.5">{{ $locker->branch->nama ?? '-' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Rak</p>
                    <p class="text-sm font-semibold text-gray-800 dark:text-white mt-0.5">{{ $locker->rak }}</p>
                </div>
            </div>
            @if($locker->keterangan)
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 italic">{{ $locker->keterangan }}</p>
            @endif
        </div>

        {{-- Content --}}
        <div class="p-6">
            @if($locker->status === 'kosong')
                {{-- Loker Kosong --}}
                <div class="text-center py-6">
                    <div class="flex items-center justify-center w-16 h-16 rounded-full bg-success-50 dark:bg-success-500/10 mx-auto mb-4">
                        <svg class="text-success-500" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white mb-1">Loker Tersedia</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Loker ini sedang tidak digunakan</p>
                </div>

            @else
                {{-- Loker Terisi --}}
                @php
                    $gadai = $locker->gadai;
                @endphp

                @if($gadai)
                <div>
                    <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-4">Informasi Gadai</p>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2.5 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">No SBG</span>
                            <span class="text-sm font-bold text-gray-800 dark:text-white">{{ $gadai->no_sbg ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2.5 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Nasabah</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-white">{{ $gadai->nasabah->nama ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2.5 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">No CIF</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $gadai->nasabah->no_cif ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2.5 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Barang</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white text-right max-w-[180px]">{{ $gadai->barang->nama_barang ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2.5 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Kategori</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white">
                                {{ $gadai->barang ? ucfirst(str_replace('_', ' ', $gadai->barang->kategori)) : '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2.5 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Nilai Pinjaman</span>
                            <span class="text-sm font-bold text-brand-500">
                                Rp {{ number_format($gadai->nilai_pinjaman ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2.5 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Tgl Gadai</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white">
                                {{ $gadai->tgl_gadai ? $gadai->tgl_gadai->format('d M Y') : '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2.5 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Jatuh Tempo</span>
                            <span class="text-sm font-semibold {{ $gadai->tgl_jatuh_tempo && $gadai->tgl_jatuh_tempo->isPast() ? 'text-error-600' : 'text-gray-800 dark:text-white' }}">
                                {{ $gadai->tgl_jatuh_tempo ? $gadai->tgl_jatuh_tempo->format('d M Y') : '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2.5">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Status Gadai</span>
                            @php
                                $statusConfig = [
                                    'aktif'        => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                                    'jatuh_tempo'  => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                                    'perpanjangan' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                                ];
                                $statusClass = $statusConfig[$gadai->status] ?? 'bg-gray-100 text-gray-600';
                            @endphp
                            <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium {{ $statusClass }}">
                                {{ ucfirst(str_replace('_', ' ', $gadai->status)) }}
                            </span>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-6">
                    <p class="text-sm text-gray-400">Data gadai tidak ditemukan</p>
                </div>
                @endif
            @endif
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 text-center bg-gray-50 dark:bg-gray-800/50">
            <p class="text-xs text-gray-400 dark:text-gray-500">
                Dipindai pada {{ now()->format('d M Y, H:i') }} WIB
            </p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                BATIM GADAI © {{ now()->format('Y') }} — PT Bintang Timur
            </p>
        </div>

    </div>
</div>

</body>
</html>
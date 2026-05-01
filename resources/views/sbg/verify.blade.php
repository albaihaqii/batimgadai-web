<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi SBG — BATIM GADAI</title>
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
                    <p class="text-xs text-gray-500 dark:text-gray-400">Verifikasi Surat Bukti Gadai</p>
                </div>
            </div>
            @if($sbg)
            <span class="rounded-full px-3 py-1 text-xs font-semibold bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">
                Terverifikasi
            </span>
            @else
            <span class="rounded-full px-3 py-1 text-xs font-semibold bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500">
                Tidak Valid
            </span>
            @endif
        </div>

        @if($sbg)
        {{-- Info SBG --}}
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">No SBG</p>
                    <p class="text-lg font-bold text-gray-800 dark:text-white mt-0.5">{{ $sbg->no_sbg }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Tipe</p>
                    <span class="inline-block mt-0.5 rounded-full px-2 py-0.5 text-xs font-semibold bg-brand-50 text-brand-500 dark:bg-brand-500/10">
                        {{ strtoupper($sbg->tipe) }}
                    </span>
                </div>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                Tanggal transaksi: {{ $sbg->tgl_transaksi->format('d M Y') }}
            </p>
        </div>

        {{-- Status Valid --}}
        <div class="px-6 py-4 bg-success-50 dark:bg-success-500/10 border-b border-success-100 dark:border-success-500/20 flex items-center gap-3">
            <div class="flex items-center justify-center w-9 h-9 rounded-full bg-success-100 dark:bg-success-500/20 flex-shrink-0">
                <svg class="text-success-600 dark:text-success-500" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-success-700 dark:text-success-500">Dokumen Resmi Terverifikasi</p>
                <p class="text-xs text-success-600 dark:text-success-400 mt-0.5">SBG ini adalah dokumen sah yang diterbitkan BATIM GADAI</p>
            </div>
        </div>

        {{-- Detail --}}
        <div class="p-6 space-y-5">

            {{-- Data Nasabah --}}
            <div>
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Data Nasabah</p>
                <div class="space-y-2.5">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Nama</span>
                        <span class="text-sm font-semibold text-gray-800 dark:text-white">{{ $sbg->nasabah->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm text-gray-500 dark:text-gray-400">No CIF</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $sbg->nasabah->no_cif ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm text-gray-500 dark:text-gray-400">No KTP</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $sbg->nasabah->no_ktp ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Data Barang --}}
            <div>
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Data Barang</p>
                <div class="space-y-2.5">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Barang</span>
                        <span class="text-sm font-semibold text-gray-800 dark:text-white text-right max-w-[180px]">{{ $sbg->gadai->barang->nama_barang ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Kategori</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white">
                            {{ $sbg->gadai->barang ? ucfirst(str_replace('_', ' ', $sbg->gadai->barang->kategori)) : '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Merk / Model</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white">
                            {{ $sbg->gadai->barang->merk ?? '-' }} {{ $sbg->gadai->barang->tipe_model ?? '' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Data Transaksi --}}
            <div>
                <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider mb-3">Data Transaksi</p>
                <div class="space-y-2.5">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Cabang</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $sbg->gadai->branch->nama ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Nilai Pinjaman</span>
                        <span class="text-sm font-bold text-brand-500">
                            Rp {{ number_format($sbg->gadai->nilai_pinjaman ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Jasa (5%)</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white">
                            Rp {{ number_format($sbg->gadai->jasa_nominal ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Total Tebus</span>
                        <span class="text-sm font-bold text-gray-800 dark:text-white">
                            Rp {{ number_format($sbg->gadai->total_tebus ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Tgl Gadai</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white">
                            {{ $sbg->gadai->tgl_gadai ? $sbg->gadai->tgl_gadai->format('d M Y') : '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Jatuh Tempo</span>
                        <span class="text-sm font-semibold {{ $sbg->gadai->tgl_jatuh_tempo && $sbg->gadai->tgl_jatuh_tempo->isPast() ? 'text-error-600' : 'text-gray-800 dark:text-white' }}">
                            {{ $sbg->gadai->tgl_jatuh_tempo ? $sbg->gadai->tgl_jatuh_tempo->format('d M Y') : '-' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Status Gadai</span>
                        @php
                            $statusConfig = [
                                'aktif'        => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                                'jatuh_tempo'  => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                                'perpanjangan' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                                'lunas'        => 'bg-gray-100 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
                            ];
                            $statusClass = $statusConfig[$sbg->gadai->status] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium {{ $statusClass }}">
                            {{ ucfirst(str_replace('_', ' ', $sbg->gadai->status ?? '-')) }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        @else
        {{-- SBG Tidak Valid --}}
        <div class="px-6 py-4 bg-error-50 dark:bg-error-500/10 border-b border-error-100 dark:border-error-500/20 flex items-center gap-3">
            <div class="flex items-center justify-center w-9 h-9 rounded-full bg-error-100 dark:bg-error-500/20 flex-shrink-0">
                <svg class="text-error-600 dark:text-error-500" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-error-700 dark:text-error-500">Dokumen Tidak Valid</p>
                <p class="text-xs text-error-600 dark:text-error-400 mt-0.5">SBG ini tidak ditemukan atau tidak valid dalam sistem</p>
            </div>
        </div>
        <div class="p-6 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Dokumen yang Anda scan tidak terdaftar dalam sistem BATIM GADAI. Kemungkinan dokumen ini palsu atau sudah tidak berlaku.
            </p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-3">
                Hubungi cabang BATIM GADAI terdekat untuk informasi lebih lanjut.
            </p>
        </div>
        @endif

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 text-center bg-gray-50 dark:bg-gray-800/50">
            <p class="text-xs text-gray-400 dark:text-gray-500">
                Diverifikasi pada {{ now()->format('d M Y, H:i') }} WIB
            </p>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                BATIM GADAI © {{ now()->format('Y') }} — PT Bintang Timur
            </p>
        </div>

    </div>
</div>

</body>
</html>
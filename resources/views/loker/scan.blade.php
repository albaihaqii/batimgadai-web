<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Loker {{ $locker->kode_loker }} — BATIM GADAI</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}?v=2">
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="min-h-full bg-gray-50 dark:bg-gray-900 flex items-center justify-center p-4">

<div class="w-full max-w-md">

    {{-- Header --}}
    <div class="text-center mb-6">
        <img src="{{ asset('favicon-192.png') }}" alt="BATIM GADAI" class="w-16 h-16 mx-auto mb-3 rounded-2xl">
        <h1 class="text-xl font-bold text-gray-800 dark:text-white">BATIM GADAI</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Sistem Informasi Gadai Elektronik</p>
    </div>

    {{-- Card Loker --}}
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 overflow-hidden">

        {{-- Loker Info Header --}}
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-800 dark:text-white">{{ $locker->kode_loker }}</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $locker->branch->nama ?? '-' }} - Rak {{ $locker->rak }}</p>
            </div>
            <span class="rounded-full px-3 py-1 text-sm font-semibold
                {{ $locker->status === 'kosong'
                    ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500'
                    : 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400' }}">
                {{ ucfirst($locker->status) }}
            </span>
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
                    @if($locker->keterangan)
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-3 italic">{{ $locker->keterangan }}</p>
                    @endif
                </div>

            @else
                {{-- Loker Terisi --}}
                @php $gadai = \App\Models\Locker::with(['gadai.nasabah', 'gadai.barang'])->find($locker->id); @endphp

                <div class="space-y-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="text-brand-500" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                        </svg>
                        <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Informasi Gadai</span>
                    </div>

                    @if($locker->gadai_id)
                    @php $gadaiData = \App\Models\Locker::find($locker->id)->gadai ?? null; @endphp
                    @if($gadaiData)
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">No SBG</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-white">{{ $gadaiData->no_sbg ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Nasabah</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $gadaiData->nasabah->nama ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">No CIF</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $gadaiData->nasabah->no_cif ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Barang</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $gadaiData->barang->nama_barang ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Nilai Pinjaman</span>
                            <span class="text-sm font-semibold text-gray-800 dark:text-white">Rp {{ number_format($gadaiData->nilai_pinjaman ?? 0, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Tgl Gadai</span>
                            <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $gadaiData->tgl_gadai ? \Carbon\Carbon::parse($gadaiData->tgl_gadai)->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Jatuh Tempo</span>
                            <span class="text-sm font-semibold {{ $gadaiData->tgl_jatuh_tempo && \Carbon\Carbon::parse($gadaiData->tgl_jatuh_tempo)->isPast() ? 'text-red-500' : 'text-gray-800 dark:text-white' }}">
                                {{ $gadaiData->tgl_jatuh_tempo ? \Carbon\Carbon::parse($gadaiData->tgl_jatuh_tempo)->format('d M Y') : '-' }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                            <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">
                                {{ ucfirst(str_replace('_', ' ', $gadaiData->status ?? '-')) }}
                            </span>
                        </div>
                    </div>
                    @endif
                    @else
                    <p class="text-sm text-gray-400 text-center py-4">Data gadai tidak ditemukan</p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 text-center">
            <p class="text-xs text-gray-400 dark:text-gray-500">
                BATIM GADAI © {{ now()->format('Y') }} - Scan dilakukan pada {{ now()->format('d M Y, H:i') }} WIB
            </p>
        </div>
    </div>

</div>

</body>
</html>
@extends('layouts.app')
@section('content')

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400">
    {{ session('success') }}
</div>
@endif

{{-- 3 Stat Cards --}}
<div class="grid grid-cols-3 gap-4 md:gap-6 mb-6">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800 flex-shrink-0">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2.75C9.10051 2.75 6.75 5.10051 6.75 8V8.91722C6.75 9.22088 6.6526 9.51676 6.47188 9.76076L4.89453 11.9211C3.84701 13.3328 4.76599 15.3565 6.50559 15.5223C8.49687 15.7089 10.4956 15.8055 12.5 15.8055C14.5044 15.8055 16.5031 15.7089 18.4944 15.5223C20.234 15.3565 21.153 13.3328 20.1055 11.9211L18.5281 9.76076C18.3474 9.51676 18.25 9.22088 18.25 8.91722V8C18.25 5.10051 15.8995 2.75 13 2.75H12ZM9.75 19C9.75 18.5858 10.0858 18.25 10.5 18.25H14.5C14.9142 18.25 15.25 18.5858 15.25 19C15.25 20.2426 14.2426 21.25 13 21.25H12C10.7574 21.25 9.75 20.2426 9.75 19Z" fill=""/></svg>
            </div>
            <div class="flex-1">
                <span class="text-sm text-gray-500 dark:text-gray-400">Menunggu Proses</span>
                <div class="flex items-end justify-between mt-1">
                    <h4 class="font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $totalProses }}</h4>
                    <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">Proses</span>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800 flex-shrink-0">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" fill="currentColor"/></svg>
            </div>
            <div class="flex-1">
                <span class="text-sm text-gray-500 dark:text-gray-400">Selesai Terjual</span>
                <div class="flex items-end justify-between mt-1">
                    <h4 class="font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $totalSelesai }}</h4>
                    <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">Selesai</span>
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800 flex-shrink-0">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M6.21967 7.28131C5.92678 6.98841 5.92678 6.51354 6.21967 6.22065C6.51256 5.92775 6.98744 5.92775 7.28033 6.22065L11.999 10.9393L16.7176 6.22078C17.0105 5.92789 17.4854 5.92788 17.7782 6.22078C18.0711 6.51367 18.0711 6.98855 17.7782 7.28144L13.0597 12L17.7782 16.7186C18.0711 17.0115 18.0711 17.4863 17.7782 17.7792C17.4854 18.0721 17.0105 18.0721 16.7176 17.7792L11.999 13.0607L7.28033 17.7794C6.98744 18.0722 6.51256 18.0722 6.21967 17.7794C5.92678 17.4865 5.92678 17.0116 6.21967 16.7187L10.9384 12L6.21967 7.28131Z" fill="currentColor"/></svg>
            </div>
            <div class="flex-1">
                <span class="text-sm text-gray-500 dark:text-gray-400">Dibatalkan</span>
                <div class="flex items-end justify-between mt-1">
                    <h4 class="font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $totalBatal }}</h4>
                    <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500">Batal</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- Header --}}
    <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Manajemen Lelang</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Barang gadai yang melewati batas waktu 120 hari</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="px-6 mb-4">
        <form method="GET" action="{{ route('superadmin.lelang') }}" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ $search }}"
                placeholder="Cari No SBG atau Nasabah..."
                class="h-9 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 w-56">

            <select name="status" onchange="this.form.submit()"
                class="h-9 rounded-lg border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                <option value="">Semua Status</option>
                <option value="proses"  {{ $status === 'proses'  ? 'selected' : '' }}>Proses</option>
                <option value="selesai" {{ $status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="batal"   {{ $status === 'batal'   ? 'selected' : '' }}>Batal</option>
            </select>

            <select name="per_page" onchange="this.form.submit()"
                class="h-9 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                @foreach([10, 20, 50] as $val)
                <option value="{{ $val }}" {{ $perPage == $val ? 'selected' : '' }}>{{ $val }} data</option>
                @endforeach
            </select>

            @if($search)
            <button type="submit"
                class="h-9 inline-flex items-center gap-1.5 rounded-lg bg-brand-500 px-4 text-sm font-medium text-white hover:bg-brand-600">
                Cari
            </button>
            @endif

            @if($search || $status)
            <a href="{{ route('superadmin.lelang') }}"
                class="h-9 inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="max-w-full overflow-x-auto">
        <table class="w-full">
            <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No SBG</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nasabah</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Cabang</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Barang</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Tgl JT</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Sisa Hutang</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Harga Terjual</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Selisih</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($lelang as $index => $item)
                @php
                    $statusConfig = [
                        'proses'  => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
                        'selesai' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                        'batal'   => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                    ];
                    $selisihConfig = [
                        'lebih'  => ['label' => 'Lebih', 'class' => 'text-success-600 dark:text-success-500'],
                        'kurang' => ['label' => 'Kurang', 'class' => 'text-error-600 dark:text-error-500'],
                        'pas'    => ['label' => 'Pas', 'class' => 'text-gray-600 dark:text-gray-400'],
                    ];
                @endphp
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                        {{ $lelang->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="font-medium text-theme-sm text-gray-800 dark:text-white/90">{{ $item->no_sbg }}</span>
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm text-gray-800 dark:text-white/90">
                        {{ $item->nasabah->nama ?? '-' }}
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                        {{ $item->gadai->branch->nama ?? '-' }}
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                        {{ $item->gadai->barang->nama_barang ?? '-' }}
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                        {{ $item->tgl_jatuh_tempo->format('d M Y') }}
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm font-medium text-gray-800 dark:text-white/90 whitespace-nowrap">
                        Rp {{ number_format($item->sisa_hutang, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                        {{ $item->harga_terjual ? 'Rp ' . number_format($item->harga_terjual, 0, ',', '.') : '-' }}
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm whitespace-nowrap">
                        @if($item->selisih !== null && $item->status_selisih)
                        @php $sc = $selisihConfig[$item->status_selisih] ?? ['label' => '-', 'class' => ''] @endphp
                        <span class="font-medium {{ $sc['class'] }}">
                            {{ $sc['label'] }} Rp {{ number_format($item->selisih, 0, ',', '.') }}
                        </span>
                        @else
                        <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium {{ $statusConfig[$item->status] ?? '' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('superadmin.lelang.show', $item->id) }}"
                                class="text-gray-400 hover:text-brand-500 dark:hover:text-brand-400 transition-colors">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </a>
                            @if($item->status === 'proses')
                            <button onclick="openProsesModal({{ $item->id }}, '{{ $item->no_sbg }}', {{ $item->sisa_hutang }})"
                                class="text-gray-400 hover:text-success-500 dark:hover:text-success-400 transition-colors">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/></svg>
                            </button>
                            <button onclick="openBatalModal({{ $item->id }}, '{{ $item->no_sbg }}')"
                                class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="px-6 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                        Belum ada data lelang
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Menampilkan {{ $lelang->firstItem() ?? 0 }}–{{ $lelang->lastItem() ?? 0 }} dari {{ $lelang->total() }} data
        </p>
        <div class="flex items-center gap-2">
            <a href="{{ $lelang->previousPageUrl() }}"
                class="flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 {{ $lelang->onFirstPage() ? 'opacity-40 pointer-events-none' : '' }}">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z" fill="currentColor"/></svg>
                <span class="hidden sm:inline">Sebelumnya</span>
            </a>
            @foreach($lelang->getUrlRange(1, $lelang->lastPage()) as $page => $url)
            <a href="{{ $url }}"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-theme-sm font-medium transition-colors {{ $page == $lelang->currentPage() ? 'bg-brand-500 text-white' : 'text-gray-700 hover:bg-brand-500 hover:text-white dark:text-gray-400' }}">
                {{ $page }}
            </a>
            @endforeach
            <a href="{{ $lelang->nextPageUrl() }}"
                class="flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 {{ !$lelang->hasMorePages() ? 'opacity-40 pointer-events-none' : '' }}">
                <span class="hidden sm:inline">Selanjutnya</span>
                <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z" fill="currentColor"/></svg>
            </a>
        </div>
    </div>
</div>

{{-- Modal Proses Lelang --}}
<div id="modalProses" class="hidden fixed inset-0 z-99999 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div>
                <h4 class="text-base font-semibold text-gray-800 dark:text-white">Proses Lelang</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5" id="prosesSubtitle"></p>
            </div>
            <button onclick="document.getElementById('modalProses').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="formProses" method="POST" action="">
            @csrf
            <div class="p-6 space-y-4">
                <div id="infoSisaHutang" class="rounded-xl bg-gray-50 dark:bg-gray-800 p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sisa Hutang Nasabah</p>
                    <p class="text-lg font-bold text-gray-800 dark:text-white mt-1" id="nilaiSisaHutang"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Tanggal Lelang <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tgl_lelang" required
                        value="{{ today()->toDateString() }}"
                        class="w-full h-11 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Harga Terjual (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="harga_terjual" id="inputHargaTerjual" required min="0"
                        placeholder="Masukkan harga terjual"
                        oninput="hitungSelisih()"
                        class="w-full h-11 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                </div>
                <div id="previewSelisih" class="hidden rounded-xl p-4">
                    <p class="text-sm font-medium" id="labelSelisih"></p>
                    <p class="text-base font-bold mt-0.5" id="nilaiSelisih"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Keterangan</label>
                    <textarea name="keterangan" rows="3" placeholder="Keterangan tambahan (opsional)"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex gap-3 justify-end">
                <button type="button" onclick="document.getElementById('modalProses').classList.add('hidden')"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Batal
                </button>
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-600 transition-colors">
                    Simpan & Tandai Terjual
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Batal --}}
<div id="modalBatal" class="hidden fixed inset-0 z-99999 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6 text-center">
            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-50 dark:bg-red-500/10 mx-auto mb-4">
                <svg class="text-red-500" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Batalkan Lelang</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Batalkan lelang No. SBG <span id="batalNoSbg" class="font-semibold text-gray-800 dark:text-white"></span>?
            </p>
            <form id="formBatal" method="POST" action="">
                @csrf
                <div class="mb-4 text-left">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        Alasan Pembatalan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keterangan" rows="3" required placeholder="Masukkan alasan pembatalan..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"></textarea>
                </div>
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="document.getElementById('modalBatal').classList.add('hidden')"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        Tutup
                    </button>
                    <button type="submit"
                        class="rounded-lg bg-red-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-600 transition-colors">
                        Ya, Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let sisaHutangGlobal = 0;

function openProsesModal(id, noSbg, sisaHutang) {
    sisaHutangGlobal = sisaHutang;
    document.getElementById('prosesSubtitle').textContent = 'No. SBG: ' + noSbg;
    document.getElementById('nilaiSisaHutang').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(sisaHutang);
    document.getElementById('formProses').action = '/superadmin/lelang/' + id + '/proses';
    document.getElementById('inputHargaTerjual').value = '';
    document.getElementById('previewSelisih').classList.add('hidden');
    document.getElementById('modalProses').classList.remove('hidden');
}

function hitungSelisih() {
    const harga   = parseFloat(document.getElementById('inputHargaTerjual').value) || 0;
    const selisih = harga - sisaHutangGlobal;
    const preview = document.getElementById('previewSelisih');
    const label   = document.getElementById('labelSelisih');
    const nilai   = document.getElementById('nilaiSelisih');

    if (harga <= 0) { preview.classList.add('hidden'); return; }

    preview.classList.remove('hidden');

    if (selisih > 0) {
        preview.className = 'rounded-xl p-4 bg-success-50 dark:bg-success-500/10';
        label.className   = 'text-sm font-medium text-success-600 dark:text-success-500';
        label.textContent = '> Kelebihan (dikembalikan ke nasabah)';
        nilai.className   = 'text-base font-bold text-success-600 dark:text-success-500 mt-0.5';
        nilai.textContent = '+Rp ' + new Intl.NumberFormat('id-ID').format(selisih);
    } else if (selisih < 0) {
        preview.className = 'rounded-xl p-4 bg-error-50 dark:bg-error-500/10';
        label.className   = 'text-sm font-medium text-error-600 dark:text-error-500';
        label.textContent = '< Kekurangan (perusahaan menanggung kerugian)';
        nilai.className   = 'text-base font-bold text-error-600 dark:text-error-500 mt-0.5';
        nilai.textContent = '-Rp ' + new Intl.NumberFormat('id-ID').format(Math.abs(selisih));
    } else {
        preview.className = 'rounded-xl p-4 bg-gray-50 dark:bg-gray-800';
        label.className   = 'text-sm font-medium text-gray-600 dark:text-gray-400';
        label.textContent = '= Pas (harga terjual sesuai hutang)';
        nilai.className   = 'text-base font-bold text-gray-600 dark:text-gray-400 mt-0.5';
        nilai.textContent = 'Rp 0';
    }
}

function openBatalModal(id, noSbg) {
    document.getElementById('batalNoSbg').textContent = noSbg;
    document.getElementById('formBatal').action = '/superadmin/lelang/' + id + '/batal';
    document.getElementById('modalBatal').classList.remove('hidden');
}
</script>
@endpush

@endsection
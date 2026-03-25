@extends('layouts.app')
@section('content')

{{-- Alert --}}
@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium dark:bg-red-500/10 dark:border-red-500/20 dark:text-red-400">
    {{ session('error') }}
</div>
@endif

{{-- Stat Cards --}}
<div class="grid grid-cols-2 gap-4 md:gap-6 mb-6">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800 flex-shrink-0">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.25 5C3.25 3.48122 4.48122 2.25 6 2.25H18C19.5188 2.25 20.75 3.48122 20.75 5V19C20.75 20.5188 19.5188 21.75 18 21.75H6C4.48122 21.75 3.25 20.5188 3.25 19V5ZM6 3.75C5.30964 3.75 4.75 4.30964 4.75 5V19C4.75 19.6904 5.30964 20.25 6 20.25H18C18.6904 20.25 19.25 19.6904 19.25 19V5C19.25 4.30964 18.6904 3.75 18 3.75H6ZM3.25 12C3.25 11.5858 3.58579 11.25 4 11.25H20C20.4142 11.25 20.75 11.5858 20.75 12C20.75 12.4142 20.4142 12.75 20 12.75H4C3.58579 12.75 3.25 12.4142 3.25 12ZM9.25 7C9.25 6.58579 9.58579 6.25 10 6.25H14C14.4142 6.25 14.75 6.58579 14.75 7C14.75 7.41421 14.4142 7.75 14 7.75H10C9.58579 7.75 9.25 7.41421 9.25 7ZM9.25 17C9.25 16.5858 9.58579 16.25 10 16.25H14C14.4142 16.25 14.75 16.5858 14.75 17C14.75 17.4142 14.4142 17.75 14 17.75H10C9.58579 17.75 9.25 17.4142 9.25 17Z" fill=""/>
                </svg>
            </div>
            <div class="flex-1">
              <span class="text-sm text-gray-500 dark:text-gray-400">Loker Kosong</span>
              <div class="flex items-end justify-between mt-1">
                  <h4 class="font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $totalKosong }}</h4>
                  <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">Tersedia</span>
              </div>
          </div>
        </div>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800 flex-shrink-0">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.25 5C3.25 3.48122 4.48122 2.25 6 2.25H18C19.5188 2.25 20.75 3.48122 20.75 5V19C20.75 20.5188 19.5188 21.75 18 21.75H6C4.48122 21.75 3.25 20.5188 3.25 19V5ZM6 3.75C5.30964 3.75 4.75 4.30964 4.75 5V19C4.75 19.6904 5.30964 20.25 6 20.25H18C18.6904 20.25 19.25 19.6904 19.25 19V5C19.25 4.30964 18.6904 3.75 18 3.75H6ZM3.25 12C3.25 11.5858 3.58579 11.25 4 11.25H20C20.4142 11.25 20.75 11.5858 20.75 12C20.75 12.4142 20.4142 12.75 20 12.75H4C3.58579 12.75 3.25 12.4142 3.25 12ZM9.25 7C9.25 6.58579 9.58579 6.25 10 6.25H14C14.4142 6.25 14.75 6.58579 14.75 7C14.75 7.41421 14.4142 7.75 14 7.75H10C9.58579 7.75 9.25 7.41421 9.25 7ZM9.25 17C9.25 16.5858 9.58579 16.25 10 16.25H14C14.4142 16.25 14.75 16.5858 14.75 17C14.75 17.4142 14.4142 17.75 14 17.75H10C9.58579 17.75 9.25 17.4142 9.25 17Z" fill=""/>
                </svg>
            </div>
            <div class="flex-1">
              <span class="text-sm text-gray-500 dark:text-gray-400">Loker Terisi</span>
              <div class="flex items-end justify-between mt-1">
                  <h4 class="font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $totalTerisi }}</h4>
                  <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">Terpakai</span>
              </div>
          </div>
        </div>
    </div>
</div>

<div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- Header --}}
    <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Loker Barcode</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola loker penyimpanan barang gadai</p>
        </div>
        @if(auth()->user()->role !== 'officer')
        <a href="{{ route(auth()->user()->role . '.loker.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            Tambah Loker
        </a>
        @endif
    </div>

    {{-- Filter --}}
    <div class="px-6 mb-4">
        <form method="GET" action="{{ route(auth()->user()->role . '.loker') }}" class="flex flex-wrap items-center gap-3">
            <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">

            @if(auth()->user()->role === 'superadmin')
            <div class="relative">
                <select name="cabang_id" onchange="this.form.submit()"
                    class="h-9 rounded-lg border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    <option value="">Semua Cabang</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('cabang_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="relative">
                <select name="rak" onchange="this.form.submit()"
                    class="h-9 rounded-lg border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    <option value="">Semua Rak</option>
                    @foreach(['A','B','C','D','E','F'] as $rak)
                        <option value="{{ $rak }}" {{ request('rak') == $rak ? 'selected' : '' }}>Rak {{ $rak }}</option>
                    @endforeach
                </select>
            </div>

            <div class="relative">
                <select name="status" onchange="this.form.submit()"
                    class="h-9 rounded-lg border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    <option value="">Semua Status</option>
                    <option value="kosong" {{ request('status') == 'kosong' ? 'selected' : '' }}>Kosong</option>
                    <option value="terisi" {{ request('status') == 'terisi' ? 'selected' : '' }}>Terisi</option>
                </select>
            </div>

            <select name="per_page" onchange="this.form.submit()"
                class="h-9 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                @foreach([10, 20, 50] as $val)
                    <option value="{{ $val }}" {{ request('per_page', 10) == $val ? 'selected' : '' }}>{{ $val }} data</option>
                @endforeach
            </select>

            @if(request()->hasAny(['cabang_id', 'rak', 'status']))
            <a href="{{ route(auth()->user()->role . '.loker') }}"
                class="h-9 inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
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
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Kode Loker</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">QR Code</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Rak</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Keterangan</th>
                    @if(auth()->user()->role !== 'officer')
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($lockers as $index => $locker)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                        {{ $lockers->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="font-medium text-theme-sm text-gray-800 dark:text-white/90">{{ $locker->kode_loker }}</span>
                    </td>
                    <td class="px-6 py-3.5">
                        <button onclick="openQrModal('{{ $locker->kode_loker }}', '{{ $locker->branch->nama ?? '' }}')"
                            class="text-gray-400 hover:text-brand-500 dark:hover:text-brand-400 transition-colors">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9V5a2 2 0 012-2h4M3 15v4a2 2 0 002 2h4M15 3h4a2 2 0 012 2v4M15 21h4a2 2 0 002-2v-4"/>
                                <rect x="7" y="7" width="4" height="4" rx="0.5" stroke-width="1.5"/>
                                <rect x="13" y="7" width="4" height="4" rx="0.5" stroke-width="1.5"/>
                                <rect x="7" y="13" width="4" height="4" rx="0.5" stroke-width="1.5"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 17h4M17 13v4"/>
                            </svg>
                        </button>
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">Rak {{ $locker->rak }}</td>
                    <td class="px-6 py-3.5">
                        <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium
                            {{ $locker->status === 'kosong'
                                ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500'
                                : 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400' }}">
                            {{ ucfirst($locker->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">{{ $locker->keterangan ?? '-' }}</td>
                    @if(auth()->user()->role !== 'officer')
                    <td class="px-6 py-3.5">
                        @if($locker->status === 'kosong')
                            <button onclick="openDeleteModal({{ $locker->id }}, '{{ $locker->kode_loker }}')"
                                class="text-gray-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        @else
                            <span class="text-gray-300 dark:text-gray-700 cursor-not-allowed">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </span>
                        @endif
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="{{ auth()->user()->role !== 'officer' ? 7 : 6 }}" class="px-6 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                        Belum ada data loker
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer: Info + Pagination --}}
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Menampilkan {{ $lockers->firstItem() ?? 0 }}–{{ $lockers->lastItem() ?? 0 }} dari {{ $lockers->total() }} data
        </p>
        <div class="flex items-center gap-2">
            <a href="{{ $lockers->previousPageUrl() }}"
                class="flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 {{ $lockers->onFirstPage() ? 'opacity-40 pointer-events-none' : '' }}">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z" fill="currentColor"/></svg>
                <span class="hidden sm:inline">Sebelumnya</span>
            </a>
            @foreach($lockers->getUrlRange(1, $lockers->lastPage()) as $page => $url)
                <a href="{{ $url }}"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-theme-sm font-medium transition-colors
                    {{ $page == $lockers->currentPage()
                        ? 'bg-brand-500 text-white'
                        : 'text-gray-700 hover:bg-brand-500 hover:text-white dark:text-gray-400' }}">
                    {{ $page }}
                </a>
            @endforeach
            <a href="{{ $lockers->nextPageUrl() }}"
                class="flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 {{ !$lockers->hasMorePages() ? 'opacity-40 pointer-events-none' : '' }}">
                <span class="hidden sm:inline">Selanjutnya</span>
                <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z" fill="currentColor"/></svg>
            </a>
        </div>
    </div>
</div>

{{-- Modal QR Code --}}
<div id="modalQr" class="hidden fixed inset-0 z-99999 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-sm mx-4">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div>
                <h4 class="text-base font-semibold text-gray-800 dark:text-white" id="qrKode"></h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5" id="qrCabang"></p>
            </div>
            <button onclick="document.getElementById('modalQr').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6 flex flex-col items-center gap-3">
            <div id="qrContainer" class="p-4 bg-white rounded-xl border border-gray-200"></div>
            <p class="text-xs text-gray-400 text-center">Scan QR untuk melihat detail loker</p>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex flex-col gap-2">
            <button onclick="downloadQr()"
                class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-600 transition-colors">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                    <path d="M10 2.5V13.5M10 13.5L6 9.5M10 13.5L14 9.5M3 15.5H17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Unduh QR Code
            </button>
            <a id="btnBukaTab" href="#" target="_blank"
                class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                </svg>
                Buka di Tab Baru
            </a>
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
<div id="modalHapus" class="hidden fixed inset-0 z-99999 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6 text-center">
            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-50 dark:bg-red-500/10 mx-auto mb-4">
                <svg class="text-red-500" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Hapus Loker</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                Apakah Anda yakin ingin menghapus loker <span id="deleteKode" class="font-semibold text-gray-800 dark:text-white"></span>?
            </p>
            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('modalHapus').classList.add('hidden')"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Batal
                </button>
                <form id="formHapus" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="rounded-lg bg-red-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-600 transition-colors">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
let currentQrKode = '';

function openQrModal(kode, cabang) {
    currentQrKode = kode;
    document.getElementById('qrKode').textContent = kode;
    document.getElementById('qrCabang').textContent = cabang;

    const container = document.getElementById('qrContainer');
    container.innerHTML = '';

    new QRCode(container, {
        text: `{{ url('/loker') }}/${kode}`,
        width: 200,
        height: 200,
        colorDark: '#1F5C3A',
        colorLight: '#ffffff',
        correctLevel: QRCode.CorrectLevel.H
    });

    document.getElementById('btnBukaTab').href = `{{ url('/loker') }}/${kode}`;
    document.getElementById('modalQr').classList.remove('hidden');
}

function downloadQr() {
    const canvas = document.querySelector('#qrContainer canvas');
    if (canvas) {
        const link = document.createElement('a');
        link.download = `QR-${currentQrKode}.png`;
        link.href = canvas.toDataURL();
        link.click();
    }
}

function openDeleteModal(id, kode) {
    const role = '{{ auth()->user()->role }}';
    document.getElementById('deleteKode').textContent = kode;
    document.getElementById('formHapus').action = `/${role}/loker/${id}`;
    document.getElementById('modalHapus').classList.remove('hidden');
}
</script>
@endpush

@endsection
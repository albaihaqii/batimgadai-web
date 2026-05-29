@extends('layouts.app')
@section('content')

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400">
    {{ session('success') }}
</div>
@endif

{{-- Header --}}
<div class="mb-6 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Master Simulasi Pinjaman</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola persentase gadai, kecacatan, dan kelengkapan per kategori barang</p>
    </div>
    <span class="text-xs text-gray-400 dark:text-gray-500 mt-1 sm:mt-0">{{ count($kategoriList) }} kategori tersedia</span>
</div>

{{-- Tab Kategori --}}
@php
$kategoriIcon = [
    'handphone'           => '📱',
    'laptop'              => '💻',
    'tablet'              => '📟',
    'elektronik_lainnya'  => '🔌',
    'kendaraan_motor'     => '🏍️',
    'barang_rumah_tangga' => '🏠',
    'perhiasan'           => '💍',
];
@endphp

<div class="mb-6 flex flex-wrap gap-2">
    <a href="{{ route('superadmin.simulasi') }}"
        class="rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ !$kategoriFilter ? 'bg-brand-500 text-white shadow-sm' : 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400' }}">
        Semua Kategori
    </a>
    @foreach($kategoriList as $kat)
    <a href="{{ route('superadmin.simulasi', ['kategori' => $kat]) }}"
        class="rounded-lg px-4 py-2 text-sm font-medium transition-colors {{ $kategoriFilter === $kat ? 'bg-brand-500 text-white shadow-sm' : 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400' }}">
        {{ $kategoriIcon[$kat] ?? '📦' }} {{ ucfirst(str_replace('_', ' ', $kat)) }}
    </a>
    @endforeach
</div>

{{-- Per Kategori --}}
@foreach(($kategoriFilter ? [$kategoriFilter] : $kategoriList) as $kat)
@php
    $master    = $masters->get($kat);
    $cacatList = $kecacatan->get($kat, collect());
    $lengkList = $kelengkapan->get($kat, collect());
@endphp

<div class="mb-6 rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- Header Kategori --}}
    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-brand-50 dark:bg-brand-500/10 text-lg flex-shrink-0">
                    {{ $kategoriIcon[$kat] ?? '📦' }}
                </div>
                <div>
                    <h4 class="text-base font-semibold text-gray-800 dark:text-white">
                        {{ ucfirst(str_replace('_', ' ', $kat)) }}
                    </h4>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                        {{ $master?->keterangan ?? 'Belum ada keterangan' }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if($master)
                    <span class="rounded-full px-2.5 py-1 text-xs font-medium {{ $master->is_active ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400' }}">
                        {{ $master->is_active ? '● Aktif' : '○ Nonaktif' }}
                    </span>
                    @if($master->is_active)
                    <div class="text-right">
                        <p class="text-xs text-gray-400">Range Gadai</p>
                        <p class="text-sm font-bold text-brand-500">{{ $master->persen_min }}% — {{ $master->persen_max }}%</p>
                    </div>
                    @endif
                @else
                    <span class="rounded-full px-2.5 py-1 text-xs font-medium bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                        ⚠ Belum dikonfigurasi
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="p-6 space-y-6">

        {{-- 1. Persentase Gadai --}}
        <div class="rounded-xl border border-gray-100 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1.5 h-4 rounded-full bg-brand-500 flex-shrink-0"></div>
                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Persentase Gadai dari Harga Pasar</h5>
                <span class="ml-auto text-xs text-gray-400 hidden sm:inline">Sistem menghitung nilai pinjaman dari rentang ini</span>
            </div>

            <form method="POST" action="{{ route('superadmin.simulasi.master.update', $kat) }}"
                class="flex flex-wrap items-end gap-4">
                @csrf
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">% Minimum</label>
                    <div class="relative">
                        <input type="number" name="persen_min" value="{{ $master?->persen_min ?? 50 }}"
                            step="0.5" min="1" max="100"
                            class="h-10 w-32 rounded-lg border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-800 focus:border-brand-300 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">%</span>
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">% Maksimum</label>
                    <div class="relative">
                        <input type="number" name="persen_max" value="{{ $master?->persen_max ?? 75 }}"
                            step="0.5" min="1" max="100"
                            class="h-10 w-32 rounded-lg border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-800 focus:border-brand-300 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">%</span>
                    </div>
                </div>
                <div class="flex-1 min-w-48">
                    <label class="mb-1.5 block text-xs font-medium text-gray-600 dark:text-gray-400">Keterangan</label>
                    <input type="text" name="keterangan" value="{{ $master?->keterangan }}"
                        placeholder="Contoh: Smartphone, HP Android/iOS"
                        class="h-10 w-full rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-800 focus:border-brand-300 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                </div>
                <div class="flex items-center gap-3 pb-0.5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_active" value="1" class="sr-only peer"
                                {{ ($master?->is_active ?? true) ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-gray-200 rounded-full peer peer-checked:bg-brand-500 transition-colors dark:bg-gray-700"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                        </div>
                        <span class="text-xs text-gray-600 dark:text-gray-400">Aktif</span>
                    </label>
                    <button type="submit"
                        class="h-10 rounded-lg bg-brand-500 px-5 text-sm font-medium text-white hover:bg-brand-600 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>

            {{-- Preview kalkulasi --}}
            @if($master && $master->is_active)
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 flex flex-wrap gap-3 text-xs text-gray-500 dark:text-gray-400">
                <span>💡 Contoh harga pasar Rp 5.000.000</span>
                <span class="text-brand-500 font-medium">
                    → Estimasi: Rp {{ number_format(5000000 * $master->persen_min / 100, 0, ',', '.') }} — Rp {{ number_format(5000000 * $master->persen_max / 100, 0, ',', '.') }}
                </span>
                <span class="text-gray-400">(sebelum faktor kondisi & kecacatan)</span>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- 2. Kecacatan --}}
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-1.5 h-4 rounded-full bg-error-500 flex-shrink-0"></div>
                    <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Kecacatan</h5>
                    <span class="ml-auto rounded-full bg-error-50 px-2 py-0.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-400">
                        {{ $cacatList->count() }} item
                    </span>
                </div>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-3 ml-3.5">Faktor pengurangan nilai (input negatif, contoh: -10)</p>

                {{-- List --}}
                <div class="space-y-2 mb-4 max-h-56 overflow-y-auto pr-1">
                    @forelse($cacatList as $cacat)
                    <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 dark:border-gray-700 dark:bg-gray-800">
                        <span class="flex-1 text-sm text-gray-800 dark:text-white/90">{{ $cacat->label }}</span>
                        <span class="text-sm font-bold min-w-12 text-right {{ $cacat->faktor < 0 ? 'text-error-600 dark:text-error-400' : 'text-gray-500' }}">
                            {{ $cacat->faktor > 0 ? '+' : '' }}{{ $cacat->faktor }}%
                        </span>
                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $cacat->is_active ? 'bg-success-500' : 'bg-gray-300' }}"></span>
                        <button type="button"
                            onclick="openEditModal('kecacatan', {{ $cacat->id }}, '{{ addslashes($cacat->label) }}', {{ $cacat->faktor }}, {{ $cacat->is_active ? 1 : 0 }})"
                            class="text-gray-400 hover:text-brand-500 transition-colors flex-shrink-0">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('superadmin.simulasi.kecacatan.destroy', $cacat->id) }}"
                            class="inline" onsubmit="return confirm('Hapus item kecacatan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors flex-shrink-0">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="rounded-lg border border-dashed border-gray-200 dark:border-gray-700 py-6 text-center">
                        <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada item kecacatan</p>
                    </div>
                    @endforelse
                </div>

                {{-- Tambah --}}
                <form method="POST" action="{{ route('superadmin.simulasi.kecacatan.store') }}"
                    class="flex gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                    @csrf
                    <input type="hidden" name="kategori" value="{{ $kat }}">
                    <input type="text" name="label" placeholder="Nama kecacatan" required
                        class="flex-1 h-9 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-800 focus:border-brand-300 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                    <div class="relative">
                        <input type="number" name="faktor" placeholder="-5" step="0.5" min="-100" max="0" required
                            class="w-20 h-9 rounded-lg border border-gray-300 bg-white px-3 pr-6 text-sm text-gray-800 focus:border-brand-300 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                        <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-400">%</span>
                    </div>
                    <button type="submit"
                        class="h-9 rounded-lg bg-gray-800 px-3 text-sm font-medium text-white hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors whitespace-nowrap">
                        + Tambah
                    </button>
                </form>
            </div>

            {{-- 3. Kelengkapan --}}
            <div class="rounded-xl border border-gray-100 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-1.5 h-4 rounded-full bg-success-500 flex-shrink-0"></div>
                    <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Kelengkapan</h5>
                    <span class="ml-auto rounded-full bg-success-50 px-2 py-0.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-400">
                        {{ $lengkList->count() }} item
                    </span>
                </div>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-3 ml-3.5">Faktor penambahan/pengurangan nilai (contoh: +5 atau -5)</p>

                {{-- List --}}
                <div class="space-y-2 mb-4 max-h-56 overflow-y-auto pr-1">
                    @forelse($lengkList as $lk)
                    <div class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-3 py-2 dark:border-gray-700 dark:bg-gray-800">
                        <span class="flex-1 text-sm text-gray-800 dark:text-white/90">{{ $lk->label }}</span>
                        <span class="text-sm font-bold min-w-12 text-right
                            {{ $lk->faktor > 0 ? 'text-success-600 dark:text-success-400' : ($lk->faktor < 0 ? 'text-error-600 dark:text-error-400' : 'text-gray-500') }}">
                            {{ $lk->faktor > 0 ? '+' : '' }}{{ $lk->faktor }}%
                        </span>
                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $lk->is_active ? 'bg-success-500' : 'bg-gray-300' }}"></span>
                        <button type="button"
                            onclick="openEditModal('kelengkapan', {{ $lk->id }}, '{{ addslashes($lk->label) }}', {{ $lk->faktor }}, {{ $lk->is_active ? 1 : 0 }})"
                            class="text-gray-400 hover:text-brand-500 transition-colors flex-shrink-0">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('superadmin.simulasi.kelengkapan.destroy', $lk->id) }}"
                            class="inline" onsubmit="return confirm('Hapus item kelengkapan ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors flex-shrink-0">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="rounded-lg border border-dashed border-gray-200 dark:border-gray-700 py-6 text-center">
                        <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada item kelengkapan</p>
                    </div>
                    @endforelse
                </div>

                {{-- Tambah --}}
                <form method="POST" action="{{ route('superadmin.simulasi.kelengkapan.store') }}"
                    class="flex gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                    @csrf
                    <input type="hidden" name="kategori" value="{{ $kat }}">
                    <input type="text" name="label" placeholder="Nama kelengkapan" required
                        class="flex-1 h-9 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-800 focus:border-brand-300 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                    <div class="relative">
                        <input type="number" name="faktor" placeholder="+5" step="0.5" min="-100" max="100" required
                            class="w-20 h-9 rounded-lg border border-gray-300 bg-white px-3 pr-6 text-sm text-gray-800 focus:border-brand-300 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white/90">
                        <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-400">%</span>
                    </div>
                    <button type="submit"
                        class="h-9 rounded-lg bg-gray-800 px-3 text-sm font-medium text-white hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 transition-colors whitespace-nowrap">
                        + Tambah
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endforeach

{{-- Modal Edit --}}
<div id="modalEdit" class="hidden fixed inset-0 z-99999 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-sm mx-4">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <h4 class="text-base font-semibold text-gray-800 dark:text-white" id="modalEditTitle">Edit Item</h4>
            <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')"
                class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="formEdit" method="POST" action="">
            @csrf @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Label</label>
                    <input type="text" name="label" id="editLabel" required
                        class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                </div>
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Faktor (%)
                        <span class="text-xs text-gray-400 font-normal ml-1" id="faktorHint"></span>
                    </label>
                    <div class="relative">
                        <input type="number" name="faktor" id="editFaktor" step="0.5" required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 pr-8 text-sm text-gray-800 focus:border-brand-300 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">%</span>
                    </div>
                </div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" name="is_active" id="editAktif" value="1" class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-brand-500 transition-colors dark:bg-gray-700"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktif</span>
                </label>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex gap-3 justify-end">
                <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Batal
                </button>
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-600 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openEditModal(tipe, id, label, faktor, isAktif) {
    const isCacat = tipe === 'kecacatan';
    document.getElementById('modalEditTitle').textContent = isCacat ? 'Edit Kecacatan' : 'Edit Kelengkapan';
    document.getElementById('faktorHint').textContent     = isCacat
        ? '— nilai negatif untuk pengurangan'
        : '— positif menambah nilai, negatif mengurangi';
    document.getElementById('formEdit').action            = '/superadmin/simulasi/' + tipe + '/' + id;
    document.getElementById('editLabel').value            = label;
    document.getElementById('editFaktor').value           = faktor;
    document.getElementById('editAktif').checked          = isAktif === 1;
    document.getElementById('modalEdit').classList.remove('hidden');
}
</script>
@endpush

@endsection
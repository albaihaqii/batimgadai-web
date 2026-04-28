@extends('layouts.app')
@section('content')

<x-common.page-breadcrumb pageTitle="Tambah Rate Jasa" />

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
        <h3 class="text-base font-semibold text-gray-800 dark:text-white">Form Tambah Rate Perhitungan Jasa</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Isi data rate perhitungan jasa dengan lengkap dan benar.</p>
    </div>

    <form method="POST" action="{{ route('superadmin.jasa-rate.store') }}" class="p-6">
        @csrf
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Tipe --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Tipe Jasa <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select name="tipe"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 {{ $errors->has('tipe') ? 'border-red-500' : '' }}">
                        <option value="umum" {{ request('tipe', old('tipe')) !== 'perhiasan' ? 'selected' : '' }}>Umum</option>
                        <option value="perhiasan" {{ request('tipe', old('tipe')) === 'perhiasan' ? 'selected' : '' }}>Perhiasan / Emas</option>
                    </select>
                    <span class="absolute top-1/2 right-4 -translate-y-1/2 pointer-events-none text-gray-500">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                </div>
                @error('tipe') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Status <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select name="is_active"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="1" selected>Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                    <span class="absolute top-1/2 right-4 -translate-y-1/2 pointer-events-none text-gray-500">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                </div>
            </div>

            {{-- Min Pinjaman --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Minimum Pinjaman (Rp) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 pointer-events-none font-medium">Rp</span>
                    <input type="number" name="min_pinjaman" value="{{ old('min_pinjaman', 0) }}" min="0"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-10 pr-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 {{ $errors->has('min_pinjaman') ? 'border-red-500' : '' }}">
                </div>
                @error('min_pinjaman') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Max Pinjaman --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Maksimum Pinjaman (Rp)
                    <span class="text-gray-400 font-normal ml-1">— kosongkan jika tidak ada batas</span>
                </label>
                <div class="relative">
                    <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 pointer-events-none font-medium">Rp</span>
                    <input type="number" name="max_pinjaman" value="{{ old('max_pinjaman') }}" min="0"
                        placeholder="Kosongkan jika ke atas"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('max_pinjaman') ? 'border-red-500' : '' }}">
                </div>
                @error('max_pinjaman') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Jasa 15 Hari --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Jasa 1/2 Bulan — 1 s/d 15 hari (%) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" name="jasa_15_hari" value="{{ old('jasa_15_hari') }}"
                        min="0" max="100" step="0.01" placeholder="Contoh: 5"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 pr-10 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 {{ $errors->has('jasa_15_hari') ? 'border-red-500' : '' }}">
                    <span class="absolute top-1/2 right-4 -translate-y-1/2 text-sm text-gray-400 pointer-events-none">%</span>
                </div>
                @error('jasa_15_hari') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Jasa 30 Hari --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Jasa 1 Bulan — 16 s/d 30 hari (%) <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" name="jasa_30_hari" value="{{ old('jasa_30_hari') }}"
                        min="0" max="100" step="0.01" placeholder="Contoh: 8"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 pr-10 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 {{ $errors->has('jasa_30_hari') ? 'border-red-500' : '' }}">
                    <span class="absolute top-1/2 right-4 -translate-y-1/2 text-sm text-gray-400 pointer-events-none">%</span>
                </div>
                @error('jasa_30_hari') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

        </div>

        <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-800">
            <button type="submit"
                class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                Simpan Data
            </button>
            <a href="{{ route('superadmin.jasa-rate') }}"
                class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection
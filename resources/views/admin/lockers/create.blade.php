@extends('layouts.app')
@section('content')

<x-common.page-breadcrumb pageTitle="Tambah Loker" />

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
        <h3 class="text-base font-semibold text-gray-800 dark:text-white">Form Tambah Loker</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kode loker akan digenerate otomatis sesuai cabang dan rak yang dipilih.</p>
    </div>

    <form method="POST" action="{{ route(auth()->user()->role . '.loker.store') }}" class="p-6">
        @csrf

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Cabang --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Cabang <span class="text-red-500">*</span>
                </label>
                @if(auth()->user()->role === 'superadmin')
                <div class="relative">
                    <select name="cabang_id"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 {{ $errors->has('cabang_id') ? 'border-red-500' : '' }}">
                        <option value="">Pilih Cabang</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ old('cabang_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->nama }}
                            </option>
                        @endforeach
                    </select>
                    <span class="absolute top-1/2 right-4 -translate-y-1/2 pointer-events-none text-gray-500">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                </div>
                @else
                <input type="hidden" name="cabang_id" value="{{ auth()->user()->cabang_id }}">
                <input type="text" value="{{ auth()->user()->branch->nama ?? '-' }}" disabled
                    class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-400 cursor-not-allowed dark:border-gray-700 dark:bg-gray-800 dark:text-gray-500">
                @endif
                @error('cabang_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Rak --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Rak <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select name="rak"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 {{ $errors->has('rak') ? 'border-red-500' : '' }}">
                        <option value="">Pilih Rak</option>
                        @foreach($raks as $rak)
                            <option value="{{ $rak }}" {{ old('rak') == $rak ? 'selected' : '' }}>Rak {{ $rak }}</option>
                        @endforeach
                    </select>
                    <span class="absolute top-1/2 right-4 -translate-y-1/2 pointer-events-none text-gray-500">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                </div>
                @error('rak') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Jumlah Loker --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Jumlah Loker <span class="text-red-500">*</span>
                    <span class="text-gray-400 font-normal">(maks. 50)</span>
                </label>
                <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}"
                    min="1" max="50" placeholder="Masukkan jumlah loker"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('jumlah') ? 'border-red-500' : '' }}">
                @error('jumlah') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Keterangan --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Keterangan
                    <span class="text-gray-400 font-normal">(Opsional)</span>
                </label>
                <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                    placeholder="Contoh: Loker handphone dan tablet"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                @error('keterangan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-800">
            <button type="submit"
                class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                Generate Loker
            </button>
            <a href="{{ route(auth()->user()->role . '.loker') }}"
                class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection
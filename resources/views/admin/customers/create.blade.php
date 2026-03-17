@extends('layouts.app')
@section('content')

<x-common.page-breadcrumb pageTitle="Tambah Nasabah" />

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
        <h3 class="text-base font-semibold text-gray-800 dark:text-white">Form Tambah Nasabah</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Isi data nasabah dengan lengkap dan benar.</p>
    </div>

    <form method="POST" action="{{ route(auth()->user()->role . '.nasabah.store') }}" class="p-6">
        @csrf

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- Nama Lengkap --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}"
                    placeholder="Masukkan nama lengkap"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('nama') ? 'border-red-500' : '' }}">
                @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- No KTP --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    No KTP <span class="text-red-500">*</span>
                    <span class="text-gray-400 font-normal">(16 digit)</span>
                </label>
                <input type="text" name="no_ktp" value="{{ old('no_ktp') }}"
                    placeholder="Masukkan 16 digit No KTP" maxlength="16"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('no_ktp') ? 'border-red-500' : '' }}">
                @error('no_ktp') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- No HP --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    No HP <span class="text-red-500">*</span>
                </label>
                <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                    placeholder="08xx atau +62xx"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('no_hp') ? 'border-red-500' : '' }}">
                @error('no_hp') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Cabang --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Cabang <span class="text-red-500">*</span>
                </label>
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
                @error('cabang_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Status --}}
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Status <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select name="status"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="aktif" {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    <span class="absolute top-1/2 right-4 -translate-y-1/2 pointer-events-none text-gray-500">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </span>
                </div>
            </div>

            {{-- Alamat --}}
            <div class="md:col-span-2">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                    Alamat <span class="text-red-500">*</span>
                </label>
                <textarea name="alamat" rows="3" placeholder="Masukkan alamat lengkap"
                    class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('alamat') ? 'border-red-500' : '' }}">{{ old('alamat') }}</textarea>
                @error('alamat') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

        </div>

        {{-- Buttons --}}
        <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-800">
            <button type="submit"
                class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                Simpan Data
            </button>
            <a href="{{ route(auth()->user()->role . '.nasabah') }}"
                class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection
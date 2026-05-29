@extends('layouts.app')
@section('content')

@php $routePrefix = auth()->user()->role; @endphp

<x-common.page-breadcrumb pageTitle="Edit Banner Mobile" />

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <form method="POST" action="{{ route($routePrefix . '.banner.mobile.update', $banner->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Edit Banner Mobile App</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Perbarui informasi banner promo</p>
                </div>
                <div class="p-6 space-y-5">

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Judul <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul', $banner->judul) }}"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        @error('judul')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">URL Link</label>
                        <input type="text" name="url_link" value="{{ old('url_link', $banner->url_link) }}"
                            placeholder="https://..."
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>

                    @if(auth()->user()->role === 'superadmin')
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Target Cabang</label>
                        <div class="relative">
                            <select name="cabang_id"
                                class="h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 pr-10 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="">Semua Cabang</option>
                                @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('cabang_id', $banner->cabang_id) == $branch->id ? 'selected' : '' }}>{{ $branch->nama }}</option>
                                @endforeach
                            </select>
                            <span class="absolute top-1/2 right-4 -translate-y-1/2 pointer-events-none text-gray-500">
                                <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                        </div>
                    </div>
                    @endif

                    {{-- Foto Mobile: preview 16:9 --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Foto Banner
                            <span class="text-xs text-gray-400 font-normal ml-1">— kosongkan jika tidak ingin mengganti</span>
                        </label>

                        <input type="file" name="foto" id="fotoInput" accept="image/jpg,image/jpeg,image/png"
                            class="hidden" onchange="handleFotoMobile(this)">

                        <div id="fotoPreview" class="mb-3">
                            <div class="relative w-full overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700" style="aspect-ratio: 16/9;">
                                <img id="previewImg" src="{{ asset('storage/' . $banner->foto) }}" alt="Preview"
                                    class="w-full h-full object-cover">
                            </div>
                        </div>

                        <button type="button" onclick="document.getElementById('fotoInput').click()"
                            class="inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 bg-gray-50 px-5 py-3 text-sm font-medium text-gray-600 hover:border-brand-400 hover:bg-brand-50 hover:text-brand-600 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-brand-500">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
                            Ganti Foto Banner
                        </button>
                        <p class="text-xs text-gray-400 mt-1.5">JPG, JPEG, PNG. Rasio 16:9 agar tampil optimal di mobile</p>
                        @error('foto')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Urutan <span class="text-red-500">*</span></label>
                            <input type="number" name="urutan" value="{{ old('urutan', $banner->urutan) }}" min="1"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        </div>
                        <div class="flex items-end pb-1">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:bg-brand-500 transition-colors dark:bg-gray-700"></div>
                                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktif</p>
                                    <p class="text-xs text-gray-400">Notif dikirim jika baru diaktifkan</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-5 border-t border-gray-200 dark:border-gray-800 flex gap-3">
                    <button type="submit" class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-600 transition-colors">Perbarui Banner</button>
                    <a href="{{ route($routePrefix . '.banner.mobile') }}" class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Batal</a>
                </div>
            </div>
        </form>
    </div>

    <div>
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-3">Info Banner</h4>
            <div class="space-y-2 text-sm text-gray-500 dark:text-gray-400">
                <p>Dibuat: {{ $banner->created_at->format('d M Y, H:i') }} WIB</p>
                <p>Diperbarui: {{ $banner->updated_at->format('d M Y, H:i') }} WIB</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function handleFotoMobile(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        document.getElementById('previewImg').src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush
@endsection
@extends('layouts.app')
@section('content')
<x-common.page-breadcrumb pageTitle="Edit Banner Landing" />

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2">
        <form method="POST" action="{{ route('superadmin.banner.landing.update', $banner->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Edit Banner Landing Page</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Perbarui informasi hero slider</p>
                </div>
                <div class="p-6 space-y-5">

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Judul <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul', $banner->judul) }}"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        @error('judul')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kata Highlight</label>
                        <input type="text" name="subjudul" value="{{ old('subjudul', $banner->subjudul) }}"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Deskripsi</label>
                        <textarea name="deskripsi" rows="4"
                            class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('deskripsi', $banner->deskripsi) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Teks Tombol</label>
                            <input type="text" name="teks_tombol" value="{{ old('teks_tombol', $banner->teks_tombol) }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        </div>
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">URL Tombol</label>
                            <input type="text" name="url_tombol" value="{{ old('url_tombol', $banner->url_tombol) }}"
                                class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        </div>
                    </div>

                    {{-- Foto Landing: preview 3:2 (1536x1024) --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Foto Background
                            <span class="text-xs text-gray-400 font-normal ml-1">— kosongkan jika tidak ingin mengganti</span>
                        </label>

                        <input type="file" name="foto" id="fotoInput" accept="image/jpg,image/jpeg,image/png"
                            class="hidden" onchange="handleFotoLanding(this)">

                        <div id="fotoPreview" class="mb-3">
                            <div class="relative w-full overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700" style="aspect-ratio: 3/2;">
                                <img id="previewImg" src="{{ asset('storage/' . $banner->foto) }}" alt="Preview"
                                    class="w-full h-full object-cover">
                            </div>
                            <p class="text-xs text-gray-400 mt-1.5" id="fotoDimensi">Foto saat ini</p>
                        </div>

                        <button type="button" onclick="document.getElementById('fotoInput').click()"
                            class="inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 bg-gray-50 px-5 py-3 text-sm font-medium text-gray-600 hover:border-brand-400 hover:bg-brand-50 hover:text-brand-600 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-brand-500">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
                            Ganti Foto Background
                        </button>
                        <p class="text-xs text-gray-400 mt-1.5">JPG, JPEG, PNG. Ukuran ideal 1536×1024px (rasio 3:2)</p>
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
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Aktif</p>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="px-6 py-5 border-t border-gray-200 dark:border-gray-800 flex gap-3">
                    <button type="submit" class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-600 transition-colors">Perbarui Banner</button>
                    <a href="{{ route('superadmin.banner.landing') }}" class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Batal</a>
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
function handleFotoLanding(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        const img = new Image();
        img.onload = () => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('fotoDimensi').textContent = img.width + ' × ' + img.height + ' px';
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush
@endsection
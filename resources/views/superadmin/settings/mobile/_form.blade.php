<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="p-6">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif
    @php
        $activeLimitReached = ($activeCount ?? 0) >= ($maxActiveSlides ?? 4);
        $replacementOptions = $activeSlides ?? collect();
        $canReplaceActiveSlide = $activeLimitReached && !$slide->is_active && $replacementOptions->isNotEmpty();
        $showReplacementField = $canReplaceActiveSlide && old('is_active', $slide->is_active);
    @endphp

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-[220px_1fr]">
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Preview Gambar</label>
            <div class="mx-auto max-w-[180px] overflow-hidden rounded-2xl border border-gray-200 bg-gray-100 dark:border-gray-800 dark:bg-gray-900">
                @if ($slide->image_path)
                    <img id="mobile-image-preview" src="{{ asset($slide->image_path) }}" alt="{{ $slide->title }}" class="h-auto w-full object-contain">
                @else
                    <img id="mobile-image-preview" src="" alt="Preview gambar mobile" class="hidden h-auto w-full object-contain">
                    <div id="mobile-image-placeholder" class="flex aspect-[9/16] items-center justify-center p-4 text-center text-sm text-gray-400">Belum ada gambar</div>
                @endif
            </div>
            <input id="mobile-image-input" type="file" name="image" accept="image/png,image/jpeg,image/webp"
                class="mt-4 block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-500 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-white hover:file:bg-brand-600 dark:text-gray-400">
            <p class="mt-2 text-xs text-gray-400">Format JPG, PNG, atau WEBP. Maksimal 4MB. {{ $method === 'POST' ? 'Wajib diisi.' : 'Kosongkan jika tidak diganti.' }}</p>
            @error('image') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Judul Slide <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $slide->title) }}"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                @error('title') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Urutan <span class="text-red-500">*</span></label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $slide->sort_order) }}" min="1" max="99"
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                @error('sort_order') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Deskripsi</label>
                <textarea name="description" rows="4"
                    class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">{{ old('description', $slide->description) }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-end">
                <label class="flex h-11 items-center gap-3 rounded-lg border border-gray-300 px-4 text-sm font-medium text-gray-700 dark:border-gray-700 dark:text-gray-400">
                    <input id="mobile-is-active-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $slide->is_active) ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500">
                    Tampilkan slide
                </label>
                @error('is_active') <p class="ml-3 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            @if ($canReplaceActiveSlide)
                <div id="mobile-replace-active-wrapper" class="md:col-span-2 {{ $showReplacementField ? '' : 'hidden' }}">
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Nonaktifkan slide aktif
                    </label>
                    <select name="replace_active_id"
                        class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                        <option value="">Pilih slide yang digantikan</option>
                        @foreach ($replacementOptions as $activeSlide)
                            <option value="{{ $activeSlide->id }}" {{ old('replace_active_id') == $activeSlide->id ? 'selected' : '' }}>
                                #{{ $activeSlide->sort_order }} - {{ $activeSlide->title }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs text-gray-400">
                        Slot aktif sudah penuh. Pilihan ini akan dinonaktifkan otomatis saat slide ini diaktifkan.
                    </p>
                    @error('replace_active_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            @endif
        </div>
    </div>

    <div class="mt-6 flex items-center gap-3 border-t border-gray-200 pt-6 dark:border-gray-800">
        <button type="submit" class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white shadow-theme-xs transition-colors hover:bg-brand-600">
            {{ $submitLabel }}
        </button>
        <a href="{{ route('superadmin.settings.mobile') }}" class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
            Batal
        </a>
    </div>
</form>

@push('scripts')
    <script>
        document.getElementById('mobile-image-input')?.addEventListener('change', function () {
            const file = this.files?.[0];
            if (!file) return;

            const preview = document.getElementById('mobile-image-preview');
            const placeholder = document.getElementById('mobile-image-placeholder');

            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
            placeholder?.classList.add('hidden');
        });

        document.getElementById('mobile-is-active-input')?.addEventListener('change', function () {
            document.getElementById('mobile-replace-active-wrapper')?.classList.toggle('hidden', !this.checked);
        });
    </script>
@endpush

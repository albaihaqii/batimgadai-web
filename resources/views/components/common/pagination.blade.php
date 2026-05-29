@php
    $currentPage = $paginator->currentPage();
    $lastPage    = $paginator->lastPage();
    $window      = 2; // tampilkan 2 halaman di kiri dan kanan halaman aktif

    $start = max(1, $currentPage - $window);
    $end   = min($lastPage, $currentPage + $window);

    // Selalu tampilkan minimal 5 halaman kalau tersedia
    if ($end - $start < $window * 2) {
        if ($start === 1) {
            $end = min($lastPage, $start + $window * 2);
        } else {
            $start = max(1, $end - $window * 2);
        }
    }
@endphp

<div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-gray-500 dark:text-gray-400">
        Menampilkan {{ $paginator->firstItem() ?? 0 }}–{{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} data
    </p>
    <div class="flex items-center gap-1.5 flex-wrap">

        {{-- Sebelumnya --}}
        <a href="{{ $paginator->previousPageUrl() }}"
            class="flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 {{ $paginator->onFirstPage() ? 'opacity-40 pointer-events-none' : '' }}">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z" fill="currentColor"/></svg>
            <span class="hidden sm:inline">Sebelumnya</span>
        </a>

        {{-- Halaman pertama + ellipsis --}}
        @if($start > 1)
            <a href="{{ $paginator->url(1) }}"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-theme-sm font-medium text-gray-700 hover:bg-brand-500 hover:text-white dark:text-gray-400 transition-colors">
                1
            </a>
            @if($start > 2)
                <span class="flex h-9 w-9 items-center justify-center text-theme-sm text-gray-400">...</span>
            @endif
        @endif

        {{-- Halaman di window --}}
        @for($page = $start; $page <= $end; $page++)
            <a href="{{ $paginator->url($page) }}"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-theme-sm font-medium transition-colors
                {{ $page == $currentPage ? 'bg-brand-500 text-white' : 'text-gray-700 hover:bg-brand-500 hover:text-white dark:text-gray-400' }}">
                {{ $page }}
            </a>
        @endfor

        {{-- Ellipsis + halaman terakhir --}}
        @if($end < $lastPage)
            @if($end < $lastPage - 1)
                <span class="flex h-9 w-9 items-center justify-center text-theme-sm text-gray-400">...</span>
            @endif
            <a href="{{ $paginator->url($lastPage) }}"
                class="flex h-9 w-9 items-center justify-center rounded-lg text-theme-sm font-medium text-gray-700 hover:bg-brand-500 hover:text-white dark:text-gray-400 transition-colors">
                {{ $lastPage }}
            </a>
        @endif

        {{-- Selanjutnya --}}
        <a href="{{ $paginator->nextPageUrl() }}"
            class="flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 {{ !$paginator->hasMorePages() ? 'opacity-40 pointer-events-none' : '' }}">
            <span class="hidden sm:inline">Selanjutnya</span>
            <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z" fill="currentColor"/></svg>
        </a>

    </div>
</div>
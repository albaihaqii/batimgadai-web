@extends('layouts.app')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Mobile App</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Kelola daftar slide aplikasi mobile untuk API. Maksimal 4 slide boleh aktif bersamaan.
            </p>
        </div>
        <a href="{{ route('superadmin.settings.mobile.create') }}"
            class="inline-flex h-11 items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white shadow-theme-xs transition-colors hover:bg-brand-600">
            <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                <path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            Tambah Slide
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4 text-sm font-medium text-green-700 dark:border-green-500/20 dark:bg-green-500/10 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm font-medium text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-xs text-gray-500 dark:text-gray-400">Total Slide</p>
            <p class="mt-1 text-lg font-semibold text-gray-800 dark:text-white/90">{{ $slides->count() }}</p>
        </div>
        <div class="rounded-xl border border-green-100 bg-green-50 p-4 dark:border-green-500/20 dark:bg-green-500/5">
            <p class="text-xs text-green-600 dark:text-green-400">Slide Aktif</p>
            <p class="mt-1 text-lg font-semibold text-green-700 dark:text-green-400">{{ $activeCount }} / 4</p>
        </div>
        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-xs text-gray-500 dark:text-gray-400">Penyimpanan Gambar</p>
            <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-white/90">public/frontend/images</p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto">
            <table class="w-full min-w-[760px]">
                <thead class="border-y border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Urutan</th>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Gambar</th>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Judul</th>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                        <th class="px-5 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($slides as $slide)
                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-5 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">{{ $slide->sort_order }}</td>
                            <td class="px-5 py-3.5">
                                <img src="{{ asset($slide->image_path) }}" alt="{{ $slide->title }}" class="h-16 w-12 rounded-lg object-cover">
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="text-theme-sm font-semibold text-gray-800 dark:text-white/90">{{ $slide->title }}</p>
                                <p class="mt-0.5 line-clamp-1 max-w-md text-xs text-gray-400">{{ $slide->description ?: '-' }}</p>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium {{ $slide->is_active ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500' : 'bg-gray-100 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400' }}">
                                    {{ $slide->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('superadmin.settings.mobile.edit', $slide) }}" class="text-gray-400 transition-colors hover:text-gray-900 dark:hover:text-white">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('superadmin.settings.mobile.destroy', $slide) }}" onsubmit="return confirm('Hapus slide mobile ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 transition-colors hover:text-red-600 dark:hover:text-red-500">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                                Belum ada slide mobile.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

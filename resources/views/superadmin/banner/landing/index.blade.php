@extends('layouts.app')
@section('content')

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-2 gap-4 md:gap-6 mb-6">
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800 flex-shrink-0">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M3.25 6C3.25 4.48122 4.48122 3.25 6 3.25H18C19.5188 3.25 20.75 4.48122 20.75 6V18C20.75 19.5188 19.5188 20.75 18 20.75H6C4.48122 20.75 3.25 19.5188 3.25 18V6ZM6 4.75C5.30964 4.75 4.75 5.30964 4.75 6V18C4.75 18.6904 5.30964 19.25 6 19.25H18C18.6904 19.25 19.25 18.6904 19.25 18V6C19.25 5.30964 18.6904 4.75 18 4.75H6ZM3.25 9C3.25 8.58579 3.58579 8.25 4 8.25H20C20.4142 8.25 20.75 8.58579 20.75 9C20.75 9.41421 20.4142 9.75 20 9.75H4C3.58579 9.75 3.25 9.41421 3.25 9ZM7 12.25C6.58579 12.25 6.25 12.5858 6.25 13C6.25 13.4142 6.58579 13.75 7 13.75H14C14.4142 13.75 14.75 13.4142 14.75 13C14.75 12.5858 14.4142 12.25 14 12.25H7ZM6.25 16C6.25 15.5858 6.58579 15.25 7 15.25H10C10.4142 15.25 10.75 15.5858 10.75 16C10.75 16.4142 10.4142 16.75 10 16.75H7C6.58579 16.75 6.25 16.4142 6.25 16Z" fill="currentColor"/></svg>
            </div>
            <div class="flex-1">
                <span class="text-sm text-gray-500 dark:text-gray-400">Total Banner</span>
                <div class="flex items-end justify-between mt-1">
                    <h4 class="font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $total }}</h4>
                    <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400">Landing</span>
                </div>
            </div>
        </div>
    </div>
    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="flex items-center gap-4">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800 flex-shrink-0">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M19.916 4.626a.75.75 0 01.208 1.04l-9 13.5a.75.75 0 01-1.154.114l-6-6a.75.75 0 011.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 011.04-.208z" fill="currentColor"/></svg>
            </div>
            <div class="flex-1">
                <span class="text-sm text-gray-500 dark:text-gray-400">Sedang Aktif</span>
                <div class="flex items-end justify-between mt-1">
                    <h4 class="font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $aktif }}</h4>
                    <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500">Aktif</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Banner Landing Page</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Hero slider di halaman utama website</p>
        </div>
        <a href="{{ route('superadmin.banner.landing.create') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
            <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
            Tambah Banner
        </a>
    </div>

    <div class="max-w-full overflow-x-auto">
        <table class="w-full">
            <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Preview</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Judul</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Highlight</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Urutan</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($banners as $index => $banner)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">{{ $banners->firstItem() + $index }}</td>
                    <td class="px-6 py-3.5">
                        <img src="{{ asset('storage/' . $banner->foto) }}" alt="{{ $banner->judul }}"
                            class="w-24 h-14 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                    </td>
                    <td class="px-6 py-3.5">
                        <p class="font-medium text-theme-sm text-gray-800 dark:text-white/90">{{ $banner->judul }}</p>
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="text-theme-sm font-semibold text-[#1F5C3A] dark:text-[#B6D96C]">{{ $banner->subjudul ?? '-' }}</span>
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">{{ $banner->urutan }}</td>
                    <td class="px-6 py-3.5">
                        <form method="POST" action="{{ route('superadmin.banner.toggle', $banner->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="rounded-full px-2 py-0.5 text-theme-xs font-medium transition-colors {{ $banner->is_active ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500 hover:bg-success-100' : 'bg-gray-100 text-gray-500 dark:bg-gray-500/15 dark:text-gray-400 hover:bg-gray-200' }}">
                                {{ $banner->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-3.5">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('superadmin.banner.landing.edit', $banner->id) }}"
                                class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            <button onclick="openDeleteModal({{ $banner->id }}, '{{ $banner->judul }}')"
                                class="text-gray-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-10 text-center text-sm text-gray-400 dark:text-gray-500">Belum ada banner landing</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <x-common.pagination :paginator="$banners" />
</div>

<div id="modalHapus" class="hidden fixed inset-0 z-99999 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6 text-center">
            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-50 dark:bg-red-500/10 mx-auto mb-4">
                <svg class="text-red-500" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Hapus Banner</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Hapus banner <span id="deleteJudul" class="font-semibold text-gray-800 dark:text-white"></span>? Foto akan ikut terhapus.</p>
            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('modalHapus').classList.add('hidden')"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Batal</button>
                <form id="formHapus" method="POST" action="">
                    @csrf @method('DELETE')
                    <button type="submit" class="rounded-lg bg-red-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-600 transition-colors">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openDeleteModal(id, judul) {
    document.getElementById('deleteJudul').textContent = judul;
    document.getElementById('formHapus').action = '/superadmin/banner/' + id;
    document.getElementById('modalHapus').classList.remove('hidden');
}
</script>
@endpush
@endsection
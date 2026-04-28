@extends('layouts.app')
@section('content')

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400">
    {{ session('success') }}
</div>
@endif

{{-- Tarif Jasa Umum --}}
<div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03] mb-6">
    <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Tarif Jasa Umum</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Berlaku untuk semua kategori barang kecuali perhiasan</p>
        </div>
        <a href="{{ route('superadmin.jasa-rate.create') }}?tipe=umum"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            Tambah Rate
        </a>
    </div>
    <div class="max-w-full overflow-x-auto">
        <table class="w-full">
            <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Range Pinjaman</th>
                    <th class="px-6 py-3 text-center text-theme-xs font-medium text-gray-500 dark:text-gray-400">Jasa 1/2 Bulan (1-15 hari)</th>
                    <th class="px-6 py-3 text-center text-theme-xs font-medium text-gray-500 dark:text-gray-400">Jasa 1 Bulan (16-30 hari)</th>
                    <th class="px-6 py-3 text-center text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-6 py-3 text-center text-theme-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($umum as $idx => $rate)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">{{ $idx + 1 }}</td>
                    <td class="px-6 py-3.5 text-theme-sm font-medium text-gray-800 dark:text-white/90">
                        Rp {{ number_format($rate->min_pinjaman, 0, ',', '.') }}
                        @if($rate->max_pinjaman)
                            – Rp {{ number_format($rate->max_pinjaman, 0, ',', '.') }}
                        @else
                            ke atas
                        @endif
                    </td>
                    <td class="px-6 py-3.5 text-center">
                        <span class="font-semibold text-brand-500 text-theme-sm">{{ $rate->jasa_15_hari }}%</span>
                    </td>
                    <td class="px-6 py-3.5 text-center">
                        <span class="font-semibold text-brand-500 text-theme-sm">{{ $rate->jasa_30_hari }}%</span>
                    </td>
                    <td class="px-6 py-3.5 text-center">
                        <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium
                            {{ $rate->is_active ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500' }}">
                            {{ $rate->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('superadmin.jasa-rate.edit', $rate->id) }}"
                                class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            <button onclick="openDeleteModal({{ $rate->id }}, 'rate ini')"
                                class="text-gray-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                        Belum ada data tarif jasa umum
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Tarif Jasa Perhiasan --}}
<div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Tarif Jasa Khusus Perhiasan</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Berlaku untuk kategori perhiasan / emas</p>
        </div>
        <a href="{{ route('superadmin.jasa-rate.create') }}?tipe=perhiasan"
            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            Tambah Rate
        </a>
    </div>
    <div class="max-w-full overflow-x-auto">
        <table class="w-full">
            <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Range Pinjaman</th>
                    <th class="px-6 py-3 text-center text-theme-xs font-medium text-gray-500 dark:text-gray-400">Jasa 1/2 Bulan (1-15 hari)</th>
                    <th class="px-6 py-3 text-center text-theme-xs font-medium text-gray-500 dark:text-gray-400">Jasa 1 Bulan (16-30 hari)</th>
                    <th class="px-6 py-3 text-center text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-6 py-3 text-center text-theme-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($perhiasan as $idx => $rate)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">{{ $idx + 1 }}</td>
                    <td class="px-6 py-3.5 text-theme-sm font-medium text-gray-800 dark:text-white/90">
                        Rp {{ number_format($rate->min_pinjaman, 0, ',', '.') }}
                        @if($rate->max_pinjaman)
                            – Rp {{ number_format($rate->max_pinjaman, 0, ',', '.') }}
                        @else
                            ke atas
                        @endif
                    </td>
                    <td class="px-6 py-3.5 text-center">
                        <span class="font-semibold text-brand-500 text-theme-sm">{{ $rate->jasa_15_hari }}%</span>
                    </td>
                    <td class="px-6 py-3.5 text-center">
                        <span class="font-semibold text-brand-500 text-theme-sm">{{ $rate->jasa_30_hari }}%</span>
                    </td>
                    <td class="px-6 py-3.5 text-center">
                        <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium
                            {{ $rate->is_active ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500' }}">
                            {{ $rate->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('superadmin.jasa-rate.edit', $rate->id) }}"
                                class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            <button onclick="openDeleteModal({{ $rate->id }}, 'rate ini')"
                                class="text-gray-400 hover:text-red-600 dark:hover:text-red-500 transition-colors">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                        Belum ada data tarif jasa perhiasan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Hapus --}}
<div id="modalHapus" class="hidden fixed inset-0 z-99999 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6 text-center">
            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-50 dark:bg-red-500/10 mx-auto mb-4">
                <svg class="text-red-500" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Hapus Rate Jasa</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                Apakah Anda yakin ingin menghapus <span id="deleteNama" class="font-semibold text-gray-800 dark:text-white"></span>? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('modalHapus').classList.add('hidden')"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Batal
                </button>
                <form id="formHapus" method="POST" action="">
                    @csrf @method('DELETE')
                    <button type="submit"
                        class="rounded-lg bg-red-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-600 transition-colors">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openDeleteModal(id, nama) {
    document.getElementById('deleteNama').textContent = nama;
    document.getElementById('formHapus').action = `/superadmin/jasa-rate/${id}`;
    document.getElementById('modalHapus').classList.remove('hidden');
}
</script>
@endpush

@endsection
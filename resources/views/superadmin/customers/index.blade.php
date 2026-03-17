@extends('layouts.app')
@section('content')

{{-- Alert --}}
@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400">
    {{ session('success') }}
</div>
@endif

<div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- Header --}}
    <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Data Nasabah</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola data nasabah BATIM GADAI</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Search Realtime --}}
            <div class="relative">
                <span class="absolute -translate-y-1/2 left-4 top-1/2 pointer-events-none">
                    <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z" fill=""/>
                    </svg>
                </span>
                <input type="text" id="searchInput" value="{{ request('search') }}"
                    placeholder="Cari nama, No KTP, No HP, No CIF..."
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-12 pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 xl:w-[280px]">
            </div>

            {{-- Unduh --}}
            <a href="{{ route(auth()->user()->role . '.nasabah') }}?export=1&search={{ request('search') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                <svg class="stroke-current" width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M10 2.5V13.5M10 13.5L6 9.5M10 13.5L14 9.5M3 15.5H17" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Unduh
            </a>

            {{-- Tambah --}}
            <a href="{{ route(auth()->user()->role . '.nasabah.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
                Tambah Nasabah
            </a>
        </div>
    </div>

    {{-- Entries --}}
    <div class="px-6 mb-3">
        <form method="GET" action="{{ route(auth()->user()->role . '.nasabah') }}" id="entriesForm" class="flex items-center gap-2">
            <input type="hidden" name="search" id="hiddenSearch" value="{{ request('search') }}">
            <span class="text-sm text-gray-500 dark:text-gray-400">Tampilkan</span>
            <select name="per_page" onchange="this.form.submit()"
                class="h-9 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                @foreach([10, 20, 50] as $val)
                    <option value="{{ $val }}" {{ request('per_page', 10) == $val ? 'selected' : '' }}>{{ $val }}</option>
                @endforeach
            </select>
            <span class="text-sm text-gray-500 dark:text-gray-400">data</span>
        </form>
    </div>

    {{-- Table --}}
    <div class="max-w-full overflow-x-auto">
        <table class="w-full">
            <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No CIF</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nama Nasabah</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No KTP</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No HP</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Cabang</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Tgl Bergabung</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($customers as $index => $customer)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                        {{ $customers->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="font-medium text-theme-sm text-gray-800 dark:text-white/90">{{ $customer->no_cif }}</span>
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                        {{ $customer->nama }}
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">{{ $customer->no_ktp }}</td>
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">{{ $customer->no_hp }}</td>
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">{{ $customer->branch->nama ?? '-' }}</td>
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">{{ $customer->tgl_bergabung->format('d M Y') }}</td>
                    <td class="px-6 py-3.5">
                        <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium
                            {{ $customer->status === 'aktif'
                                ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500'
                                : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500' }}">
                            {{ ucfirst($customer->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5">
                        <div class="flex items-center gap-3">
                            {{-- Edit --}}
                            <a href="{{ route(auth()->user()->role . '.nasabah.edit', $customer->id) }}"
                                class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            {{-- Hapus --}}
                            <button onclick="openDeleteModal({{ $customer->id }}, '{{ $customer->nama }}')"
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
                    <td colspan="9" class="px-6 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                        Belum ada data nasabah
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer: Info + Pagination --}}
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        {{-- Info --}}
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Menampilkan {{ $customers->firstItem() ?? 0 }}–{{ $customers->lastItem() ?? 0 }} dari {{ $customers->total() }} data
        </p>

        {{-- Pagination --}}
        <div class="flex items-center gap-2">
            {{-- Sebelumnya --}}
            <a href="{{ $customers->previousPageUrl() }}"
                class="flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 {{ $customers->onFirstPage() ? 'opacity-40 pointer-events-none' : '' }}">
                <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z" fill="currentColor"/></svg>
                <span class="hidden sm:inline">Sebelumnya</span>
            </a>

            {{-- Page Numbers --}}
            @foreach($customers->getUrlRange(1, $customers->lastPage()) as $page => $url)
                <a href="{{ $url }}"
                    class="flex h-9 w-9 items-center justify-center rounded-lg text-theme-sm font-medium transition-colors mx-0.5
                    {{ $page == $customers->currentPage()
                        ? 'bg-brand-500 text-white'
                        : 'text-gray-700 hover:bg-brand-500 hover:text-white dark:text-gray-400' }}">
                    {{ $page }}
                </a>
            @endforeach

            {{-- Selanjutnya --}}
            <a href="{{ $customers->nextPageUrl() }}"
                class="flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 {{ !$customers->hasMorePages() ? 'opacity-40 pointer-events-none' : '' }}">
                <span class="hidden sm:inline">Selanjutnya</span>
                <svg width="16" height="16" viewBox="0 0 20 20" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z" fill="currentColor"/></svg>
            </a>
        </div>
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
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Hapus Data Nasabah</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                Apakah Anda yakin ingin menghapus data nasabah <span id="deleteNama" class="font-semibold text-gray-800 dark:text-white"></span>? Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex justify-center gap-3">
                <button onclick="document.getElementById('modalHapus').classList.add('hidden')"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Batal
                </button>
                <form id="formHapus" method="POST" action="">
                    @csrf
                    @method('DELETE')
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
// Search realtime dengan debounce
let searchTimeout;
document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    const value = this.value;
    searchTimeout = setTimeout(function() {
        const url = new URL(window.location.href);
        url.searchParams.set('search', value);
        url.searchParams.set('page', 1);
        window.location.href = url.toString();
    }, 800);
});

// Modal Hapus
function openDeleteModal(id, nama) {
    const role = '{{ auth()->user()->role }}';
    document.getElementById('deleteNama').textContent = nama;
    document.getElementById('formHapus').action = `/${role}/nasabah/${id}`;
    document.getElementById('modalHapus').classList.remove('hidden');
}
</script>
@endpush

@endsection
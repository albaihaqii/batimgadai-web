@extends('layouts.app')
@section('content')

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium dark:bg-red-500/10 dark:border-red-500/20 dark:text-red-400">
    {{ session('error') }}
</div>
@endif

<div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

    {{-- Header --}}
    <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Transaksi Pelunasan</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Riwayat pelunasan gadai nasabah</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative">
                <span class="absolute -translate-y-1/2 left-4 top-1/2 pointer-events-none">
                    <svg class="fill-gray-500 dark:fill-gray-400" width="20" height="20" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M3.04199 9.37381C3.04199 5.87712 5.87735 3.04218 9.37533 3.04218C12.8733 3.04218 15.7087 5.87712 15.7087 9.37381C15.7087 12.8705 12.8733 15.7055 9.37533 15.7055C5.87735 15.7055 3.04199 12.8705 3.04199 9.37381ZM9.37533 1.54218C5.04926 1.54218 1.54199 5.04835 1.54199 9.37381C1.54199 13.6993 5.04926 17.2055 9.37533 17.2055C11.2676 17.2055 13.0032 16.5346 14.3572 15.4178L17.1773 18.2381C17.4702 18.531 17.945 18.5311 18.2379 18.2382C18.5308 17.9453 18.5309 17.4704 18.238 17.1775L15.4182 14.3575C16.5367 13.0035 17.2087 11.2671 17.2087 9.37381C17.2087 5.04835 13.7014 1.54218 9.37533 1.54218Z"/>
                    </svg>
                </span>
                <input type="text" id="searchInput" value="{{ request('search') }}"
                    placeholder="Cari nasabah atau No SBG..."
                    class="h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pl-12 pr-4 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 xl:w-[260px]">
            </div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="px-6 mb-3">
        <form method="GET" action="{{ route(auth()->user()->role . '.transaksi.pelunasan') }}" class="flex flex-wrap items-center gap-3">
            <input type="hidden" name="search" id="hiddenSearch" value="{{ request('search') }}">

            @if(auth()->user()->role === 'superadmin')
            <div class="relative">
                <select name="cabang_id" onchange="this.form.submit()"
                    class="h-9 rounded-lg border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    <option value="">Semua Cabang</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ request('cabang_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="relative">
                <select name="status_bayar" onchange="this.form.submit()"
                    class="h-9 rounded-lg border border-gray-300 bg-white px-3 pr-8 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    <option value="">Semua Status</option>
                    <option value="menunggu" {{ request('status_bayar') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="berhasil" {{ request('status_bayar') == 'berhasil' ? 'selected' : '' }}>Berhasil</option>
                    <option value="gagal" {{ request('status_bayar') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                </select>
            </div>

            <select name="per_page" onchange="this.form.submit()"
                class="h-9 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 shadow-theme-xs dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                @foreach([10, 20, 50] as $val)
                    <option value="{{ $val }}" {{ request('per_page', 10) == $val ? 'selected' : '' }}>{{ $val }} data</option>
                @endforeach
            </select>

            @if(request()->filled('cabang_id') || request()->filled('search') || request()->filled('status_bayar') || request()->filled('per_page'))
            <a href="{{ route(auth()->user()->role . '.transaksi.pelunasan') }}"
                class="h-9 inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="max-w-full overflow-x-auto">
        <table class="w-full">
            <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No SBG</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nasabah</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Total Tebus</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Tgl Pelunasan</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Jatuh Tempo</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Metode</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-6 py-3 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($pelunasan as $index => $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                        {{ $pelunasan->firstItem() + $index }}
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm font-medium text-gray-800 dark:text-white/90">
                        {{ $item->no_sbg ?? '-' }}
                    </td>
                    <td class="px-6 py-3.5">
                        <div>
                            <p class="text-theme-sm font-medium text-gray-800 dark:text-white/90">{{ $item->nasabah->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $item->nasabah->no_cif ?? '-' }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm font-semibold text-brand-500">
                        Rp {{ number_format($item->total_tebus, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                        {{ $item->tgl_pelunasan ? $item->tgl_pelunasan->format('d M Y') : '-' }}
                    </td>
                    <td class="px-6 py-3.5 text-theme-sm text-gray-500 dark:text-gray-400">
                        {{ $item->tgl_jt ? $item->tgl_jt->format('d M Y') : '-' }}
                    </td>
                    <td class="px-6 py-3.5">
                        <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium
                            {{ $item->metode_bayar === 'tunai'
                                ? 'bg-gray-100 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400'
                                : 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400' }}">
                            {{ ucfirst($item->metode_bayar) }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5">
                        @php
                            $statusConfig = [
                                'menunggu' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
                                'berhasil' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                                'gagal'    => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                            ];
                        @endphp
                        <span class="rounded-full px-2 py-0.5 text-theme-xs font-medium {{ $statusConfig[$item->status_bayar] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($item->status_bayar) }}
                        </span>
                    </td>
                    <td class="px-6 py-3.5">
                        <a href="{{ route(auth()->user()->role . '.transaksi.pelunasan.show', $item->id) }}"
                            class="text-gray-400 hover:text-brand-500 dark:hover:text-brand-400 transition-colors">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                        Belum ada data pelunasan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer Pagination --}}
    <x-common.pagination :paginator="$pelunasan" />
</div>

@push('scripts')
<script>
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
    document.getElementById('hiddenSearch').value = this.value;
});
</script>
@endpush

@endsection
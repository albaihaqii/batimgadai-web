@extends('layouts.app')
@section('content')

<x-common.page-breadcrumb pageTitle="Pelunasan Gadai" />

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    <div class="lg:col-span-2 space-y-6">

        {{-- Card Info Gadai --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Informasi Gadai</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Detail gadai yang akan dilunasi.</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">No SBG</p>
                        <p class="text-sm font-bold text-brand-500 mt-1">{{ $gadai->no_sbg }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Nasabah</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white mt-1">{{ $gadai->nasabah->nama }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Barang</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->barang->nama_barang }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Loker</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->loker->kode_loker ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Nilai Pinjaman</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white mt-1">
                            Rp {{ number_format($gadai->nilai_pinjaman, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Jatuh Tempo</p>
                        <p class="text-sm font-semibold mt-1 {{ $gadai->tgl_jatuh_tempo->isPast() ? 'text-error-600' : 'text-gray-800 dark:text-white' }}">
                            {{ $gadai->tgl_jatuh_tempo->format('d M Y') }}
                            @if($gadai->tgl_jatuh_tempo->isPast())
                                <span class="text-xs">(Terlambat {{ $hitungan['hari_terlambat'] }} hari)</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Rincian Biaya --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Rincian Biaya Pelunasan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Perhitungan total tebus otomatis.</p>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2.5 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Pokok Pinjaman</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white">
                            Rp {{ number_format($hitungan['nilai_pinjaman'], 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2.5 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Biaya Jasa ({{ $hitungan['jasa_persen'] }}%)</span>
                        <span class="text-sm font-medium text-gray-800 dark:text-white">
                            Rp {{ number_format($hitungan['jasa_nominal'], 0, ',', '.') }}
                        </span>
                    </div>
                    @if($hitungan['denda_nominal'] > 0)
                    <div class="flex justify-between items-center py-2.5 border-b border-gray-100 dark:border-gray-800">
                        <span class="text-sm text-error-600">
                            Denda Keterlambatan ({{ $hitungan['denda_persen'] }}%)
                            <span class="text-xs block">{{ $hitungan['hari_terlambat'] }} hari terlambat</span>
                        </span>
                        <span class="text-sm font-medium text-error-600">
                            Rp {{ number_format($hitungan['denda_nominal'], 0, ',', '.') }}
                        </span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center py-2.5">
                        <span class="text-base font-bold text-gray-800 dark:text-white">Total Tebus</span>
                        <span class="text-base font-bold text-brand-500">
                            Rp {{ number_format($hitungan['total_tebus'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Pilih Metode --}}
        <form method="POST" action="{{ route(auth()->user()->role . '.transaksi.pelunasan.store') }}">
        @csrf
        <input type="hidden" name="gadai_id" value="{{ $gadai->id }}">

        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Metode Pembayaran</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Pilih metode pembayaran pelunasan.</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    {{-- Tunai --}}
                    <label class="relative flex cursor-pointer">
                        <input type="radio" name="metode_bayar" value="tunai" class="peer sr-only" checked>
                        <div class="w-full rounded-xl border-2 border-gray-200 bg-white p-5 peer-checked:border-brand-500 peer-checked:bg-brand-50 dark:border-gray-700 dark:bg-gray-900 dark:peer-checked:bg-brand-500/10 dark:peer-checked:border-brand-500 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gray-100 dark:bg-gray-800">
                                    <svg class="text-gray-600 dark:text-gray-400" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">Tunai</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Bayar langsung di outlet</p>
                                </div>
                            </div>
                        </div>
                    </label>

                    {{-- Midtrans --}}
                    <label class="relative flex cursor-pointer">
                        <input type="radio" name="metode_bayar" value="midtrans" class="peer sr-only">
                        <div class="w-full rounded-xl border-2 border-gray-200 bg-white p-5 peer-checked:border-brand-500 peer-checked:bg-brand-50 dark:border-gray-700 dark:bg-gray-900 dark:peer-checked:bg-brand-500/10 dark:peer-checked:border-brand-500 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-500/10">
                                    <svg class="text-blue-600" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-white">Midtrans</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Transfer / VA / E-Wallet</p>
                                </div>
                            </div>
                        </div>
                    </label>

                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3 px-6 py-5 border-t border-gray-200 dark:border-gray-800">
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                    Proses Pelunasan
                </button>
                <a href="{{ route(auth()->user()->role . '.transaksi.gadai.show', $gadai->id) }}"
                    class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Batal
                </a>
            </div>
        </div>

        </form>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Ketentuan Pelunasan</h4>
            <div class="space-y-4">
                @foreach([
                    ['num' => '1', 'text' => 'Nasabah membayar pokok + jasa + denda (jika ada)'],
                    ['num' => '2', 'text' => 'Barang jaminan dapat diambil setelah pembayaran lunas'],
                    ['num' => '3', 'text' => 'Loker otomatis dikosongkan setelah pelunasan'],
                    ['num' => '4', 'text' => 'SBG pelunasan diterbitkan sebagai bukti'],
                    ['num' => '5', 'text' => 'Status gadai berubah menjadi Lunas'],
                ] as $step)
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center mt-0.5">
                        <span class="text-xs font-bold text-brand-500">{{ $step['num'] }}</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $step['text'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Ringkasan Pembayaran</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Pokok</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">Rp {{ number_format($hitungan['nilai_pinjaman'], 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Jasa</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">Rp {{ number_format($hitungan['jasa_nominal'], 0, ',', '.') }}</span>
                </div>
                @if($hitungan['denda_nominal'] > 0)
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-error-600">Denda</span>
                    <span class="text-sm font-medium text-error-600">Rp {{ number_format($hitungan['denda_nominal'], 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm font-bold text-gray-800 dark:text-white">Total Tebus</span>
                    <span class="text-sm font-bold text-brand-500">Rp {{ number_format($hitungan['total_tebus'], 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
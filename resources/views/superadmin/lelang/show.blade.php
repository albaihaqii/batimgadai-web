@extends('layouts.app')
@section('content')

<x-common.page-breadcrumb pageTitle="Detail Lelang" />

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    <div class="lg:col-span-2 space-y-6">

        {{-- Status Card --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">{{ $lelang->no_sbg }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Masuk lelang sejak {{ $lelang->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>
                @php
                    $statusConfig = [
                        'proses'  => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
                        'selesai' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                        'batal'   => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                    ];
                @endphp
                <span class="rounded-full px-3 py-1 text-sm font-semibold {{ $statusConfig[$lelang->status] ?? '' }}">
                    {{ ucfirst($lelang->status) }}
                </span>
            </div>
        </div>

        {{-- Data Nasabah --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Data Nasabah</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-5">
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Nama</p><p class="text-sm font-semibold text-gray-800 dark:text-white mt-1">{{ $lelang->nasabah->nama ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">No CIF</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $lelang->nasabah->no_cif ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">No KTP</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $lelang->nasabah->no_ktp ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">No HP</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $lelang->nasabah->no_hp ?? '-' }}</p></div>
                    <div class="col-span-2"><p class="text-xs text-gray-500 dark:text-gray-400">Alamat</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $lelang->nasabah->alamat ?? '-' }}</p></div>
                </div>
            </div>
        </div>

        {{-- Data Barang --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Data Barang Jaminan</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-5">
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Nama Barang</p><p class="text-sm font-semibold text-gray-800 dark:text-white mt-1">{{ $lelang->gadai->barang->nama_barang ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Kategori</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $lelang->gadai->barang ? ucfirst(str_replace('_', ' ', $lelang->gadai->barang->kategori)) : '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Merk</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $lelang->gadai->barang->merk ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Tipe / Model</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $lelang->gadai->barang->tipe_model ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Kondisi</p>
                        <span class="inline-block mt-1 rounded-full px-2 py-0.5 text-theme-xs font-medium
                            {{ ($lelang->gadai->barang->kondisi ?? '') === 'baik' ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500' : 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400' }}">
                            {{ ucfirst(str_replace('_', ' ', $lelang->gadai->barang->kondisi ?? '-')) }}
                        </span>
                    </div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Kelengkapan</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $lelang->gadai->barang->kelengkapan ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Cabang</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $lelang->gadai->branch->nama ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Tgl Jatuh Tempo</p><p class="text-sm font-semibold text-error-600 dark:text-error-500 mt-1">{{ $lelang->tgl_jatuh_tempo->format('d M Y') }}</p></div>
                </div>
            </div>
        </div>

        {{-- Hasil Lelang (jika sudah selesai) --}}
        @if($lelang->status === 'selesai')
        <div class="rounded-2xl border border-success-200 bg-success-50 dark:border-success-500/20 dark:bg-success-500/10">
            <div class="px-6 py-5 border-b border-success-200 dark:border-success-500/20">
                <h3 class="text-base font-semibold text-success-700 dark:text-success-500">Hasil Lelang</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-5">
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Tanggal Lelang</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $lelang->tgl_lelang?->format('d M Y') ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Diproses oleh</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $lelang->diprosesOleh->nama ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Sisa Hutang</p><p class="text-sm font-semibold text-gray-800 dark:text-white mt-1">Rp {{ number_format($lelang->sisa_hutang, 0, ',', '.') }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Harga Terjual</p><p class="text-sm font-semibold text-success-600 dark:text-success-500 mt-1">Rp {{ number_format($lelang->harga_terjual, 0, ',', '.') }}</p></div>
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Selisih</p>
                        @php
                            $selisihClass = match($lelang->status_selisih) {
                                'lebih'  => 'text-success-600 dark:text-success-500',
                                'kurang' => 'text-error-600 dark:text-error-500',
                                default  => 'text-gray-600 dark:text-gray-400',
                            };
                            $selisihLabel = match($lelang->status_selisih) {
                                'lebih'  => 'Kelebihan — dikembalikan ke nasabah',
                                'kurang' => 'Kekurangan — perusahaan menanggung',
                                default  => 'Pas — sesuai hutang',
                            };
                        @endphp
                        <p class="text-sm font-semibold {{ $selisihClass }} mt-1">
                            Rp {{ number_format($lelang->selisih, 0, ',', '.') }}
                            <span class="text-xs font-normal ml-1">({{ $selisihLabel }})</span>
                        </p>
                    </div>
                    @if($lelang->keterangan)
                    <div class="col-span-2"><p class="text-xs text-gray-500 dark:text-gray-400">Keterangan</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $lelang->keterangan }}</p></div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Batal info --}}
        @if($lelang->status === 'batal')
        <div class="rounded-2xl border border-error-200 bg-error-50 dark:border-error-500/20 dark:bg-error-500/10 p-6">
            <p class="text-sm font-semibold text-error-600 dark:text-error-500 mb-2">Lelang Dibatalkan</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $lelang->keterangan ?? '-' }}</p>
        </div>
        @endif

    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">

        {{-- Ringkasan --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Ringkasan Keuangan</h4>
            <div class="space-y-0">
                <div class="flex items-center justify-between py-2.5 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Nilai Pinjaman</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">Rp {{ number_format($lelang->gadai->nilai_pinjaman ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Biaya Jasa</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">Rp {{ number_format($lelang->gadai->jasa_nominal ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Sisa Hutang</span>
                    <span class="text-sm font-bold text-error-600 dark:text-error-500">Rp {{ number_format($lelang->sisa_hutang, 0, ',', '.') }}</span>
                </div>
                @if($lelang->harga_terjual)
                <div class="flex items-center justify-between py-2.5 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Harga Terjual</span>
                    <span class="text-sm font-semibold text-success-600 dark:text-success-500">Rp {{ number_format($lelang->harga_terjual, 0, ',', '.') }}</span>
                </div>
                <div class="flex items-center justify-between py-2.5">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Selisih</span>
                    <span class="text-sm font-bold {{ $selisihClass ?? 'text-gray-800 dark:text-white' }}">
                        Rp {{ number_format($lelang->selisih ?? 0, 0, ',', '.') }}
                    </span>
                </div>
                @endif
            </div>
        </div>

        {{-- Aksi --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Aksi</h4>
            <div class="space-y-3">
                @if($lelang->status === 'proses')
                <button onclick="openProsesModal({{ $lelang->id }}, '{{ $lelang->no_sbg }}', {{ $lelang->sisa_hutang }})"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition-colors">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75"/></svg>
                    Proses Lelang
                </button>
                <button onclick="openBatalModal({{ $lelang->id }}, '{{ $lelang->no_sbg }}')"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-error-600 hover:bg-error-50 dark:border-gray-700 dark:bg-gray-800 transition-colors">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batalkan Lelang
                </button>
                @endif
                <a href="{{ route('superadmin.transaksi.gadai.show', $lelang->gadai_id) }}"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Lihat Detail Gadai
                </a>
                <a href="{{ route('superadmin.lelang') }}"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Kembali ke Daftar
                </a>
            </div>
        </div>

    </div>
</div>

{{-- Modal Proses Lelang --}}
<div id="modalProses" class="hidden fixed inset-0 z-99999 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
            <div>
                <h4 class="text-base font-semibold text-gray-800 dark:text-white">Proses Lelang</h4>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5" id="prosesSubtitle"></p>
            </div>
            <button onclick="document.getElementById('modalProses').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="formProses" method="POST" action="">
            @csrf
            <div class="p-6 space-y-4">
                <div class="rounded-xl bg-gray-50 dark:bg-gray-800 p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sisa Hutang Nasabah</p>
                    <p class="text-lg font-bold text-gray-800 dark:text-white mt-1" id="nilaiSisaHutang"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Tanggal Lelang <span class="text-red-500">*</span></label>
                    <input type="date" name="tgl_lelang" required value="{{ today()->toDateString() }}"
                        class="w-full h-11 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Harga Terjual (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_terjual" id="inputHargaTerjual" required min="0" placeholder="Masukkan harga terjual"
                        oninput="hitungSelisih()"
                        class="w-full h-11 rounded-lg border border-gray-300 bg-white px-3 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300">
                </div>
                <div id="previewSelisih" class="hidden rounded-xl p-4">
                    <div class="flex items-center gap-2">
                        <span id="iconSelisih" class="flex-shrink-0"></span>
                        <div>
                            <p class="text-sm font-medium" id="labelSelisih"></p>
                            <p class="text-base font-bold mt-0.5" id="nilaiSelisih"></p>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Keterangan</label>
                    <textarea name="keterangan" rows="3" placeholder="Keterangan tambahan (opsional)"
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"></textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex gap-3 justify-end">
                <button type="button" onclick="document.getElementById('modalProses').classList.add('hidden')"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Batal</button>
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-600 transition-colors">Simpan & Tandai Terjual</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Batal --}}
<div id="modalBatal" class="hidden fixed inset-0 z-99999 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6 text-center">
            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-red-50 dark:bg-red-500/10 mx-auto mb-4">
                <svg class="text-red-500" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
            </div>
            <h4 class="text-lg font-semibold text-gray-800 dark:text-white mb-2">Batalkan Lelang</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">No. SBG <span id="batalNoSbg" class="font-semibold text-gray-800 dark:text-white"></span></p>
            <form id="formBatal" method="POST" action="">
                @csrf
                <div class="mb-4 text-left">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Alasan Pembatalan <span class="text-red-500">*</span></label>
                    <textarea name="keterangan" rows="3" required placeholder="Masukkan alasan pembatalan..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"></textarea>
                </div>
                <div class="flex justify-center gap-3">
                    <button type="button" onclick="document.getElementById('modalBatal').classList.add('hidden')"
                        class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Tutup</button>
                    <button type="submit"
                        class="rounded-lg bg-red-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-600 transition-colors">Ya, Batalkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let sisaHutangGlobal = 0;

function openProsesModal(id, noSbg, sisaHutang) {
    sisaHutangGlobal = sisaHutang;
    document.getElementById('prosesSubtitle').textContent = 'No. SBG: ' + noSbg;
    document.getElementById('nilaiSisaHutang').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(sisaHutang);
    document.getElementById('formProses').action = '/superadmin/lelang/' + id + '/proses';
    document.getElementById('inputHargaTerjual').value = '';
    document.getElementById('previewSelisih').classList.add('hidden');
    document.getElementById('modalProses').classList.remove('hidden');
}

function hitungSelisih() {
    const harga   = parseFloat(document.getElementById('inputHargaTerjual').value) || 0;
    const selisih = harga - sisaHutangGlobal;
    const preview = document.getElementById('previewSelisih');
    const icon    = document.getElementById('iconSelisih');
    const label   = document.getElementById('labelSelisih');
    const nilai   = document.getElementById('nilaiSelisih');

    if (harga <= 0) { preview.classList.add('hidden'); return; }
    preview.classList.remove('hidden');

    if (selisih > 0) {
        preview.className = 'rounded-xl p-4 bg-success-50 dark:bg-success-500/10';
        icon.innerHTML = '<svg class="text-success-500" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        label.className = 'text-sm font-medium text-success-600 dark:text-success-500';
        label.textContent = 'Kelebihan — dikembalikan ke nasabah';
        nilai.className = 'text-base font-bold text-success-600 dark:text-success-500 mt-0.5';
        nilai.textContent = '+Rp ' + new Intl.NumberFormat('id-ID').format(selisih);
    } else if (selisih < 0) {
        preview.className = 'rounded-xl p-4 bg-error-50 dark:bg-error-500/10';
        icon.innerHTML = '<svg class="text-error-500" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
        label.className = 'text-sm font-medium text-error-600 dark:text-error-500';
        label.textContent = 'Kekurangan — perusahaan menanggung kerugian';
        nilai.className = 'text-base font-bold text-error-600 dark:text-error-500 mt-0.5';
        nilai.textContent = '-Rp ' + new Intl.NumberFormat('id-ID').format(Math.abs(selisih));
    } else {
        preview.className = 'rounded-xl p-4 bg-gray-50 dark:bg-gray-800';
        icon.innerHTML = '<svg class="text-gray-500" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
        label.className = 'text-sm font-medium text-gray-600 dark:text-gray-400';
        label.textContent = 'Pas — harga terjual sesuai hutang';
        nilai.className = 'text-base font-bold text-gray-600 dark:text-gray-400 mt-0.5';
        nilai.textContent = 'Rp 0';
    }
}

function openBatalModal(id, noSbg) {
    document.getElementById('batalNoSbg').textContent = noSbg;
    document.getElementById('formBatal').action = '/superadmin/lelang/' + id + '/batal';
    document.getElementById('modalBatal').classList.remove('hidden');
}
</script>
@endpush

@endsection
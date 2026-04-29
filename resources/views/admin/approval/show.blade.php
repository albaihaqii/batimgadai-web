@extends('layouts.app')
@section('content')

<x-common.page-breadcrumb pageTitle="Detail Pengajuan Gadai" />

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    <div class="lg:col-span-2 space-y-6">

        {{-- Card Status --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">
                        Pengajuan Gadai — {{ $gadai->nasabah->nama ?? '-' }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Diajukan oleh {{ $gadai->officer->nama ?? '-' }} pada {{ $gadai->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>
                @php
                    $statusConfig = [
                        'menunggu_approval' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
                        'aktif'             => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                        'ditolak'           => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                    ];
                @endphp
                <span class="rounded-full px-3 py-1 text-sm font-semibold {{ $statusConfig[$gadai->status] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst(str_replace('_', ' ', $gadai->status)) }}
                </span>
            </div>
        </div>

        {{-- Card Data Nasabah --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Data Nasabah</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-5">
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Nama</p><p class="text-sm font-semibold text-gray-800 dark:text-white mt-1">{{ $gadai->nasabah->nama ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">No CIF</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->nasabah->no_cif ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">No KTP</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->nasabah->no_ktp ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">No HP</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->nasabah->no_hp ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Alamat</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->nasabah->alamat ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Cabang</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->branch->nama ?? '-' }}</p></div>
                </div>
            </div>
        </div>

        {{-- Card Data Barang --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Data Barang Jaminan</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    {{-- Foto dari barang_fotos --}}
                    @if($gadai->barang && $gadai->barang->fotos && $gadai->barang->fotos->count() > 0)
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Foto Barang ({{ $gadai->barang->fotos->count() }} foto)</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach($gadai->barang->fotos as $idx => $foto)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $foto->foto_path) }}" alt="Foto {{ $idx+1 }}"
                                    class="w-28 h-28 object-cover rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer"
                                    onclick="openLightbox('{{ asset('storage/' . $foto->foto_path) }}')">
                                <span class="absolute top-1 left-1 w-5 h-5 rounded-full bg-brand-500 text-white text-xs flex items-center justify-center font-bold">{{ $idx+1 }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @elseif($gadai->barang && $gadai->barang->foto)
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Foto Barang</p>
                        <img src="{{ asset('storage/' . $gadai->barang->foto) }}" alt="Foto Barang"
                            class="w-28 h-28 object-cover rounded-xl border border-gray-200 dark:border-gray-700">
                    </div>
                    @endif

                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Nama Barang</p><p class="text-sm font-semibold text-gray-800 dark:text-white mt-1">{{ $gadai->barang->nama_barang ?? '-' }}</p></div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Kategori</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->barang ? ucfirst(str_replace('_', ' ', $gadai->barang->kategori)) : '-' }}</p>
                    </div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Merk</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->barang->merk ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Tipe / Model</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->barang->tipe_model ?? '-' }}</p></div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Kondisi</p>
                        <span class="inline-block mt-1 rounded-full px-2 py-0.5 text-theme-xs font-medium
                            {{ $gadai->barang && $gadai->barang->kondisi === 'baik' ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500' : ($gadai->barang && $gadai->barang->kondisi === 'cukup' ? 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500') }}">
                            {{ $gadai->barang ? ucfirst(str_replace('_', ' ', $gadai->barang->kondisi)) : '-' }}
                        </span>
                    </div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Kelengkapan</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->barang->kelengkapan ?? '-' }}</p></div>
                </div>
            </div>
        </div>

        {{-- Card Taksiran --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Nilai Taksiran</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-5">
                    <div class="col-span-2">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Taksiran Awal (Range Petugas)</p>
                        <p class="text-lg font-bold text-gray-800 dark:text-white mt-1">
                            Rp {{ number_format($gadai->nilai_taksiran_min, 0, ',', '.') }} – Rp {{ number_format($gadai->nilai_taksiran_max, 0, ',', '.') }}
                        </p>
                    </div>
                    @if($gadai->approval && $gadai->approval->nilai_final)
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Nilai Final (Admin)</p>
                        <p class="text-lg font-bold text-brand-500 mt-1">Rp {{ number_format($gadai->approval->nilai_final, 0, ',', '.') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Catatan approval jika sudah diproses --}}
        @if($gadai->approval && $gadai->approval->status !== 'menunggu' && $gadai->approval->catatan)
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Catatan Approval</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-5">
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Diproses oleh</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->admin->nama ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Tanggal Diproses</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->approval->tgl_diproses ? $gadai->approval->tgl_diproses->format('d M Y, H:i') : '-' }} WIB</p></div>
                    <div class="col-span-2"><p class="text-xs text-gray-500 dark:text-gray-400">Catatan</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->approval->catatan }}</p></div>
                </div>
            </div>
        </div>
        @endif

        {{-- Form Approval --}}
        @if($gadai->status === 'menunggu_approval')
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Proses Approval</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Isi nilai pinjaman final dan pilih loker untuk menyetujui pengajuan.</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route(auth()->user()->role . '.approval.gadai.proses', $gadai->id) }}">
                @csrf
                <div class="space-y-5">

                    {{-- Nilai Pinjaman Final --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Nilai Pinjaman Final <span class="text-red-500">*</span>
                            <span class="text-gray-400 font-normal ml-1">(dalam range Rp {{ number_format($gadai->nilai_taksiran_min, 0, ',', '.') }} – Rp {{ number_format($gadai->nilai_taksiran_max, 0, ',', '.') }})</span>
                        </label>
                        <div class="relative">
                            <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 pointer-events-none font-medium">Rp</span>
                            <input type="number" name="nilai_final" id="nilaiInput"
                                placeholder="0" min="1"
                                oninput="hitungPreview(this.value)"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('nilai_final') ? 'border-red-500' : '' }}">
                        </div>
                        @error('nilai_final') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

                        {{-- Preview AJAX --}}
                        <div id="previewHitung" class="hidden mt-4 p-4 rounded-xl bg-brand-50 border border-brand-100 dark:bg-brand-500/10 dark:border-brand-500/20">
                            <div class="flex items-center gap-2 mb-3">
                                <div id="preview-loading" class="hidden">
                                    <svg class="animate-spin text-brand-500" width="14" height="14" viewBox="0 0 24 24" fill="none">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-brand-600 dark:text-brand-400">Preview Perhitungan Jasa</p>
                            </div>
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Nilai Pinjaman</span>
                                    <span class="font-semibold text-gray-800 dark:text-white" id="previewPinjaman">Rp -</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Jasa (<span id="previewJasaPersen">-</span>%)</span>
                                    <span class="font-medium text-gray-800 dark:text-white" id="previewJasa">Rp -</span>
                                </div>
                                <div class="flex justify-between text-sm border-t border-brand-200 dark:border-brand-500/20 pt-2">
                                    <span class="font-semibold text-gray-700 dark:text-gray-300">Total Tebus</span>
                                    <span class="font-bold text-brand-500" id="previewTotal">Rp -</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-400">Jatuh Tempo</span>
                                    <span class="font-medium text-gray-800 dark:text-white" id="previewJT">-</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pilih Loker --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Pilih Loker <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select name="loker_id"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 {{ $errors->has('loker_id') ? 'border-red-500' : '' }}">
                                <option value="">Pilih Loker Kosong...</option>
                                @foreach($lokers as $loker)
                                    <option value="{{ $loker->id }}">{{ $loker->kode_loker }} — Rak {{ $loker->rak }}@if($loker->keterangan) ({{ $loker->keterangan }})@endif</option>
                                @endforeach
                            </select>
                            <span class="absolute top-1/2 right-4 -translate-y-1/2 pointer-events-none text-gray-500"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        </div>
                        @if($lokers->isEmpty())
                        <p class="text-xs text-error-500 mt-1">⚠ Tidak ada loker kosong. Tambahkan loker terlebih dahulu.</p>
                        @endif
                        @error('loker_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Catatan --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <textarea name="catatan" rows="3" placeholder="Tambahkan catatan keputusan approval..."
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('catatan') }}</textarea>
                    </div>

                </div>

                <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-800">
                    <button type="submit" name="aksi" value="setujui"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Setujui
                    </button>
                    <button type="submit" name="aksi" value="tolak"
                        onclick="return confirm('Yakin ingin menolak pengajuan ini?')"
                        class="inline-flex items-center gap-2 rounded-lg bg-error-500 px-6 py-2.5 text-sm font-semibold text-white shadow-theme-xs hover:bg-error-600 transition-colors">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Tolak
                    </button>
                    <a href="{{ route(auth()->user()->role . '.approval.gadai') }}"
                        class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        Kembali
                    </a>
                </div>

                </form>
            </div>
        </div>
        @else
        <div class="flex items-center gap-3">
            @if(in_array($gadai->status, ['aktif', 'jatuh_tempo', 'perpanjangan', 'lunas']))
            <a href="{{ route(auth()->user()->role . '.transaksi.gadai.sbg', $gadai->id) }}" target="_blank"
                class="inline-flex items-center gap-2 rounded-lg bg-success-500 px-6 py-2.5 text-sm font-semibold text-white shadow-theme-xs hover:bg-success-600 transition-colors">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.056 48.056 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/></svg>
                Cetak SBG
            </a>
            @endif
            <a href="{{ route(auth()->user()->role . '.approval.gadai') }}"
                class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Kembali ke Daftar
            </a>
        </div>
        @endif

    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Info Pengajuan</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Cabang</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $gadai->branch->nama ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Petugas</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $gadai->officer->nama ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Tgl Pengajuan</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $gadai->created_at->format('d M Y') }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400 flex-shrink-0">Taksiran Awal</span>
                    <span class="text-sm font-semibold text-gray-800 dark:text-white text-right ml-2">
                        Rp {{ number_format($gadai->nilai_taksiran_min, 0, ',', '.') }} – Rp {{ number_format($gadai->nilai_taksiran_max, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        @if($gadai->status === 'menunggu_approval')
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-3">
                Loker Kosong Tersedia
                <span class="ml-2 rounded-full bg-brand-50 px-2 py-0.5 text-xs font-medium text-brand-500 dark:bg-brand-500/10">{{ $lokers->count() }} loker</span>
            </h4>
            @if($lokers->isEmpty())
            <p class="text-sm text-gray-400">Tidak ada loker kosong.</p>
            @else
            <div class="space-y-1.5 max-h-48 overflow-y-auto">
                @foreach($lokers as $loker)
                <div class="flex items-center justify-between py-1">
                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $loker->kode_loker }}</span>
                    <span class="text-xs text-gray-400">Rak {{ $loker->rak }}</span>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endif

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Ketentuan Biaya</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Jasa gadai</span>
                    <span class="text-sm font-semibold text-gray-800 dark:text-white">Berdasarkan nilai</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Masa gadai</span>
                    <span class="text-sm font-semibold text-gray-800 dark:text-white">30 hari</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Denda 1-15 hari</span>
                    <span class="text-sm font-semibold text-warning-600">Jasa 1/2 bulan</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Denda 16-30 hari</span>
                    <span class="text-sm font-semibold text-error-600">Jasa 1 bulan</span>
                </div>
            </div>
            <a href="{{ route('superadmin.jasa-rate') }}" target="_blank"
                class="mt-4 inline-flex items-center gap-1.5 text-xs text-brand-500 hover:text-brand-600 transition-colors">
                Lihat tabel perhitungan jasa →
            </a>
        </div>

    </div>
</div>

{{-- Lightbox --}}
<div id="lightbox" class="hidden fixed inset-0 z-[999] bg-black/80 flex items-center justify-center p-4"
    onclick="document.getElementById('lightbox').classList.add('hidden')">
    <img id="lightbox-img" src="" alt="" class="max-w-full max-h-full rounded-xl shadow-2xl">
</div>

@push('scripts')
<script>
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.remove('hidden');
}

let previewTimeout;
function hitungPreview(nilai) {
    clearTimeout(previewTimeout);
    const preview = document.getElementById('previewHitung');
    const loading = document.getElementById('preview-loading');

    if (!nilai || nilai <= 0) {
        preview.classList.add('hidden');
        return;
    }

    preview.classList.remove('hidden');
    loading.classList.remove('hidden');

    const kategori = '{{ $gadai->barang->kategori ?? "handphone" }}';
    const tipe     = kategori === 'perhiasan' ? 'perhiasan' : 'umum';

    previewTimeout = setTimeout(() => {
        fetch('/api/preview-jasa-rate?nilai=' + nilai + '&tipe=' + tipe)
            .then(r => r.json())
            .then(data => {
                loading.classList.add('hidden');

                const pinjaman = parseInt(nilai);
                const jt       = new Date();
                jt.setDate(jt.getDate() + 30);

                const f = (n) => 'Rp ' + parseInt(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                const d = (dt) => dt.toLocaleDateString('id-ID', {day:'2-digit', month:'long', year:'numeric'});

                document.getElementById('previewPinjaman').textContent   = f(pinjaman);
                document.getElementById('previewJasaPersen').textContent = data.jasa_30_hari;
                document.getElementById('previewJasa').textContent       = f(data.jasa_nominal_30);
                document.getElementById('previewTotal').textContent      = f(data.total_tebus_30);
                document.getElementById('previewJT').textContent        = d(jt);
            })
            .catch(() => {
                loading.classList.add('hidden');
            });
    }, 400);
}
</script>
@endpush

@endsection
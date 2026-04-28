@extends('layouts.app')
@section('content')

<x-common.page-breadcrumb pageTitle="Detail Transaksi Gadai" />

{{-- Modal Konfirmasi Tambah Pinjaman --}}
<div id="modalTambahPinjaman" class="hidden fixed inset-0 z-99999 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-warning-50 dark:bg-warning-500/10 flex items-center justify-center">
                    <svg class="text-warning-500" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                </div>
                <div>
                    <h4 class="text-base font-semibold text-gray-800 dark:text-white">Konfirmasi Tambah Pinjaman</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Periksa ringkasan sebelum memproses</p>
                </div>
            </div>
            <div id="modalTambahSummary" class="mb-5 p-4 rounded-xl bg-gray-50 dark:bg-gray-800 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Pinjaman saat ini</span>
                    <span class="font-medium text-gray-800 dark:text-white" id="mtp-saat-ini">-</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Jumlah tambahan</span>
                    <span class="font-medium text-warning-600" id="mtp-tambahan">-</span>
                </div>
                <div class="flex justify-between text-sm border-t border-gray-200 dark:border-gray-700 pt-2">
                    <span class="font-semibold text-gray-700 dark:text-gray-300">Total pinjaman baru</span>
                    <span class="font-bold text-brand-500" id="mtp-total">-</span>
                </div>
                <p class="text-xs text-gray-400 pt-1">*Jasa dan total tebus akan diperbarui otomatis sesuai range perhitungan</p>
            </div>
            <div class="flex justify-end gap-3">
                <button onclick="tutupModalTambah()"
                    class="rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">Batal</button>
                <button onclick="submitTambahPinjaman()"
                    class="rounded-lg bg-warning-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-warning-600 transition-colors">Ya, Tambah Pinjaman</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Sukses --}}
<div id="modalSukses" class="hidden fixed inset-0 z-99999 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-sm mx-4">
        <div class="p-6 text-center">
            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-success-50 dark:bg-success-500/10 mx-auto mb-4">
                <svg class="text-success-500" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h4 class="text-base font-semibold text-gray-800 dark:text-white mb-1">Berhasil!</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-5" id="modalSuksesMsg">Pinjaman berhasil ditambahkan.</p>
            <button onclick="window.location.reload()"
                class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-600 transition-colors">OK</button>
        </div>
    </div>
</div>

{{-- Modal Gagal --}}
<div id="modalGagal" class="hidden fixed inset-0 z-99999 flex items-center justify-center bg-black/50">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-sm mx-4">
        <div class="p-6 text-center">
            <div class="flex items-center justify-center w-14 h-14 rounded-full bg-error-50 dark:bg-error-500/10 mx-auto mb-4">
                <svg class="text-error-500" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <h4 class="text-base font-semibold text-gray-800 dark:text-white mb-1">Gagal</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-5" id="modalGagalMsg">Terjadi kesalahan.</p>
            <button onclick="document.getElementById('modalGagal').classList.add('hidden')"
                class="rounded-lg bg-error-500 px-6 py-2.5 text-sm font-semibold text-white hover:bg-error-600 transition-colors">Tutup</button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    <div class="lg:col-span-2 space-y-6">

        {{-- Card Status --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">{{ $gadai->no_sbg ?? 'Belum ada No SBG' }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Diinput oleh {{ $gadai->officer->nama ?? '-' }} pada {{ $gadai->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>
                @php
                    $statusConfig = [
                        'menunggu_approval' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
                        'aktif'             => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                        'ditolak'           => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                        'jatuh_tempo'       => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                        'perpanjangan'      => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                        'lunas'             => 'bg-gray-100 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
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
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    @if($gadai->barang && $gadai->barang->fotos && $gadai->barang->fotos->count() > 0)
                    <div class="md:col-span-2">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Foto Barang ({{ $gadai->barang->fotos->count() }} foto)</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach($gadai->barang->fotos as $idx => $foto)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $foto->foto_path) }}" alt="Foto {{ $idx+1 }}"
                                    class="w-24 h-24 object-cover rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer hover:opacity-90 transition-opacity"
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
                            class="w-24 h-24 object-cover rounded-xl border border-gray-200 dark:border-gray-700 cursor-pointer"
                            onclick="openLightbox('{{ asset('storage/' . $gadai->barang->foto) }}')">
                    </div>
                    @endif

                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Nama Barang</p><p class="text-sm font-semibold text-gray-800 dark:text-white mt-1">{{ $gadai->barang->nama_barang ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Kategori</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->barang ? ucfirst(str_replace('_', ' ', $gadai->barang->kategori)) : '-' }}</p></div>
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

        {{-- Card Detail Transaksi --}}
        @if($gadai->status !== 'menunggu_approval' && $gadai->status !== 'ditolak')
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Detail Transaksi</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Taksiran Awal (Range)</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">
                            Rp {{ number_format($gadai->nilai_taksiran_min, 0, ',', '.') }} – Rp {{ number_format($gadai->nilai_taksiran_max, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Nilai Pinjaman (Final)</p>
                        <p class="text-sm font-semibold text-brand-500 mt-1" id="detail-nilai-pinjaman">
                            Rp {{ number_format($gadai->nilai_pinjaman ?? 0, 0, ',', '.') }}
                        </p>
                        @if($gadai->nilai_pinjaman_tambahan > 0)
                        <p class="text-xs text-warning-600 mt-0.5">
                            +Rp {{ number_format($gadai->nilai_pinjaman_tambahan, 0, ',', '.') }} (tambahan)
                        </p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400" id="detail-label-jasa">Jasa ({{ $gadai->jasa_persen ?? 5 }}%)</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white mt-1" id="detail-jasa-nominal">
                            Rp {{ number_format($gadai->jasa_nominal ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Tebus</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white mt-1" id="detail-total-tebus">
                            Rp {{ number_format($gadai->total_tebus ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Tanggal Gadai</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">
                            {{ $gadai->tgl_gadai ? $gadai->tgl_gadai->format('d M Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Jatuh Tempo</p>
                        @php
                            $tglJt    = $gadai->tgl_jatuh_tempo;
                            $sudahJt  = $tglJt && $tglJt->isPast();
                            $hariSisa = $tglJt ? (int) now()->diffInDays($tglJt, false) : null;
                            // Prioritas warna
                            if ($sudahJt) {
                                $jtColor   = 'text-error-600 dark:text-error-500';
                                $badgeColor = 'text-error-500';
                                $badgeText  = 'Telat ' . abs($hariSisa) . ' hari';
                            } elseif ($hariSisa !== null && $hariSisa <= 1) {
                                $jtColor   = 'text-error-600 dark:text-error-500';
                                $badgeColor = 'text-error-500';
                                $badgeText  = $hariSisa == 0 ? 'Hari ini!' : $hariSisa . ' hari lagi';
                            } elseif ($hariSisa !== null && $hariSisa <= 3) {
                                $jtColor   = 'text-error-600 dark:text-error-500';
                                $badgeColor = 'text-error-500';
                                $badgeText  = $hariSisa . ' hari lagi';
                            } elseif ($hariSisa !== null && $hariSisa <= 7) {
                                $jtColor   = 'text-warning-600 dark:text-orange-400';
                                $badgeColor = 'text-warning-600 dark:text-orange-400';
                                $badgeText  = $hariSisa . ' hari lagi';
                            } else {
                                $jtColor   = 'text-gray-800 dark:text-white';
                                $badgeColor = 'text-gray-400';
                                $badgeText  = $hariSisa . ' hari lagi';
                            }
                        @endphp
                        @if($tglJt)
                        <p class="text-sm font-semibold mt-1 {{ $jtColor }} flex items-center gap-1.5 flex-wrap">
                            <span>{{ $tglJt->format('d M Y') }}</span>
                            <span class="text-xs font-medium {{ $badgeColor }}">({{ $badgeText }})</span>
                        </p>
                        @else
                        <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">-</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Loker</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->loker->kode_loker ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Disetujui oleh</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->admin->nama ?? '-' }}</p>
                    </div>
                </div>

                {{-- Riwayat tambah pinjaman --}}
                @if($gadai->nilai_pinjaman_tambahan > 0)
                <div class="mt-5 p-4 rounded-xl bg-warning-50 border border-warning-200 dark:bg-warning-500/10 dark:border-warning-500/20">
                    <p class="text-xs font-semibold text-warning-700 dark:text-warning-400 mb-3">Riwayat Tambah Pinjaman</p>
                    <div class="grid grid-cols-2 gap-3">
                        <div><p class="text-xs text-gray-500">Pinjaman Awal</p><p class="text-sm font-medium text-gray-800 dark:text-white">Rp {{ number_format($gadai->nilai_pinjaman_awal ?? 0, 0, ',', '.') }}</p></div>
                        <div><p class="text-xs text-gray-500">Total Tambahan</p><p class="text-sm font-medium text-warning-600">+Rp {{ number_format($gadai->nilai_pinjaman_tambahan, 0, ',', '.') }}</p></div>
                    </div>
                    @if($gadai->catatan_tambahan_pinjaman)
                    <p class="text-xs text-gray-500 mt-2">Catatan: {{ $gadai->catatan_tambahan_pinjaman }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Card Ditolak --}}
        @if($gadai->status === 'ditolak' && $gadai->approval)
        <div class="rounded-2xl border border-error-200 bg-error-50 dark:border-error-500/20 dark:bg-error-500/10">
            <div class="px-6 py-5 border-b border-error-200 dark:border-error-500/20">
                <h3 class="text-base font-semibold text-error-600 dark:text-error-500">Pengajuan Ditolak</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-5">
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Diproses oleh</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->admin->nama ?? '-' }}</p></div>
                    <div><p class="text-xs text-gray-500 dark:text-gray-400">Tanggal Diproses</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->approval->tgl_diproses ? $gadai->approval->tgl_diproses->format('d M Y, H:i') : '-' }} WIB</p></div>
                    <div class="col-span-2"><p class="text-xs text-gray-500 dark:text-gray-400">Catatan</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $gadai->approval->catatan ?? '-' }}</p></div>
                </div>
            </div>
        </div>
        @endif

        {{-- Card Menunggu --}}
        @if($gadai->status === 'menunggu_approval')
        <div class="rounded-2xl border border-warning-200 bg-warning-50 dark:border-warning-500/20 dark:bg-warning-500/10 p-6">
            <div class="flex items-center gap-3">
                <svg class="text-warning-600 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                <div>
                    <p class="text-sm font-semibold text-warning-700 dark:text-orange-400">Menunggu Approval Admin</p>
                    <p class="text-xs text-warning-600 dark:text-orange-300 mt-0.5">Pengajuan sedang dalam antrian approval.</p>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">

        {{-- Ringkasan Nilai --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Ringkasan Nilai</h4>
            <div class="space-y-0">
                {{-- Taksiran Awal — 1 baris horizontal --}}
                <div class="flex items-center justify-between py-2.5 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400 flex-shrink-0">Taksiran Awal</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white text-right ml-2">
                        Rp {{ number_format($gadai->nilai_taksiran_min, 0, ',', '.') }} – Rp {{ number_format($gadai->nilai_taksiran_max, 0, ',', '.') }}
                    </span>
                </div>
                {{-- Nilai Pinjaman --}}
                <div class="flex items-center justify-between py-2.5 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Nilai Pinjaman</span>
                    <span class="text-sm font-semibold text-brand-500" id="sb-nilai-pinjaman">
                        Rp {{ number_format($gadai->nilai_pinjaman ?? 0, 0, ',', '.') }}
                    </span>
                </div>
                {{-- Pinjaman Tambahan — hanya muncul jika ada --}}
                @if($gadai->nilai_pinjaman_tambahan > 0)
                <div class="flex items-center justify-between py-2.5 border-b border-gray-100 dark:border-gray-800" id="sb-row-tambahan">
                    <span class="text-sm text-warning-600">+ Pinjaman Tambahan</span>
                    <span class="text-sm font-medium text-warning-600" id="sb-pinjaman-tambahan">
                        Rp {{ number_format($gadai->nilai_pinjaman_tambahan, 0, ',', '.') }}
                    </span>
                </div>
                @else
                <div class="hidden py-2.5 border-b border-gray-100 dark:border-gray-800 items-center justify-between" id="sb-row-tambahan">
                    <span class="text-sm text-warning-600">+ Pinjaman Tambahan</span>
                    <span class="text-sm font-medium text-warning-600" id="sb-pinjaman-tambahan">Rp 0</span>
                </div>
                @endif
                {{-- Jasa --}}
                <div class="flex items-center justify-between py-2.5 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400" id="sb-label-jasa">Jasa ({{ $gadai->jasa_persen ?? 5 }}%)</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white" id="sb-jasa-nominal">
                        Rp {{ number_format($gadai->jasa_nominal ?? 0, 0, ',', '.') }}
                    </span>
                </div>
                {{-- Total Tebus --}}
                <div class="flex items-center justify-between py-2.5">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Total Tebus</span>
                    <span class="text-sm font-bold text-gray-800 dark:text-white" id="sb-total-tebus">
                        Rp {{ number_format($gadai->total_tebus ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- SBG --}}
        @if($gadai->sbg && $gadai->sbg->count() > 0)
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Surat Bukti Gadai</h4>
            <div class="space-y-3">
                @foreach($gadai->sbg as $sbg)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-800 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $sbg->no_sbg }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ ucfirst($sbg->tipe) }} — {{ $sbg->tgl_transaksi->format('d M Y') }}</p>
                    </div>
                    <a href="{{ route('sbg.verify', $sbg->qr_token) }}" target="_blank" class="text-brand-500 hover:text-brand-600 transition-colors">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Aksi --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Aksi</h4>
            <div class="space-y-3">

                {{-- Cetak SBG --}}
                @if(in_array($gadai->status, ['aktif', 'jatuh_tempo', 'perpanjangan', 'lunas']))
                <a href="{{ route(auth()->user()->role . '.transaksi.gadai.sbg', $gadai->id) }}" target="_blank"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-success-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-success-600 transition-colors">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.056 48.056 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/></svg>
                    Cetak SBG
                </a>
                @endif

                {{-- Tambah Pinjaman --}}
                @if(in_array($gadai->status, ['aktif', 'perpanjangan']) && auth()->user()->role !== 'admin')
                @php $sisaBisa = $gadai->nilai_taksiran_max - ($gadai->nilai_pinjaman ?? 0); @endphp
                @if($sisaBisa > 0)
                <div x-data="{ openTambah: false }">
                    <button @click="openTambah = !openTambah"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-warning-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-warning-600 transition-colors">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Pinjaman
                    </button>
                    <div x-show="openTambah" x-transition class="mt-2 p-4 rounded-xl border border-warning-200 bg-warning-50 dark:border-warning-500/20 dark:bg-warning-500/10">
                        <div class="space-y-1 mb-3 text-xs text-warning-700 dark:text-warning-400">
                            <p>Saat ini: <strong>Rp {{ number_format($gadai->nilai_pinjaman ?? 0, 0, ',', '.') }}</strong></p>
                            <p>Maks: <strong>Rp {{ number_format($gadai->nilai_taksiran_max, 0, ',', '.') }}</strong></p>
                            <p>Sisa: <strong>Rp {{ number_format($sisaBisa, 0, ',', '.') }}</strong></p>
                        </div>
                        <div class="mb-2">
                            <label class="text-xs font-medium text-warning-700 dark:text-warning-400 mb-1 block">Jumlah Tambahan (Rp)</label>
                            <input type="number" id="inputNilaiTambahan" min="100000" max="{{ $sisaBisa }}"
                                placeholder="Min Rp 100.000"
                                oninput="siapkanModalTambah()"
                                class="w-full rounded-lg border border-warning-300 bg-white px-3 py-2 text-sm focus:border-warning-500 focus:outline-none dark:border-warning-500/30 dark:bg-gray-800 dark:text-gray-300">
                        </div>
                        <div class="mb-3">
                            <label class="text-xs font-medium text-warning-700 dark:text-warning-400 mb-1 block">Catatan (opsional)</label>
                            <textarea id="inputCatatanTambahan" rows="2" placeholder="Alasan tambah pinjaman..."
                                class="w-full rounded-lg border border-warning-300 bg-white px-3 py-2 text-sm focus:border-warning-500 focus:outline-none dark:border-warning-500/30 dark:bg-gray-800 dark:text-gray-300"></textarea>
                        </div>
                        <button type="button" onclick="bukaModalTambah()"
                            class="w-full rounded-lg bg-warning-500 px-4 py-2 text-sm font-semibold text-white hover:bg-warning-600 transition-colors">
                            Proses Tambah Pinjaman
                        </button>
                    </div>
                </div>
                @endif
                @endif

                {{-- Batalkan --}}
                @if($gadai->status === 'menunggu_approval' && auth()->user()->role !== 'admin')
                <form method="POST" action="{{ route(auth()->user()->role . '.transaksi.gadai.destroy', $gadai->id) }}">
                    @csrf @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin ingin menghapus pengajuan ini?')"
                        class="w-full rounded-lg bg-error-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-error-600 transition-colors">
                        Batalkan Pengajuan
                    </button>
                </form>
                @endif

                {{-- Perpanjangan & Pelunasan --}}
                @if(in_array($gadai->status, ['aktif', 'jatuh_tempo', 'perpanjangan']) && auth()->user()->role !== 'admin')
                <a href="{{ route(auth()->user()->role . '.transaksi.perpanjangan.create', ['gadai_id' => $gadai->id]) }}"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-600 transition-colors">
                    Perpanjangan
                </a>
                <a href="{{ route(auth()->user()->role . '.transaksi.pelunasan.create', ['gadai_id' => $gadai->id]) }}"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 transition-colors">
                    Pelunasan
                </a>
                @endif

                <a href="{{ route(auth()->user()->role . '.transaksi.gadai') }}"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Kembali ke Daftar
                </a>
            </div>
        </div>

    </div>
</div>

{{-- Lightbox --}}
<div id="lightbox" class="hidden fixed inset-0 z-[999] bg-black/80 flex items-center justify-center p-4"
    onclick="this.classList.add('hidden')">
    <img id="lightbox-img" src="" alt="" class="max-w-full max-h-full rounded-xl shadow-2xl">
</div>

@push('scripts')
<script>
// Lightbox
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.remove('hidden');
}

// Data gadai dari PHP untuk JS
const gadaiData = {
    nilaiPinjaman   : {{ (int)($gadai->nilai_pinjaman ?? 0) }},
    nilaiTambahanSekarang: {{ (int)($gadai->nilai_pinjaman_tambahan ?? 0) }},
    taksiranMax     : {{ (int)$gadai->nilai_taksiran_max }},
    gadaiId         : {{ $gadai->id }},
    role            : '{{ auth()->user()->role }}',
    tipeJasa        : '{{ $gadai->barang->kategori === "perhiasan" ? "perhiasan" : "umum" }}',
};

// Siapkan isi modal konfirmasi tambah pinjaman
function siapkanModalTambah() { /* trigger on input — tidak buka modal */ }

function bukaModalTambah() {
    const tambahan = parseInt(document.getElementById('inputNilaiTambahan')?.value || 0);
    if (!tambahan || tambahan < 100000) {
        tampilGagal('Jumlah tambahan minimal Rp 100.000.');
        return;
    }
    if (tambahan > gadaiData.taksiranMax - gadaiData.nilaiPinjaman) {
        tampilGagal('Jumlah tambahan melebihi sisa yang bisa dipinjam.');
        return;
    }
    const total = gadaiData.nilaiPinjaman + tambahan;
    document.getElementById('mtp-saat-ini').textContent  = 'Rp ' + fmt(gadaiData.nilaiPinjaman);
    document.getElementById('mtp-tambahan').textContent  = '+Rp ' + fmt(tambahan);
    document.getElementById('mtp-total').textContent     = 'Rp ' + fmt(total);
    document.getElementById('modalTambahPinjaman').classList.remove('hidden');
}

function tutupModalTambah() {
    document.getElementById('modalTambahPinjaman').classList.add('hidden');
}

async function submitTambahPinjaman() {
    tutupModalTambah();
    const tambahan = parseInt(document.getElementById('inputNilaiTambahan')?.value || 0);
    const catatan  = document.getElementById('inputCatatanTambahan')?.value || '';

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('nilai_tambahan', tambahan);
    formData.append('catatan', catatan);

    try {
        const res  = await fetch(`/${gadaiData.role}/transaksi/gadai/${gadaiData.gadaiId}/tambah-pinjaman`, {
            method: 'POST',
            body  : formData,
        });

        if (res.redirected || res.ok) {
            // Cek apakah response adalah redirect (sukses dari controller)
            // Ambil data baru via halaman yang sama
            const nilaiTotal = gadaiData.nilaiPinjaman + tambahan;

            // Update tampilan ringkasan sidebar
            document.getElementById('sb-nilai-pinjaman').textContent =
                'Rp ' + fmt(nilaiTotal);

            const totalTambahan = gadaiData.nilaiTambahanSekarang + tambahan;
            const rowTambahan   = document.getElementById('sb-row-tambahan');
            document.getElementById('sb-pinjaman-tambahan').textContent =
                'Rp ' + fmt(totalTambahan);
            rowTambahan.classList.remove('hidden');
            rowTambahan.style.display = 'flex';

            // Update detail card
            document.getElementById('detail-nilai-pinjaman').textContent =
                'Rp ' + fmt(nilaiTotal);

            // Reload jasa dari API
            const tipe = gadaiData.tipeJasa;
            fetch('/api/preview-jasa-rate?nilai=' + nilaiTotal + '&tipe=' + tipe)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('sb-label-jasa').textContent =
                        'Jasa (' + data.jasa_30_hari + '%)';
                    document.getElementById('sb-jasa-nominal').textContent =
                        'Rp ' + fmt(data.jasa_nominal_30);
                    document.getElementById('sb-total-tebus').textContent =
                        'Rp ' + fmt(data.total_tebus_30);
                    document.getElementById('detail-label-jasa').textContent =
                        'Jasa (' + data.jasa_30_hari + '%)';
                    document.getElementById('detail-jasa-nominal').textContent =
                        'Rp ' + fmt(data.jasa_nominal_30);
                    document.getElementById('detail-total-tebus').textContent =
                        'Rp ' + fmt(data.total_tebus_30);
                });

            tampilSukses('Pinjaman berhasil ditambah. Total pinjaman: Rp ' + fmt(nilaiTotal));
        } else {
            const json = await res.json().catch(() => null);
            tampilGagal(json?.message || 'Gagal memproses tambah pinjaman.');
        }
    } catch (e) {
        tampilGagal('Terjadi kesalahan koneksi.');
    }
}

function tampilSukses(msg) {
    document.getElementById('modalSuksesMsg').textContent = msg;
    document.getElementById('modalSukses').classList.remove('hidden');
}

function tampilGagal(msg) {
    document.getElementById('modalGagalMsg').textContent = msg;
    document.getElementById('modalGagal').classList.remove('hidden');
}

function fmt(n) {
    return parseInt(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
</script>
@endpush

@endsection
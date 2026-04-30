@extends('layouts.app')
@section('content')

<x-common.page-breadcrumb pageTitle="Detail Perpanjangan" />

{{-- Modal Berhasil (Tunai) --}}
@if(session('show_modal'))
<div id="successModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
    <div class="w-full max-w-md rounded-2xl bg-white dark:bg-gray-900 shadow-2xl overflow-hidden">
        <div class="p-6 text-center">
            <div class="flex items-center justify-center w-16 h-16 rounded-full bg-success-50 dark:bg-success-500/10 mx-auto mb-4">
                <svg class="text-success-500" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Perpanjangan Berhasil!</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">{{ session('success') }}</p>
            <button onclick="document.getElementById('successModal').remove()"
                class="w-full rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white hover:bg-brand-600 transition-colors">
                OK, Mengerti
            </button>
        </div>
    </div>
</div>
@endif

@if(session('info'))
<div class="mb-6 p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-700 text-sm font-medium dark:bg-blue-500/10 dark:border-blue-500/20 dark:text-blue-400">
    {{ session('info') }}
</div>
@endif

@if(session('success') && !session('show_modal'))
<div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400">
    {{ session('success') }}
</div>
@endif

{{-- Midtrans: dari session (baru redirect) --}}
@if(session('snap_token'))
<div class="mb-6 p-5 rounded-2xl bg-blue-50 border border-blue-200 dark:bg-blue-500/10 dark:border-blue-500/20">
    <div class="flex items-center gap-3 mb-3">
        <svg class="text-blue-600 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
        </svg>
        <div>
            <p class="text-sm font-semibold text-blue-700 dark:text-blue-400">Menunggu Pembayaran via Midtrans</p>
            <p class="text-xs text-blue-600 dark:text-blue-300 mt-0.5">Halaman pembayaran akan terbuka otomatis. Jika tidak muncul klik tombol di bawah.</p>
        </div>
    </div>
    <button id="pay-button"
        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
        Buka Halaman Pembayaran
    </button>
</div>
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
    window.addEventListener('load', function() { triggerSnap(); });
    function triggerSnap() {
        snap.pay('{{ session("snap_token") }}', {
            onSuccess: function(result) { window.location.reload(); },
            onPending: function(result) { window.location.reload(); },
            onError: function(result) { alert('Pembayaran gagal!'); },
            onClose: function() { console.log('closed'); }
        });
    }
    document.getElementById('pay-button').onclick = function() { triggerSnap(); };
</script>

{{-- Midtrans: dari database (status masih menunggu) --}}
@elseif($perpanjangan->status_bayar === 'menunggu' && $perpanjangan->metode_bayar === 'midtrans' && $perpanjangan->midtrans_token)
<div class="mb-6 p-5 rounded-2xl bg-blue-50 border border-blue-200 dark:bg-blue-500/10 dark:border-blue-500/20">
    <div class="flex items-center gap-3 mb-3">
        <svg class="text-blue-600 flex-shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
        </svg>
        <div>
            <p class="text-sm font-semibold text-blue-700 dark:text-blue-400">Pembayaran Belum Selesai</p>
            <p class="text-xs text-blue-600 dark:text-blue-300 mt-0.5">Klik lanjutkan atau buat ulang jika token expired.</p>
        </div>
    </div>
    <div class="flex flex-wrap gap-3">
        <button id="pay-button"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
            Lanjutkan Pembayaran
        </button>
        <form method="POST"
            action="{{ route(auth()->user()->role . '.transaksi.perpanjangan.retry', $perpanjangan->id) }}">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg border border-error-300 bg-error-50 px-5 py-2.5 text-sm font-medium text-error-600 hover:bg-error-100 dark:border-error-500/30 dark:bg-error-500/10 dark:text-error-500 transition-colors">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                </svg>
                Token Expired? Buat Ulang
            </button>
        </form>
    </div>
</div>
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
    document.getElementById('pay-button').onclick = function() {
        snap.pay('{{ $perpanjangan->midtrans_token }}', {
            onSuccess: function(result) { window.location.reload(); },
            onPending: function(result) { window.location.reload(); },
            onError: function(result) { alert('Token expired. Klik Buat Ulang Transaksi.'); },
            onClose: function() { console.log('closed'); }
        });
    };
</script>
@endif

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-6">

        {{-- Card Status --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">
                        {{ $perpanjangan->no_sbg ?? 'Perpanjangan #' . $perpanjangan->id }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                        Diproses oleh {{ $perpanjangan->officer->nama ?? '-' }} pada {{ $perpanjangan->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>
                @php
                    $statusConfig = [
                        'menunggu' => 'bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400',
                        'berhasil' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                        'gagal'    => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                    ];
                @endphp
                <span class="rounded-full px-3 py-1 text-sm font-semibold {{ $statusConfig[$perpanjangan->status_bayar] ?? 'bg-gray-100 text-gray-600' }}">
                    {{ ucfirst($perpanjangan->status_bayar) }}
                </span>
            </div>
        </div>

        {{-- Card Info Gadai --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Informasi Gadai</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">No SBG Gadai</p>
                        <p class="text-sm font-bold text-brand-500 mt-1">{{ $perpanjangan->gadai->no_sbg ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Nasabah</p>
                        <p class="text-sm font-semibold text-gray-800 dark:text-white mt-1">{{ $perpanjangan->nasabah->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Barang</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $perpanjangan->gadai->barang->nama_barang ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Cabang</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $perpanjangan->gadai->branch->nama ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Detail --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Detail Perpanjangan</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Nilai Pinjaman</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">Rp {{ number_format($perpanjangan->nilai_pinjaman, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Biaya Jasa ({{ $perpanjangan->jasa_persen }}%)</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">Rp {{ number_format($perpanjangan->jasa_nominal, 0, ',', '.') }}</p>
                    </div>
                    @if($perpanjangan->denda_nominal > 0)
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Denda ({{ $perpanjangan->denda_persen }}%)</p>
                        <p class="text-sm font-medium text-error-600 mt-1">Rp {{ number_format($perpanjangan->denda_nominal, 0, ',', '.') }} <span class="text-xs">({{ $perpanjangan->hari_terlambat }} hari)</span></p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Bayar</p>
                        <p class="text-sm font-bold text-brand-500 mt-1">Rp {{ number_format($perpanjangan->total_bayar, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">JT Lama</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $perpanjangan->tgl_jt_lama->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">JT Baru</p>
                        <p class="text-sm font-semibold text-brand-500 mt-1">{{ $perpanjangan->tgl_jt_baru->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Tgl Perpanjangan</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white mt-1">{{ $perpanjangan->tgl_perpanjangan->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Metode Bayar</p>
                        <span class="inline-block mt-1 rounded-full px-2 py-0.5 text-theme-xs font-medium {{ $perpanjangan->metode_bayar === 'tunai' ? 'bg-gray-100 text-gray-600' : 'bg-blue-50 text-blue-600' }}">
                            {{ ucfirst($perpanjangan->metode_bayar) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Ringkasan</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Jasa</span>
                    <span class="text-sm font-medium text-gray-800 dark:text-white">Rp {{ number_format($perpanjangan->jasa_nominal, 0, ',', '.') }}</span>
                </div>
                @if($perpanjangan->denda_nominal > 0)
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-error-600">Denda</span>
                    <span class="text-sm font-medium text-error-600">Rp {{ number_format($perpanjangan->denda_nominal, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm font-bold text-gray-800 dark:text-white">Total Bayar</span>
                    <span class="text-sm font-bold text-brand-500">Rp {{ number_format($perpanjangan->total_bayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">JT Baru</span>
                    <span class="text-sm font-semibold text-brand-500">{{ $perpanjangan->tgl_jt_baru->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Aksi</h4>
            <div class="space-y-3">
                @if($perpanjangan->status_bayar === 'berhasil')
                <a href="{{ route(auth()->user()->role . '.transaksi.gadai.sbg', $perpanjangan->gadai_id) }}?tipe=perpanjangan"
                    target="_blank"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-success-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-success-600 transition-colors">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/>
                    </svg>
                    Cetak SBG Perpanjangan
                </a>
                @endif
                <a href="{{ route(auth()->user()->role . '.transaksi.gadai.show', $perpanjangan->gadai_id) }}"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Lihat Detail Gadai
                </a>
                <a href="{{ route(auth()->user()->role . '.transaksi.perpanjangan') }}"
                    class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Kembali ke Daftar
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
@extends('layouts.app')
@section('content')

    <x-common.page-breadcrumb pageTitle="Detail Pengajuan Gadai" :breadcrumbs="[
        ['title' => 'Approval Pengajuan', 'url' => route('superadmin.transactions.approval')],
        ['title' => 'Detail Pengajuan', 'url' => null],
    ]" />

    {{-- Alert --}}
    @if (session('success'))
        <div
            class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div
            class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium dark:bg-red-500/10 dark:border-red-500/20 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Transaction Details --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Detail Pengajuan Gadai</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Informasi lengkap pengajuan gadai</p>
                </div>

                <div class="p-6 space-y-6">

                    {{-- Basic Info --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">No SBG</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->no_sbg }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                Pending Approval
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal
                                Transaksi</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $transaction->transaction_date->format('d F Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cabang</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->branch->nama }}
                            </p>
                        </div>
                    </div>

                    {{-- Customer Info --}}
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Informasi Nasabah</h4>
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400">Nama</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $transaction->customer->nama }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400">No CIF</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $transaction->customer->no_cif }}
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400">No HP</label>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $transaction->customer->no_hp ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400">Alamat</label>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $transaction->customer->alamat ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Item Info --}}
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Informasi Barang</h4>
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2">
                                    <label class="block text-xs text-gray-500 dark:text-gray-400">Nama Barang</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $transaction->item_name }}
                                    </p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs text-gray-500 dark:text-gray-400">Deskripsi</label>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $transaction->item_description ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400">Kategori</label>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $transaction->item_category ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400">Kondisi</label>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $transaction->item_condition ?? '-' }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400">Kelengkapan</label>
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $transaction->item_completeness ?? '-' }}</p>
                                </div>
                            </div>

                            {{-- Item Photos --}}
                            @if (!empty($transaction->item_photos))
                                <div class="mt-4">
                                    <label class="block text-xs text-gray-500 dark:text-gray-400 mb-2">Foto Barang</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach ($transaction->item_photos as $photo)
                                            <img src="{{ asset('storage/' . $photo) }}"
                                                class="h-20 w-full object-cover rounded border" />
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Appraisal Info --}}
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Informasi Taksiran</h4>
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400">Range Min</label>
                                    <p class="text-sm text-gray-900 dark:text-white">Rp
                                        {{ number_format($transaction->officer_appraisal_min, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400">Range Max</label>
                                    <p class="text-sm text-gray-900 dark:text-white">Rp
                                        {{ number_format($transaction->officer_appraisal_max, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400">Nilai Pinjaman</label>
                                    <p class="text-sm text-gray-900 dark:text-white">Rp
                                        {{ number_format($transaction->loan_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Officer Info --}}
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Informasi Petugas</h4>
                        <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400">Nama Petugas</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $transaction->officer->nama }}</p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 dark:text-gray-400">Email</label>
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $transaction->officer->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- Sidebar Actions --}}
        <div class="space-y-6">

            {{-- Approval Actions --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                    <h3 class="text-base font-semibold text-gray-800 dark:text-white">Aksi Approval</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Setujui atau tolak pengajuan ini</p>
                </div>

                <div class="p-6 space-y-4">

                    {{-- Approve Form --}}
                    <form method="POST" action="{{ route('superadmin.transactions.approve', $transaction->id) }}">
                        @csrf
                        @method('POST')
                        <div class="space-y-3 mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Nominal Pinjaman Disetujui <span class="text-red-500">*</span>
                            </label>
                            <input type="number" step="0.01" name="loan_amount"
                                value="{{ old('loan_amount', $transaction->loan_amount ?? $transaction->officer_appraisal_min) }}"
                                placeholder="0.00"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                required />
                            @error('loan_amount')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-green-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-600 transition-colors">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                                <path
                                    d="M16.7071 5.29289C17.0976 5.68342 17.0976 6.31658 16.7071 6.70711L8.70711 14.7071C8.31658 15.0976 7.68342 15.0976 7.29289 14.7071L3.29289 10.7071C2.90237 10.3166 2.90237 9.68342 3.29289 9.29289C3.68342 8.90237 4.31658 8.90237 4.70711 9.29289L8 12.5858L15.2929 5.29289C15.6834 4.90237 16.3166 4.90237 16.7071 5.29289Z"
                                    fill="currentColor" />
                            </svg>
                            Setujui Pengajuan
                        </button>
                    </form>

                    {{-- Reject Form --}}
                    <form method="POST" action="{{ route('superadmin.transactions.reject', $transaction->id) }}">
                        @csrf
                        @method('POST')
                        <div class="space-y-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                                Alasan Penolakan (Opsional)
                            </label>
                            <textarea name="rejection_reason" rows="3" placeholder="Masukkan alasan penolakan..."
                                class="w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"></textarea>
                        </div>
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-red-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-600 transition-colors mt-3">
                            <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                                <path
                                    d="M6.28033 5.21967C5.98744 4.92678 5.51256 4.92678 5.21967 5.21967C4.92678 5.51256 4.92678 5.98744 5.21967 6.28033L8.93934 10L5.21967 13.7197C4.92678 14.0126 4.92678 14.4874 5.21967 14.7803C5.51256 15.0732 5.98744 15.0732 6.28033 14.7803L10 11.0607L13.7197 14.7803C14.0126 15.0732 14.4874 15.0732 14.7803 14.7803C15.0732 14.0126 15.0732 13.5377 14.7803 13.2448L11.0607 10L14.7803 6.28033C15.0732 5.98744 15.0732 5.51256 14.7803 5.21967C14.4874 4.92678 13.5377 4.92678 13.2448 5.21967L10 8.93934L6.28033 5.21967Z"
                                    fill="currentColor" />
                            </svg>
                            Tolak Pengajuan
                        </button>
                    </form>

                </div>
            </div>

            {{-- Back Button --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="p-6">
                    <a href="{{ route('superadmin.transactions.approval') }}"
                        class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                        <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                            <path d="M15.8333 10H4.16667M4.16667 10L10 15.8333M4.16667 10L10 4.16667" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

        </div>

    </div>

@endsection

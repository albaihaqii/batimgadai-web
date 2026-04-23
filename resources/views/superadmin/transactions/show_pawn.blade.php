@extends('layouts.app')
@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Detail Transaksi Gadai</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">No SBG: {{ $transaction->no_sbg }}</p>
                </div>
                <div class="flex items-center gap-2">
                    @if ($transaction->status === 'approved')
                        <a href="{{ route('superadmin.transactions.pawn.sbg', $transaction->id) }}"
                            class="inline-flex items-center px-4 py-2 border border-blue-600 rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                            Unduh SBG
                        </a>
                    @endif
                    <a href="{{ route('superadmin.transactions.pawn') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            {{-- Status Update Form --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Kontrol Status Transaksi</h3>
                <form method="POST" action="{{ route('superadmin.transactions.update_pawn_status', $transaction->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select name="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required>
                                <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="approved" {{ $transaction->status == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected" {{ $transaction->status == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                                <option value="completed" {{ $transaction->status == 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Taksiran Final
                                (Rp)</label>
                            <input type="number" name="final_appraisal" value="{{ $transaction->final_appraisal }}"
                                step="0.01"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>

            {{-- Transaction Details --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Transaksi</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">No SBG</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->no_sbg ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Transaksi</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $transaction->transaction_date->format('d/m/Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="mt-1">
                            @php
                                $statusLabel = 'Unknown';
                                $statusClass = 'bg-gray-100 text-gray-800';
                                if ($transaction->status == 'pending') {
                                    $statusLabel = 'Menunggu Approval';
                                    $statusClass = 'bg-yellow-100 text-yellow-800';
                                } elseif ($transaction->status == 'approved') {
                                    $statusLabel = 'Aktif';
                                    $statusClass = 'bg-green-100 text-green-800';
                                } elseif ($transaction->status == 'rejected') {
                                    $statusLabel = 'Tolak';
                                    $statusClass = 'bg-red-100 text-red-800';
                                } elseif ($transaction->status == 'completed') {
                                    $statusLabel = 'Lunas';
                                    $statusClass = 'bg-blue-100 text-blue-800';
                                }
                            @endphp
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Approval</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            {{ $transaction->approval_date ? $transaction->approval_date->format('d/m/Y') : '-' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Customer Information --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Nasabah</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">CIF</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->customer->no_cif }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->customer->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">No HP</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->customer->no_hp }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Alamat</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->customer->alamat }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Item Information --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Barang</h3>
                @if ($transaction->item_name)
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Barang</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $transaction->item_name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Deskripsi</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $transaction->item_description ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                    @if ($transaction->item_photos)
                        <div class="mt-4">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Foto Barang</dt>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($transaction->item_photos as $photo)
                                    <img src="{{ asset('storage/' . $photo) }}" alt="Foto Barang"
                                        class="w-full h-32 object-cover rounded-lg">
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <p class="text-gray-500 dark:text-gray-400">Data barang tidak tersedia</p>
                @endif
            </div>

            {{-- Appraisal Information --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Taksiran</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Range Taksiran Petugas</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">Rp
                            {{ number_format($transaction->officer_appraisal_min) }} - Rp
                            {{ number_format($transaction->officer_appraisal_max) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nominal Pinjaman</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            @if ($transaction->loan_amount)
                                Rp {{ number_format($transaction->loan_amount) }}
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Taksiran Final</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                            @if ($transaction->final_appraisal)
                                Rp {{ number_format($transaction->final_appraisal) }}
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Branch & Officer Information --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Cabang & Petugas</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cabang</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->branch->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Petugas Input</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->officer->nama }}</dd>
                    </div>
                    @if ($transaction->admin)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Pimpinan Approval</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $transaction->admin->nama }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>

@endsection

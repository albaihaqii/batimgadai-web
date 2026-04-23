@extends('layouts.app')
@section('content')
    <x-common.page-breadcrumb pageTitle="Approval Pengajuan Gadai" />

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

    <div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- Header --}}
        <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Approval Pengajuan Gadai</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Kelola pengajuan gadai yang menunggu approval</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="max-w-full overflow-x-auto">
            <table class="w-full">
                <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-theme-xs font-semibold text-gray-600 dark:text-gray-400">No SBG
                        </th>
                        <th class="px-6 py-3 text-left text-theme-xs font-semibold text-gray-600 dark:text-gray-400">Nasabah
                        </th>
                        <th class="px-6 py-3 text-left text-theme-xs font-semibold text-gray-600 dark:text-gray-400">Barang
                        </th>
                        <th class="px-6 py-3 text-left text-theme-xs font-semibold text-gray-600 dark:text-gray-400">Nilai
                            Pinjaman</th>
                        <th class="px-6 py-3 text-left text-theme-xs font-semibold text-gray-600 dark:text-gray-400">Cabang
                        </th>
                        <th class="px-6 py-3 text-left text-theme-xs font-semibold text-gray-600 dark:text-gray-400">Petugas
                        </th>
                        <th class="px-6 py-3 text-left text-theme-xs font-semibold text-gray-600 dark:text-gray-400">Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-theme-xs font-semibold text-gray-600 dark:text-gray-400">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($pendingTransactions as $transaction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">{{ $transaction->no_sbg }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">
                                {{ $transaction->customer->nama }}<br>
                                <span
                                    class="text-xs text-gray-500 dark:text-gray-400">{{ $transaction->customer->no_cif }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">
                                {{ $transaction->item_name }}<br>
                                <span
                                    class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($transaction->item_description ?? '', 30) }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">
                                @if ($transaction->loan_amount)
                                    Rp {{ number_format($transaction->loan_amount, 0, ',', '.') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">
                                {{ $transaction->branch->nama }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">{{ $transaction->officer->nama }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-white/90">
                                {{ $transaction->transaction_date->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('superadmin.transactions.show_approval', $transaction->id) }}"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-blue-500 px-3 py-1.5 text-theme-xs font-medium text-white hover:bg-blue-600 transition-colors">
                                        <svg width="14" height="14" viewBox="0 0 20 20" fill="none">
                                            <path d="M10 4V16M4 10H16" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" />
                                        </svg>
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-sm text-gray-500 dark:text-gray-400">
                                Tidak ada pengajuan gadai yang menunggu approval
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer: Info + Pagination --}}
        @if ($pendingTransactions->hasPages())
            <div
                class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan {{ $pendingTransactions->firstItem() ?? 0 }}–{{ $pendingTransactions->lastItem() ?? 0 }}
                    dari {{ $pendingTransactions->total() }} data
                </p>
                <div class="flex items-center gap-2">
                    {{ $pendingTransactions->links() }}
                </div>
            </div>
        @endif
    </div>
@endsection

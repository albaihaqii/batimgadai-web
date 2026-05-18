@extends('layouts.app')
@section('content')

    {{-- 4 Stats Card --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6 mb-6">

        {{-- Card 1: Total Nasabah --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.80443 5.60156C7.59109 5.60156 6.60749 6.58517 6.60749 7.79851C6.60749 9.01185 7.59109 9.99545 8.80443 9.99545C10.0178 9.99545 11.0014 9.01185 11.0014 7.79851C11.0014 6.58517 10.0178 5.60156 8.80443 5.60156ZM5.10749 7.79851C5.10749 5.75674 6.76267 4.10156 8.80443 4.10156C10.8462 4.10156 12.5014 5.75674 12.5014 7.79851C12.5014 9.84027 10.8462 11.4955 8.80443 11.4955C6.76267 11.4955 5.10749 9.84027 5.10749 7.79851ZM4.86252 15.3208C4.08769 16.0881 3.70377 17.0608 3.51705 17.8611C3.48384 18.0034 3.5211 18.1175 3.60712 18.2112C3.70161 18.3141 3.86659 18.3987 4.07591 18.3987H13.4249C13.6343 18.3987 13.7992 18.3141 13.8937 18.2112C13.9797 18.1175 14.017 18.0034 13.9838 17.8611C13.7971 17.0608 13.4132 16.0881 12.6383 15.3208C11.8821 14.572 10.6899 13.955 8.75042 13.955C6.81096 13.955 5.61877 14.572 4.86252 15.3208ZM3.8071 14.2549C4.87163 13.2009 6.45602 12.455 8.75042 12.455C11.0448 12.455 12.6292 13.2009 13.6937 14.2549C14.7397 15.2906 15.2207 16.5607 15.4446 17.5202C15.7658 18.8971 14.6071 19.8987 13.4249 19.8987H4.07591C2.89369 19.8987 1.73504 18.8971 2.05628 17.5202C2.28015 16.5607 2.76117 15.2906 3.8071 14.2549ZM15.3042 11.4955C14.4702 11.4955 13.7006 11.2193 13.0821 10.7533C13.3742 10.3314 13.6054 9.86419 13.7632 9.36432C14.1597 9.75463 14.7039 9.99545 15.3042 9.99545C16.5176 9.99545 17.5012 9.01185 17.5012 7.79851C17.5012 6.58517 16.5176 5.60156 15.3042 5.60156C14.7039 5.60156 14.1597 5.84239 13.7632 6.23271C13.6054 5.73284 13.3741 5.26561 13.082 4.84371C13.7006 4.37777 14.4702 4.10156 15.3042 4.10156C17.346 4.10156 19.0012 5.75674 19.0012 7.79851C19.0012 9.84027 17.346 11.4955 15.3042 11.4955ZM19.9248 19.8987H16.3901C16.7014 19.4736 16.9159 18.969 16.9827 18.3987H19.9248C20.1341 18.3987 20.2991 18.3141 20.3936 18.2112C20.4796 18.1175 20.5169 18.0034 20.4837 17.861C20.2969 17.0607 19.913 16.088 19.1382 15.3208C18.4047 14.5945 17.261 13.9921 15.4231 13.9566C15.2232 13.6945 14.9995 13.437 14.7491 13.1891C14.5144 12.9566 14.262 12.7384 13.9916 12.5362C14.3853 12.4831 14.8044 12.4549 15.2503 12.4549C17.5447 12.4549 19.1291 13.2008 20.1936 14.2549C21.2395 15.2906 21.7206 16.5607 21.9444 17.5202C22.2657 18.8971 21.107 19.8987 19.9248 19.8987Z"
                        fill="" />
                </svg>
            </div>
            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Nasabah</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">
                        {{ number_format($totalNasabah, 0, ',', '.') }}</h4>
                </div>
                <span
                    class="flex items-center gap-1 rounded-full bg-success-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                    <svg class="fill-current" width="12" height="12" viewBox="0 0 12 12">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.56462 1.62393C5.70193 1.47072 5.90135 1.37432 6.12329 1.37432C6.31631 1.37415 6.50845 1.44731 6.65505 1.59381L9.65514 4.5918C9.94814 4.88459 9.94831 5.35947 9.65552 5.65246C9.36273 5.94546 8.88785 5.94562 8.59486 5.65283L6.87329 3.93247L6.87329 10.125C6.87329 10.5392 6.53751 10.875 6.12329 10.875C5.70908 10.875 5.37329 10.5392 5.37329 10.125L5.37329 3.93578L3.65516 5.65282C3.36218 5.94562 2.8873 5.94547 2.5945 5.65248C2.3017 5.35949 2.30185 4.88462 2.59484 4.59182L5.56462 1.62393Z"
                            fill="" />
                    </svg>
                    {{ ($nasabahGrowth >= 0 ? '+' : '') . number_format($nasabahGrowth, 1, ',', '.') }}%
                </span>
            </div>
        </div>

        {{-- Card 2: Transaksi Aktif --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M11.665 3.75621C11.8762 3.65064 12.1247 3.65064 12.3358 3.75621L18.7807 6.97856L12.3358 10.2009C12.1247 10.3065 11.8762 10.3065 11.665 10.2009L5.22014 6.97856L11.665 3.75621ZM4.29297 8.19203V16.0946C4.29297 16.3787 4.45347 16.6384 4.70757 16.7654L11.25 20.0366V11.6513C11.1631 11.6205 11.0777 11.5843 10.9942 11.5426L4.29297 8.19203ZM12.75 20.037L19.2933 16.7654C19.5474 16.6384 19.7079 16.3787 19.7079 16.0946V8.19202L13.0066 11.5426C12.9229 11.5844 12.8372 11.6208 12.75 11.6516V20.037ZM13.0066 2.41456C12.3732 2.09786 11.6277 2.09786 10.9942 2.41456L4.03676 5.89319C3.27449 6.27432 2.79297 7.05342 2.79297 7.90566V16.0946C2.79297 16.9469 3.27448 17.726 4.03676 18.1071L10.9942 21.5857C11.6277 21.9024 12.3732 21.9024 13.0066 21.5857L19.9641 18.1071C20.7264 17.726 21.2079 16.9469 21.2079 16.0946V7.90566C21.2079 7.05342 20.7264 6.27432 19.9641 5.89319L13.0066 2.41456Z"
                        fill="" />
                </svg>
            </div>
            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Transaksi Aktif</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">
                        {{ number_format($activeTransactions, 0, ',', '.') }}</h4>
                </div>
                <span
                    class="flex items-center gap-1 rounded-full bg-success-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                    <svg class="fill-current" width="12" height="12" viewBox="0 0 12 12">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.56462 1.62393C5.70193 1.47072 5.90135 1.37432 6.12329 1.37432C6.31631 1.37415 6.50845 1.44731 6.65505 1.59381L9.65514 4.5918C9.94814 4.88459 9.94831 5.35947 9.65552 5.65246C9.36273 5.94546 8.88785 5.94562 8.59486 5.65283L6.87329 3.93247L6.87329 10.125C6.87329 10.5392 6.53751 10.875 6.12329 10.875C5.70908 10.875 5.37329 10.5392 5.37329 10.125L5.37329 3.93578L3.65516 5.65282C3.36218 5.94562 2.8873 5.94547 2.5945 5.65248C2.3017 5.35949 2.30185 4.88462 2.59484 4.59182L5.56462 1.62393Z"
                            fill="" />
                    </svg>
                    {{ ($activeTransactionsGrowth >= 0 ? '+' : '') . number_format($activeTransactionsGrowth, 1, ',', '.') }}%
                </span>
            </div>
        </div>

        {{-- Card 3: Total Pinjaman Bulan Ini --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.5 8.187V17.25C3.5 17.6642 3.83579 18 4.25 18H19.75C20.1642 18 20.5 17.6642 20.5 17.25V8.18747L13.2873 13.2171C12.5141 13.7563 11.4866 13.7563 10.7134 13.2171L3.5 8.187ZM20.5 6.2286C20.5 6.23039 20.5 6.23218 20.5 6.23398V6.24336C20.4976 6.31753 20.4604 6.38643 20.3992 6.42905L12.4293 11.9867C12.1716 12.1664 11.8291 12.1664 11.5713 11.9867L3.60116 6.42885C3.538 6.38481 3.50035 6.31268 3.50032 6.23568C3.50028 6.10553 3.60577 6 3.73592 6H20.2644C20.3922 6 20.4963 6.10171 20.5 6.2286ZM22 6.25648V17.25C22 18.4926 20.9926 19.5 19.75 19.5H4.25C3.00736 19.5 2 18.4926 2 17.25V6.23398C2 6.22371 2.00021 6.2135 2.00061 6.20333C2.01781 5.25971 2.78812 4.5 3.73592 4.5H20.2644C21.2229 4.5 22 5.27697 22.0001 6.23549C22.0001 6.24249 22.0001 6.24949 22 6.25648Z"
                        fill="" />
                </svg>
            </div>
            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Pinjaman Bulan Ini</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">Rp
                        {{ number_format($totalPinjamanBulanIni, 0, ',', '.') }}</h4>
                </div>
                <span
                    class="flex items-center gap-1 rounded-full bg-success-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                    <svg class="fill-current" width="12" height="12" viewBox="0 0 12 12">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M5.56462 1.62393C5.70193 1.47072 5.90135 1.37432 6.12329 1.37432C6.31631 1.37415 6.50845 1.44731 6.65505 1.59381L9.65514 4.5918C9.94814 4.88459 9.94831 5.35947 9.65552 5.65246C9.36273 5.94546 8.88785 5.94562 8.59486 5.65283L6.87329 3.93247L6.87329 10.125C6.87329 10.5392 6.53751 10.875 6.12329 10.875C5.70908 10.875 5.37329 10.5392 5.37329 10.125L5.37329 3.93578L3.65516 5.65282C3.36218 5.94562 2.8873 5.94547 2.5945 5.65248C2.3017 5.35949 2.30185 4.88462 2.59484 4.59182L5.56462 1.62393Z"
                            fill="" />
                    </svg>
                    {{ ($totalPinjamanGrowth >= 0 ? '+' : '') . number_format($totalPinjamanGrowth, 1, ',', '.') }}%
                </span>
            </div>
        </div>

        {{-- Card 4: Menunggu Approval --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800">
                <svg class="fill-gray-800 dark:fill-white/90" width="24" height="24" viewBox="0 0 24 24"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H18.5001C19.7427 20.75 20.7501 19.7426 20.7501 18.5V5.5C20.7501 4.25736 19.7427 3.25 18.5001 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H18.5001C18.9143 4.75 19.2501 5.08579 19.2501 5.5V18.5C19.2501 18.9142 18.9143 19.25 18.5001 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V5.5ZM6.25005 9.7143C6.25005 9.30008 6.58583 8.9643 7.00005 8.9643L17 8.96429C17.4143 8.96429 17.75 9.30008 17.75 9.71429C17.75 10.1285 17.4143 10.4643 17 10.4643L7.00005 10.4643C6.58583 10.4643 6.25005 10.1285 6.25005 9.7143ZM6.25005 14.2857C6.25005 13.8715 6.58583 13.5357 7.00005 13.5357H12C12.4143 13.5357 12.75 13.8715 12.75 14.2857C12.75 14.6999 12.4143 15.0357 12 15.0357H7.00005C6.58583 15.0357 6.25005 14.6999 6.25005 14.2857Z"
                        fill="" />
                </svg>
            </div>
            <div class="flex items-end justify-between mt-5">
                <div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Menunggu Approval</span>
                    <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">
                        {{ number_format($pendingApprovalCount, 0, ',', '.') }}</h4>
                </div>
                <span
                    class="flex items-center gap-1 rounded-full bg-warning-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                    @if ($pendingApprovalCount > 0)
                        Pending ·
                        {{ ($pendingApprovalGrowth >= 0 ? '+' : '') . number_format($pendingApprovalGrowth, 1, ',', '.') }}%
                    @elseif ($pendingApprovalGrowth !== 0)
                        {{ ($pendingApprovalGrowth >= 0 ? '+' : '') . number_format($pendingApprovalGrowth, 1, ',', '.') }}%
                    @endif
                </span>
            </div>
        </div>
    </div>

    {{-- Chart Row --}}
    <div class="grid grid-cols-12 gap-4 md:gap-6 mb-6">

        {{-- Bar Chart --}}
        <div
            class="col-span-12 xl:col-span-8 overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pt-5 sm:px-6 sm:pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Transaksi per Bulan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Rekap gadai, perpanjangan & pelunasan tahun
                        ini</p>
                </div>
            </div>
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <div id="chartSix" class="min-w-[500px] h-[315px]"></div>
            </div>
        </div>

        {{-- Donut Chart --}}
        <div
            class="col-span-12 xl:col-span-4 overflow-hidden rounded-2xl border border-gray-200 bg-white px-5 pt-5 sm:px-6 sm:pt-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="mb-2">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Transaksi per Cabang</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Distribusi transaksi semua cabang</p>
            </div>
            <div id="chartPie" class="h-[280px]"></div>
            @php
                $branchColors = ['#1F5C3A', '#B6D96C', '#174a2e'];
                $branchTotal = array_sum($branchSeries ?? []);
            @endphp
            <div class="flex flex-wrap justify-center gap-6 pb-4">
                @if ($branchTotal > 0)
                    @foreach ($branchLabels as $index => $branch)
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full"
                                style="background-color: {{ $branchColors[$index % count($branchColors)] }}"></span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $branch }}</span>
                        </div>
                    @endforeach
                @else
                    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="w-2.5 h-2.5 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                        Tidak ada transaksi cabang.
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tabel Pengajuan Terbaru --}}
    <div
        class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
        <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Pengajuan Gadai Terbaru</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Menunggu persetujuan admin cabang</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="#"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                    Lihat Semua
                </a>
            </div>
        </div>

        <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="min-w-full">
                <thead>
                    <tr class="border-t border-gray-100 dark:border-gray-800">
                        <th class="py-3 text-left pr-4">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No SBG</p>
                        </th>
                        <th class="py-3 text-left pr-4">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nasabah</p>
                        </th>
                        <th class="py-3 text-left pr-4">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Barang</p>
                        </th>
                        <th class="py-3 text-left pr-4">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Cabang</p>
                        </th>
                        <th class="py-3 text-left pr-4">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Taksiran Awal</p>
                        </th>
                        <th class="py-3 text-left">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengajuanTerbaru as $item)
                        <tr class="border-t border-gray-100 dark:border-gray-800">
                            <td class="py-3 pr-4 whitespace-nowrap">
                                <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                    {{ $item['no_sbg'] }}</p>
                            </td>
                            <td class="py-3 pr-4 whitespace-nowrap">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $item['nasabah'] }}</p>
                            </td>
                            <td class="py-3 pr-4 whitespace-nowrap">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $item['barang'] }}</p>
                            </td>
                            <td class="py-3 pr-4 whitespace-nowrap">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $item['cabang'] }}</p>
                            </td>
                            <td class="py-3 pr-4 whitespace-nowrap">
                                <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ $item['taksiran'] }}</p>
                            </td>
                            <td class="py-3 whitespace-nowrap">
                                <span
                                    class="rounded-full px-2 py-0.5 text-theme-xs font-medium bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-orange-400">
                                    {{ $item['status'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr class="border-t border-gray-100 dark:border-gray-800">
                            <td colspan="6" class="py-6 text-center text-gray-500 dark:text-gray-400">Tidak ada
                                pengajuan gadai terbaru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            import {
                initChartSix
            } from '/resources/js/components/chart/chart-6.js';

            // Override chart-6 dengan label yang sesuai
            const chartSixEl = document.querySelector('#chartSix');
            const monthLabels = @json($monthLabels);
            const chartData = @json($transactionsPerMonth);
            const chartSeries = {
                gadai: chartData.map(item => item.gadai),
                perpanjangan: chartData.map(item => item.perpanjangan),
                pelunasan: chartData.map(item => item.pelunasan),
            };
            const hasBarData = [...chartSeries.gadai, ...chartSeries.perpanjangan, ...chartSeries.pelunasan].some(value =>
                Number(value) > 0);

            if (chartSixEl) {
                const options = {
                    series: [{
                            name: 'Gadai',
                            data: chartSeries.gadai
                        },
                        {
                            name: 'Perpanjangan',
                            data: chartSeries.perpanjangan
                        },
                        {
                            name: 'Pelunasan',
                            data: chartSeries.pelunasan
                        },
                    ],
                    colors: ['#1F5C3A', '#B6D96C', '#174a2e'],
                    chart: {
                        fontFamily: 'Outfit, sans-serif',
                        type: 'bar',
                        stacked: true,
                        height: 315,
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: false
                        },
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '39%',
                            borderRadius: 10,
                            borderRadiusApplication: 'end',
                            borderRadiusWhenStacked: 'last',
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                        categories: monthLabels,
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                    },
                    legend: {
                        show: true,
                        position: 'top',
                        horizontalAlign: 'left',
                        fontFamily: 'Outfit',
                        fontSize: '14px',
                        markers: {
                            size: 5,
                            shape: 'circle',
                            strokeWidth: 0
                        },
                        itemMargin: {
                            horizontal: 10
                        },
                    },
                    yaxis: {
                        title: false
                    },
                    grid: {
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        x: {
                            show: false
                        },
                        y: {
                            formatter: (val) => val + ' transaksi'
                        },
                    },
                };

                if (!hasBarData) {
                    options.series = [];
                    options.noData = {
                        text: 'Tidak ada data transaksi',
                        align: 'center',
                        verticalAlign: 'middle',
                        style: {
                            color: '#6B7280',
                            fontSize: '14px',
                        },
                    };
                }

                const chart = new ApexCharts(chartSixEl, options);
                chart.render();
            }
        </script>
        <script>
            setTimeout(function() {
                const pieEl = document.querySelector('#chartPie');
                if (pieEl && typeof ApexCharts !== 'undefined') {
                    const branchSeries = @json($branchSeries).map(value => Number(value));
                    const branchLabels = @json($branchLabels);
                    const branchTotal = branchSeries.reduce((sum, value) => sum + value, 0);
                    const hasBranchData = branchTotal > 0;
                    const pieSeries = hasBranchData ? branchSeries : [1];
                    const pieLabels = hasBranchData ? branchLabels : ['Tidak ada transaksi'];
                    const pieColors = hasBranchData ? ['#1F5C3A', '#B6D96C', '#174a2e'] : ['#d1d5db'];

                    new ApexCharts(pieEl, {
                        series: pieSeries,
                        chart: {
                            type: 'donut',
                            height: 250,
                            width: '100%',
                            fontFamily: 'Outfit, sans-serif',
                            toolbar: {
                                show: false
                            },
                        },
                        labels: pieLabels,
                        colors: pieColors,
                        legend: {
                            show: false
                        },
                        dataLabels: {
                            enabled: false
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    size: '72%',
                                    labels: {
                                        show: true,
                                        name: {
                                            show: true,
                                            fontSize: '13px',
                                            fontFamily: 'Outfit, sans-serif',
                                            fontWeight: 400,
                                            color: '#6B7280',
                                            offsetY: -4,
                                        },
                                        value: {
                                            show: true,
                                            fontSize: '22px',
                                            fontFamily: 'Outfit, sans-serif',
                                            fontWeight: 700,
                                            color: '#111827',
                                            offsetY: 4,
                                            formatter: (val) => hasBranchData ? val : 0,
                                        },
                                        total: {
                                            show: true,
                                            label: 'Total',
                                            fontSize: '13px',
                                            fontFamily: 'Outfit, sans-serif',
                                            fontWeight: 400,
                                            color: '#6B7280',
                                            formatter: () => branchTotal,
                                        }
                                    }
                                }
                            }
                        },
                        stroke: {
                            width: 2,
                            colors: ['#ffffff']
                        },
                        tooltip: {
                            enabled: false
                        },
                    }).render();
                }
            }, 800);
        </script>
    @endpush

@endsection

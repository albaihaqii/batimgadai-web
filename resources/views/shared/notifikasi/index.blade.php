@extends('layouts.app')
@section('content')

@if(session('success'))
<div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400">
    {{ session('success') }}
</div>
@endif

<div class="rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-800 dark:bg-white/[0.03]">

    <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Notifikasi</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Semua notifikasi aktivitas Anda</p>
        </div>
        @if($notifs->where('is_read', false)->count() > 0)
        <form method="POST" action="{{ route(auth()->user()->role . '.notifikasi.read-all') }}">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                Tandai Semua Dibaca
            </button>
        </form>
        @endif
    </div>

    <div class="divide-y divide-gray-100 dark:divide-gray-800">
        @forelse($notifs as $notif)
        <div class="px-6 py-4 {{ !$notif->is_read ? 'bg-brand-50/30 dark:bg-brand-500/5' : '' }} hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                    {{ $notif->tipe_notif === 'jatuh_tempo' ? 'bg-error-50 dark:bg-error-500/10' :
                       ($notif->tipe_notif === 'approval_gadai' ? 'bg-blue-50 dark:bg-blue-500/10' :
                       ($notif->tipe_notif === 'pengajuan_gadai' ? 'bg-warning-50 dark:bg-warning-500/10' : 'bg-brand-50 dark:bg-brand-500/10')) }}">
                    @if($notif->tipe_notif === 'jatuh_tempo')
                    <svg class="text-error-500" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @elseif($notif->tipe_notif === 'approval_gadai')
                    <svg class="text-blue-500" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @elseif($notif->tipe_notif === 'pengajuan_gadai')
                    <svg class="text-warning-500" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    @else
                    <svg class="text-brand-500" width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    @endif
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between gap-4">
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ $notif->judul }}</p>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if(!$notif->is_read)
                            <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                            @endif
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $notif->created_at->locale('id')->diffForHumans() }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $notif->pesan }}</p>
                    <div class="flex items-center gap-3 mt-2">
                        @if($notif->referensi_tipe === 'gadai' && $notif->referensi_id)
                        <a href="{{ route(auth()->user()->role . '.transaksi.gadai.show', $notif->referensi_id) }}"
                            class="text-xs text-brand-500 hover:text-brand-600 font-medium">
                            Lihat Detail →
                        </a>
                        @endif
                        @if(!$notif->is_read)
                        <form method="POST" action="{{ route(auth()->user()->role . '.notifikasi.read', $notif->id) }}">
                            @csrf
                            <button type="submit" class="text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                Tandai dibaca
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="px-6 py-16 text-center">
            <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <p class="text-sm text-gray-500">Belum ada notifikasi</p>
        </div>
        @endforelse
    </div>

    @if($notifs->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800">
        {{ $notifs->links() }}
    </div>
    @endif
</div>

@endsection
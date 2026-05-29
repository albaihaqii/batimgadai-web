@php
    $cabangList = \App\Models\Branch::where('status', 'aktif')
        ->orderBy('nama')
        ->get();
@endphp

<section id="cabang" class="relative bg-[#1F5C3A] grid-pattern-light py-24 overflow-hidden">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="text-center mb-14 fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 text-[#B6D96C] text-xs font-bold uppercase tracking-wider mb-5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#B6D96C]"></span>
                Lokasi Kami
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white">
                Cabang <span class="text-[#B6D96C]">Kami</span>
            </h2>
            <p class="mt-3 text-white/60">Kunjungi outlet terdekat kami di Jember</p>
        </div>

        {{-- Scroll horizontal kalau > 3 cabang --}}
        <div class="{{ $cabangList->count() > 3 ? 'flex gap-6 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-hide' : 'grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto' }}">
            @foreach($cabangList as $cabang)
            <div class="{{ $cabangList->count() > 3 ? 'flex-shrink-0 w-80 snap-start' : '' }} fade-up bg-white/5 border border-white/10 rounded-2xl p-6 hover:bg-white/10 hover:-translate-y-1.5 transition-all duration-300">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-xl bg-[#B6D96C] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#1F5C3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-white text-sm leading-tight">{{ $cabang->nama }}</h3>
                </div>

                <div class="space-y-3 text-sm text-white/60 mb-5">
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-[#B6D96C] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $cabang->hari_buka ?? 'Senin - Sabtu' }}, {{ $cabang->jam_buka ?? '07.00' }} - {{ $cabang->jam_tutup ?? '17.00' }} WIB</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-[#B6D96C] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                        </svg>
                        <span>{{ $cabang->no_telp ?? '-' }}</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-[#B6D96C] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                        </svg>
                        <span>{{ $cabang->alamat ?? '-' }}</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    @if($cabang->no_telp)
                    <a href="tel:{{ $cabang->no_telp }}"
                        class="flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-lg border border-white/20 text-white/80 text-xs font-semibold hover:bg-white/10 transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                        </svg>
                        Hubungi
                    </a>
                    @endif
                    @if($cabang->latitude && $cabang->longitude)
                    <a href="https://maps.google.com/?q={{ $cabang->latitude }},{{ $cabang->longitude }}" target="_blank"
                        class="flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-lg bg-[#B6D96C] text-[#1F5C3A] text-xs font-semibold hover:bg-[#a8cc5a] transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/>
                        </svg>
                        Lokasi
                    </a>
                    @else
                    <a href="https://maps.google.com/?q={{ urlencode($cabang->alamat ?? $cabang->nama) }}" target="_blank"
                        class="flex-1 flex items-center justify-center gap-1.5 py-2.5 rounded-lg bg-[#B6D96C] text-[#1F5C3A] text-xs font-semibold hover:bg-[#a8cc5a] transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z"/>
                        </svg>
                        Lokasi
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Indikator scroll jika > 3 --}}
        @if($cabangList->count() > 3)
        <p class="text-center text-white/40 text-xs mt-4">
            <svg class="inline w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            Geser untuk melihat cabang lainnya
        </p>
        @endif
    </div>
</section>
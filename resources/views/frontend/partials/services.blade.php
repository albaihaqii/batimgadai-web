<section id="layanan" class="relative bg-[#1F5C3A] grid-pattern-light py-24 overflow-hidden">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="text-center mb-14 fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 text-[#B6D96C] text-xs font-bold uppercase tracking-wider mb-5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#B6D96C]"></span>
                Layanan Kami
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white">
                Layanan Gadai <span class="text-[#B6D96C]">Kami</span>
            </h2>
            <p class="mt-3 text-white/60 max-w-lg mx-auto">Berbagai jenis barang yang dapat Anda gadaikan di BATIM GADAI</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 3h3m-3 3h3"/>','title'=>'Gadai Barang Elektronik','items'=>['Handphone & Smartphone','Laptop & Komputer / PC','Tablet & Perangkat Elektronik Lainnya'],'delay'=>''],
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75l2.25-1.313M12 21.75V19.5m0 2.25l-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-9 5.25-9-5.25v-2.25"/>','title'=>'Gadai Barang Bergerak','items'=>['Peralatan rumah tangga bernilai','Perlengkapan kamar & furniture','Barang lain yang memiliki nilai ekonomis'],'delay'=>'fade-up-delay-1'],
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>','title'=>'Gadai Kendaraan Bermotor','items'=>['Kendaraan dengan BPKB dan unit','Motor dan mobil segala merek','BPKB tanpa unit tidak dapat digadaikan'],'delay'=>'fade-up-delay-2'],
            ] as $s)
            <div class="fade-up {{ $s['delay'] }} bg-white/5 border border-white/10 backdrop-blur-sm rounded-2xl p-7 hover:bg-white/10 hover:-translate-y-1.5 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-[#B6D96C] flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-[#1F5C3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $s['icon'] !!}
                    </svg>
                </div>
                <h3 class="font-bold text-white text-lg mb-4">{{ $s['title'] }}</h3>
                <ul class="space-y-2.5">
                    @foreach($s['items'] as $item)
                    <li class="flex items-center gap-2.5 text-white/60 text-sm">
                        <div class="w-1.5 h-1.5 rounded-full bg-[#B6D96C] flex-shrink-0"></div>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>
    </div>
</section>
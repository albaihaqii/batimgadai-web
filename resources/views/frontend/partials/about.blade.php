{{-- Stats Bar --}}
<section class="relative bg-[#1F5C3A] grid-pattern-light overflow-hidden">
    <div class="max-w-screen-xl mx-auto px-6 py-16">
        <div class="grid grid-cols-3 gap-8 text-center">
            @foreach([
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>','value'=>'120','display'=>'120+','label'=>'Nasabah Aktif'],
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-1.281m5.94 1.28l-1.28 5.941"/>','value'=>'500','display'=>'500+','label'=>'Transaksi Berhasil'],
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>','value'=>'3','display'=>'3','label'=>'Cabang'],
            ] as $stat)
            <div class="group fade-up">
                <div class="w-14 h-14 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center mx-auto mb-4 group-hover:bg-[#B6D96C]/20 transition-all duration-300">
                    <svg class="w-7 h-7 text-[#B6D96C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $stat['icon'] !!}
                    </svg>
                </div>
                <div class="text-4xl font-extrabold text-white mb-1 counter"
                        data-target="{{ $stat['value'] }}"
                        data-suffix="{{ Str::endsWith($stat['display'], '+') ? '+' : '' }}">
                    {{ $stat['display'] }}
                </div>
                <div class="text-[#B6D96C] text-sm font-medium">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Tentang Kami --}}
<section id="tentang" class="relative bg-white grid-pattern py-24 overflow-hidden">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div class="fade-up">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#B6D96C]/20 text-[#1F5C3A] text-xs font-bold uppercase tracking-wider mb-5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#1F5C3A]"></span>
                    Tentang Kami
                </span>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-6 leading-tight">
                    Tentang <span class="text-[#1F5C3A]">BATIM GADAI</span>
                </h2>
                <p class="text-gray-500 leading-relaxed mb-4">
                    BATIM GADAI merupakan pegadaian swasta yang menyediakan layanan pinjaman dana dengan sistem gadai yang aman dan terpercaya. BATIM GADAI menerima berbagai jenis barang jaminan seperti barang elektronik, barang non-elektronik yang memiliki nilai ekonomis, serta kendaraan bermotor dengan BPKB dan unit kendaraan.
                </p>
                <p class="text-gray-500 leading-relaxed mb-8">
                    Seluruh kegiatan operasional BATIM GADAI telah berizin dan diawasi oleh Otoritas Jasa Keuangan (OJK) sehingga memberikan rasa aman dan kepercayaan bagi masyarakat.
                </p>
                <div class="flex items-center gap-3 p-4 rounded-xl bg-[#1F5C3A]/5 border border-[#1F5C3A]/10">
                    <div class="w-10 h-10 rounded-lg bg-[#B6D96C] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-[#1F5C3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-bold text-[#1F5C3A] text-sm">Berizin & Diawasi OJK</p>
                        <p class="text-gray-500 text-xs">Otoritas Jasa Keuangan Republik Indonesia</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                @foreach([
                    ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>','title'=>'Pergadaian Swasta Terpercaya','desc'=>'Perusahaan pergadaian swasta dengan sistem profesional dan terpercaya','delay'=>''],
                    ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>','title'=>'Berizin & Diawasi OJK','desc'=>'Seluruh kegiatan operasional telah berizin dan diawasi Otoritas Jasa Keuangan','delay'=>'fade-up-delay-1'],
                    ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>','title'=>'Melayani Semua Kalangan','desc'=>'Melayani kebutuhan pinjaman masyarakat dari berbagai kalangan dengan mudah','delay'=>'fade-up-delay-2'],
                    ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0H3"/>','title'=>'Sistem Digital Terintegrasi','desc'=>'Pengelolaan transaksi gadai secara terintegrasi dan terkomputerisasi modern','delay'=>'fade-up-delay-3'],
                ] as $item)
                <div class="fade-up {{ $item['delay'] }} bg-white rounded-2xl p-5 hover:shadow-lg hover:-translate-y-1.5 transition-all duration-300 border border-gray-100 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-[#1F5C3A] flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-[#B6D96C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $item['icon'] !!}
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-900 text-sm mb-1">{{ $item['title'] }}</h4>
                    <p class="text-gray-500 text-xs leading-relaxed">{{ $item['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    const counters = document.querySelectorAll('.counter');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            const target = parseInt(el.dataset.target);
            const suffix = el.dataset.suffix || '';
            let start = 0;
            const step = target / (1800 / 16);
            const interval = setInterval(() => {
                start += step;
                if (start >= target) { start = target; clearInterval(interval); }
                el.textContent = Math.floor(start).toLocaleString('id-ID') + suffix;
            }, 16);
            observer.unobserve(el);
        });
    }, { threshold: 0.5 });
    counters.forEach(c => observer.observe(c));
</script>
@endpush
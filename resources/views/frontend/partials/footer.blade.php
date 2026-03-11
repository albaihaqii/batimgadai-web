<footer class="bg-gray-900 text-white">
    <div class="max-w-screen-xl mx-auto px-6 py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-3 mb-5">
                    <img src="{{ asset('frontend/images/logo.png') }}" alt="BATIM GADAI" class="h-9 w-auto"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <div class="w-9 h-9 rounded-lg bg-[#1F5C3A] items-center justify-center hidden">
                        <span class="text-white font-bold text-sm">BG</span>
                    </div>
                    <span class="font-bold text-xl text-white">BATIM GADAI</span>
                </a>
                <p class="text-gray-400 text-sm leading-relaxed mb-5">
                    Perusahaan pergadaian swasta berizin dan diawasi OJK yang menyediakan layanan pinjaman dana dengan sistem gadai barang bergerak yang aman dan terpercaya.
                </p>
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-[#B6D96C] flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#1F5C3A]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/>
                        </svg>
                    </div>
                    <span class="text-xs text-gray-400">Berizin & Diawasi OJK</span>
                </div>
            </div>

            <div>
                <h3 class="font-bold text-white mb-5 text-sm uppercase tracking-wider">Menu</h3>
                <ul class="space-y-3">
                    @foreach([
                        ['href'=>'#tentang','label'=>'Tentang Kami'],
                        ['href'=>'#layanan','label'=>'Layanan'],
                        ['href'=>'#cabang','label'=>'Cabang'],
                        ['href'=>'#syarat','label'=>'Syarat & Ketentuan'],
                        ['href'=>'#faq','label'=>'FAQ'],
                        ['href'=>'#kontak','label'=>'Kontak'],
                    ] as $menu)
                    <li>
                        <a href="{{ $menu['href'] }}" class="text-gray-400 hover:text-[#B6D96C] text-sm transition-colors duration-200">
                            {{ $menu['label'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="font-bold text-white mb-5 text-sm uppercase tracking-wider">Kontak</h3>
                <ul class="space-y-4">
                    @foreach([
                        ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>','text'=>'Jl. Mangli No. 10, Jember'],
                        ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>','text'=>'(0331) 123-456'],
                        ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>','text'=>'info@batimgadai.com'],
                    ] as $k)
                    <li class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-[#B6D96C] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $k['icon'] !!}
                        </svg>
                        <span class="text-gray-400 text-sm">{{ $k['text'] }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 mt-12 pt-8 text-center">
            <p class="text-gray-500 text-sm">&copy; 2026 BATIM GADAI. All rights reserved. Berizin & Diawasi OJK.</p>
        </div>
    </div>
</footer>
<section id="aplikasi" class="relative bg-white grid-pattern py-24 overflow-hidden">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="text-center mb-14 fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#B6D96C]/20 text-[#1F5C3A] text-xs font-bold uppercase tracking-wider mb-5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#1F5C3A]"></span>
                Aplikasi Mobile
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-4 leading-tight">
                Aplikasi Mobile <span class="text-[#1F5C3A]">BATIM GADAI</span>
            </h2>
            <p class="text-gray-500 max-w-lg mx-auto leading-relaxed">
                Kelola gadai Anda dari mana saja dengan aplikasi mobile BATIM GADAI. Simulasi, booking, hingga pembayaran semua dalam genggaman Anda.
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-16 items-center">
            {{-- Fitur List --}}
            <div class="fade-up">
                <ul class="space-y-4">
                    @foreach([
                        ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 3h3m-3 3h3"/>','title'=>'Simulasi Gadai','desc'=>'Estimasi nilai pinjaman barang sebelum datang ke outlet'],
                        ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>','title'=>'Booking Kunjungan','desc'=>'Booking kunjungan ke outlet terdekat dengan mudah'],
                        ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>','title'=>'Riwayat Transaksi','desc'=>'Melihat riwayat barang gadai dan status transaksi secara real-time'],
                        ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>','title'=>'Perpanjangan Gadai','desc'=>'Perpanjangan masa gadai langsung dari aplikasi tanpa ke outlet'],
                        ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>','title'=>'Pembayaran Digital','desc'=>'Pembayaran jasa pinjaman melalui transfer bank langsung dari aplikasi'],
                        ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>','title'=>'Notifikasi Jatuh Tempo','desc'=>'Pengingat otomatis sebelum masa gadai berakhir'],
                    ] as $item)
                    <li class="flex items-start gap-4 p-4 rounded-2xl hover:bg-gray-50 transition-all duration-200 border border-transparent hover:border-gray-100">
                        <div class="w-10 h-10 rounded-xl bg-[#1F5C3A] flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-[#B6D96C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $item['icon'] !!}
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-sm mb-0.5">{{ $item['title'] }}</p>
                            <p class="text-gray-500 text-sm leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Mockup Slider --}}
            <div class="fade-up flex justify-center">
                <div class="relative w-64 md:w-72">
                    {{-- Decorative background --}}
                    <div class="absolute -inset-4 bg-[#B6D96C]/10 rounded-[3rem] -z-10"></div>

                    {{-- Slide 1 --}}
                    <div class="mockup-slide relative transition-opacity duration-500">
                        <img src="{{ asset('frontend/images/mockup-1.png') }}" alt="Mockup 1"
                             class="w-full h-auto object-contain rounded-3xl shadow-2xl"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="hidden w-full aspect-[9/19.5] rounded-3xl bg-gray-200 flex-col items-center justify-center text-gray-400 p-6 text-center shadow-2xl">
                            <svg class="w-14 h-14 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3"/>
                            </svg>
                            <p class="text-sm font-semibold text-gray-500">Beranda Aplikasi</p>
                            <p class="text-xs text-gray-400 mt-1">mockup-1.png</p>
                        </div>
                    </div>

                    {{-- Slide 2 --}}
                    <div class="mockup-slide absolute inset-0 transition-opacity duration-500 opacity-0 pointer-events-none">
                        <img src="{{ asset('frontend/images/mockup-2.png') }}" alt="Mockup 2"
                             class="w-full h-auto object-contain rounded-3xl shadow-2xl"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="hidden w-full aspect-[9/19.5] rounded-3xl bg-gray-200 flex-col items-center justify-center text-gray-400 p-6 text-center shadow-2xl">
                            <svg class="w-14 h-14 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3"/>
                            </svg>
                            <p class="text-sm font-semibold text-gray-500">Simulasi Gadai</p>
                            <p class="text-xs text-gray-400 mt-1">mockup-2.png</p>
                        </div>
                    </div>

                    {{-- Slide 3 --}}
                    <div class="mockup-slide absolute inset-0 transition-opacity duration-500 opacity-0 pointer-events-none">
                        <img src="{{ asset('frontend/images/mockup-3.png') }}" alt="Mockup 3"
                             class="w-full h-auto object-contain rounded-3xl shadow-2xl"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="hidden w-full aspect-[9/19.5] rounded-3xl bg-gray-200 flex-col items-center justify-center text-gray-400 p-6 text-center shadow-2xl">
                            <svg class="w-14 h-14 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3"/>
                            </svg>
                            <p class="text-sm font-semibold text-gray-500">Riwayat Gadai</p>
                            <p class="text-xs text-gray-400 mt-1">mockup-3.png</p>
                        </div>
                    </div>

                    {{-- Prev / Next --}}
                    <button id="mockup-prev"
                            class="absolute left-[-20px] top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-[#1F5C3A] hover:bg-[#174a2e] flex items-center justify-center text-white shadow-lg transition-all z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button id="mockup-next"
                            class="absolute right-[-20px] top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-[#1F5C3A] hover:bg-[#174a2e] flex items-center justify-center text-white shadow-lg transition-all z-10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    {{-- Dots --}}
                    <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 flex gap-2">
                        <button class="mockup-dot w-6 h-2 rounded-full bg-[#1F5C3A] transition-all duration-300" data-index="0"></button>
                        <button class="mockup-dot w-2 h-2 rounded-full bg-gray-300 hover:bg-gray-400 transition-all duration-300" data-index="1"></button>
                        <button class="mockup-dot w-2 h-2 rounded-full bg-gray-300 hover:bg-gray-400 transition-all duration-300" data-index="2"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    const mockupSlides = document.querySelectorAll('.mockup-slide');
    const mockupDots = document.querySelectorAll('.mockup-dot');
    let mockupCurrent = 0, mockupTimer;

    function mockupGoTo(n) {
        mockupSlides[mockupCurrent].classList.add('opacity-0', 'pointer-events-none');
        mockupDots[mockupCurrent].classList.remove('bg-[#1F5C3A]', 'w-6');
        mockupDots[mockupCurrent].classList.add('bg-gray-300', 'w-2');
        mockupCurrent = (n + mockupSlides.length) % mockupSlides.length;
        mockupSlides[mockupCurrent].classList.remove('opacity-0', 'pointer-events-none');
        mockupDots[mockupCurrent].classList.add('bg-[#1F5C3A]', 'w-6');
        mockupDots[mockupCurrent].classList.remove('bg-gray-300', 'w-2');
    }

    function mockupStartTimer() {
        mockupTimer = setInterval(() => mockupGoTo(mockupCurrent + 1), 3500);
    }

    document.getElementById('mockup-next').onclick = () => { clearInterval(mockupTimer); mockupGoTo(mockupCurrent + 1); mockupStartTimer(); };
    document.getElementById('mockup-prev').onclick = () => { clearInterval(mockupTimer); mockupGoTo(mockupCurrent - 1); mockupStartTimer(); };

    mockupDots.forEach(d => d.addEventListener('click', () => {
        clearInterval(mockupTimer);
        mockupGoTo(+d.dataset.index);
        mockupStartTimer();
    }));

    mockupStartTimer();
</script>
@endpush

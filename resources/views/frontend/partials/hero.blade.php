<section id="beranda" class="relative w-full h-screen min-h-[600px] overflow-hidden">

    {{-- Slide 1 --}}
    <div class="hero-slide absolute inset-0 transition-opacity duration-700"
            style="background: linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url('{{ asset('frontend/images/hero-1.png') }}') center/cover no-repeat;">
        <div class="flex items-center h-full max-w-screen-xl mx-auto px-6 pt-16">
            <div class="max-w-2xl text-white">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                    Gadai Cepat, Aman &<br><span class="text-[#B6D96C]">Terpercaya</span>
                </h1>
                <p class="text-lg text-gray-200 mb-8 leading-relaxed max-w-xl">
                    BATIM GADAI hadir sebagai solusi pinjaman dana cepat dengan sistem gadai barang yang aman, transparan, dan terpercaya. Proses mudah, pencairan cepat, dan barang jaminan tersimpan dengan aman di outlet kami yang telah berizin OJK.
                </p>
                <div class="flex gap-4 flex-wrap">
                    <a href="#cabang"
                        class="px-7 py-3.5 rounded-lg font-semibold bg-[#B6D96C] text-[#1F5C3A] hover:bg-[#a8cc5a] transition-all duration-200 inline-flex items-center gap-2">
                        Temukan Cabang
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="#layanan"
                        class="px-7 py-3.5 rounded-lg font-semibold text-white border-2 border-white/50 hover:bg-white/10 transition-all duration-200">
                        Lihat Layanan
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Slide 2 --}}
    <div class="hero-slide absolute inset-0 transition-opacity duration-700 opacity-0 pointer-events-none"
            style="background: linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url('{{ asset('frontend/images/hero-2.png') }}') center/cover no-repeat;">
        <div class="flex items-center h-full max-w-screen-xl mx-auto px-6 pt-16">
            <div class="max-w-2xl text-white">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                    Kelola Gadai Lebih Mudah dengan<br><span class="text-[#B6D96C]">Sistem Digital</span>
                </h1>
                <p class="text-lg text-gray-200 mb-8 leading-relaxed max-w-xl">
                    Sistem Informasi Gadai Elektronik BATIM GADAI membantu pengelolaan data nasabah, transaksi gadai, perpanjangan, dan pelunasan secara terkomputerisasi. Seluruh proses tercatat rapi, akurat, dan dapat diakses kapan saja oleh tim kami.
                </p>
                <a href="#alur"
                    class="px-7 py-3.5 rounded-lg font-semibold bg-[#B6D96C] text-[#1F5C3A] hover:bg-[#a8cc5a] transition-all duration-200 inline-flex items-center gap-2">
                    Pelajari Sistem
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Slide 3 --}}
    <div class="hero-slide absolute inset-0 transition-opacity duration-700 opacity-0 pointer-events-none"
            style="background: linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url('{{ asset('frontend/images/hero-3.png') }}') center/cover no-repeat;">
        <div class="flex items-center h-full max-w-screen-xl mx-auto px-6 pt-16">
            <div class="max-w-2xl text-white">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                    Simulasi dan Booking<br><span class="text-[#B6D96C]">Gadai dari Aplikasi</span>
                </h1>
                <p class="text-lg text-gray-200 mb-8 leading-relaxed max-w-xl">
                    Nasabah dapat melakukan simulasi estimasi nilai pinjaman, booking kunjungan ke outlet, memantau status transaksi gadai, hingga mendapatkan notifikasi pengingat jatuh tempo langsung dari aplikasi mobile BATIM GADAI di genggaman Anda.
                </p>
                <a href="#aplikasi"
                    class="px-7 py-3.5 rounded-lg font-semibold bg-[#B6D96C] text-[#1F5C3A] hover:bg-[#a8cc5a] transition-all duration-200 inline-flex items-center gap-2">
                    Preview Aplikasi
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Prev / Next --}}
    <button id="hero-prev"
            class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-black/20 hover:bg-black/40 backdrop-blur-sm flex items-center justify-center text-white transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>
    <button id="hero-next"
            class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-black/20 hover:bg-black/40 backdrop-blur-sm flex items-center justify-center text-white transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </button>

    {{-- Dots --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2.5">
        <button class="hero-dot w-8 h-2 rounded-full bg-[#B6D96C] transition-all duration-300" data-index="0"></button>
        <button class="hero-dot w-2 h-2 rounded-full bg-white/40 hover:bg-white/70 transition-all duration-300" data-index="1"></button>
        <button class="hero-dot w-2 h-2 rounded-full bg-white/40 hover:bg-white/70 transition-all duration-300" data-index="2"></button>
    </div>
</section>

@push('scripts')
<script>
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    let current = 0, timer;

    function goTo(n) {
        // Sembunyikan slide aktif + nonaktifkan klik
        slides[current].classList.add('opacity-0', 'pointer-events-none');
        dots[current].classList.remove('bg-[#B6D96C]', 'w-8');
        dots[current].classList.add('bg-white/40', 'w-2');

        // Tampilkan slide baru + aktifkan klik
        current = (n + slides.length) % slides.length;
        slides[current].classList.remove('opacity-0', 'pointer-events-none');
        dots[current].classList.add('bg-[#B6D96C]', 'w-8');
        dots[current].classList.remove('bg-white/40', 'w-2');
    }

    function startTimer() {
        timer = setInterval(() => goTo(current + 1), 5000);
    }

    document.getElementById('hero-next').onclick = () => { clearInterval(timer); goTo(current + 1); startTimer(); };
    document.getElementById('hero-prev').onclick = () => { clearInterval(timer); goTo(current - 1); startTimer(); };
    dots.forEach(d => d.addEventListener('click', () => { clearInterval(timer); goTo(+d.dataset.index); startTimer(); }));

    startTimer();
</script>
@endpush
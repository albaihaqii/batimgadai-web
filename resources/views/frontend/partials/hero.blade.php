@php
    $heroSlides = \App\Models\Banner::where('tipe', 'landing')
        ->where('is_active', true)
        ->orderBy('urutan')
        ->limit(3)
        ->get();

    // Fallback ke slide default kalau tidak ada banner di DB
    if ($heroSlides->isEmpty()) {
        $heroSlides = collect([
            (object)[
                'judul'       => 'Gadai Cepat, Aman &',
                'subjudul'    => 'Terpercaya',
                'deskripsi'   => 'BATIM GADAI hadir sebagai solusi pinjaman dana cepat dengan sistem gadai barang yang aman, transparan, dan terpercaya.',
                'teks_tombol' => 'Temukan Cabang',
                'url_tombol'  => '#cabang',
                'foto'        => null,
                'foto_url'    => asset('frontend/images/hero-1.png'),
            ],
            (object)[
                'judul'       => 'Kelola Gadai Lebih Mudah dengan',
                'subjudul'    => 'Sistem Digital',
                'deskripsi'   => 'Sistem Informasi Gadai Elektronik BATIM GADAI membantu pengelolaan data nasabah, transaksi gadai secara terkomputerisasi.',
                'teks_tombol' => 'Pelajari Sistem',
                'url_tombol'  => '#alur',
                'foto'        => null,
                'foto_url'    => asset('frontend/images/hero-2.png'),
            ],
            (object)[
                'judul'       => 'Simulasi dan Booking',
                'subjudul'    => 'Gadai dari Aplikasi',
                'deskripsi'   => 'Nasabah dapat melakukan simulasi estimasi nilai pinjaman, booking kunjungan ke outlet, dan memantau status transaksi.',
                'teks_tombol' => 'Preview Aplikasi',
                'url_tombol'  => '#aplikasi',
                'foto'        => null,
                'foto_url'    => asset('frontend/images/hero-3.png'),
            ],
        ]);
    }
@endphp

<section id="beranda" class="relative w-full h-screen min-h-[600px] overflow-hidden">

    @foreach($heroSlides as $i => $slide)
    @php
        $fotoUrl = isset($slide->foto) && $slide->foto
            ? asset('storage/' . $slide->foto)
            : ($slide->foto_url ?? asset('frontend/images/hero-1.png'));
    @endphp
    <div class="hero-slide absolute inset-0 transition-opacity duration-700 {{ $i > 0 ? 'opacity-0 pointer-events-none' : '' }}"
        style="background: linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url('{{ $fotoUrl }}') center/cover no-repeat;">
        <div class="flex items-center h-full max-w-screen-xl mx-auto px-6 pt-16">
            <div class="max-w-2xl text-white">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                    {{ $slide->judul }}<br>
                    @if($slide->subjudul)
                    <span class="text-[#B6D96C]">{{ $slide->subjudul }}</span>
                    @endif
                </h1>
                @if($slide->deskripsi)
                <p class="text-lg text-gray-200 mb-8 leading-relaxed max-w-xl">{{ $slide->deskripsi }}</p>
                @endif
                @if($slide->teks_tombol)
                <a href="{{ $slide->url_tombol ?? '#' }}"
                    class="px-7 py-3.5 rounded-lg font-semibold bg-[#B6D96C] text-[#1F5C3A] hover:bg-[#a8cc5a] transition-all duration-200 inline-flex items-center gap-2">
                    {{ $slide->teks_tombol }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    {{-- Prev / Next --}}
    <button id="hero-prev"
        class="absolute left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-black/20 hover:bg-black/40 backdrop-blur-sm flex items-center justify-center text-white transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button id="hero-next"
        class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-black/20 hover:bg-black/40 backdrop-blur-sm flex items-center justify-center text-white transition-all">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>

    {{-- Dots --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2.5" id="hero-dots">
        @foreach($heroSlides as $i => $slide)
        <button class="hero-dot transition-all duration-300 {{ $i === 0 ? 'w-8 h-2 rounded-full bg-[#B6D96C]' : 'w-2 h-2 rounded-full bg-white/40 hover:bg-white/70' }}"
            data-index="{{ $i }}"></button>
        @endforeach
    </div>
</section>

@push('scripts')
<script>
const slides = document.querySelectorAll('.hero-slide');
const dots   = document.querySelectorAll('.hero-dot');
let current  = 0, timer;

function goTo(n) {
    slides[current].classList.add('opacity-0', 'pointer-events-none');
    dots[current].classList.remove('bg-[#B6D96C]', 'w-8');
    dots[current].classList.add('bg-white/40', 'w-2');

    current = (n + slides.length) % slides.length;
    slides[current].classList.remove('opacity-0', 'pointer-events-none');
    dots[current].classList.add('bg-[#B6D96C]', 'w-8');
    dots[current].classList.remove('bg-white/40', 'w-2');
}

function startTimer() { timer = setInterval(() => goTo(current + 1), 5000); }

document.getElementById('hero-next').onclick = () => { clearInterval(timer); goTo(current + 1); startTimer(); };
document.getElementById('hero-prev').onclick = () => { clearInterval(timer); goTo(current - 1); startTimer(); };
dots.forEach(d => d.addEventListener('click', () => { clearInterval(timer); goTo(+d.dataset.index); startTimer(); }));

startTimer();
</script>
@endpush
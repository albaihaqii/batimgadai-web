@php
    $slides = isset($heroSlides) && $heroSlides->count()
        ? $heroSlides->values()
        : collect(\App\Models\HeroSlide::defaults());
@endphp

<section id="beranda" class="relative w-full h-screen min-h-[600px] overflow-hidden">
    @foreach ($slides as $index => $slide)
        <div class="hero-slide absolute inset-0 transition-opacity duration-700 {{ $index === 0 ? '' : 'opacity-0 pointer-events-none' }}"
            style="background: linear-gradient(rgba(0,0,0,0.5),rgba(0,0,0,0.5)), url('{{ asset($slide->image_path) }}') center/cover no-repeat;">
            <div class="flex items-center h-full max-w-screen-xl mx-auto px-6 pt-16">
                <div class="max-w-2xl text-white">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                        {{ $slide->title }}
                        @if ($slide->highlighted_title)
                            <br><span class="text-[#B6D96C]">{{ $slide->highlighted_title }}</span>
                        @endif
                    </h1>
                    <p class="text-lg text-gray-200 mb-8 leading-relaxed max-w-xl">
                        {{ $slide->description }}
                    </p>
                    <div class="flex gap-4 flex-wrap">
                        @if ($slide->primary_button_label && $slide->primary_button_url)
                            <a href="{{ $slide->primary_button_url }}"
                                class="px-7 py-3.5 rounded-lg font-semibold bg-[#B6D96C] text-[#1F5C3A] hover:bg-[#a8cc5a] transition-all duration-200 inline-flex items-center gap-2">
                                {{ $slide->primary_button_label }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        @endif
                        @if ($slide->secondary_button_label && $slide->secondary_button_url)
                            <a href="{{ $slide->secondary_button_url }}"
                                class="px-7 py-3.5 rounded-lg font-semibold text-white border-2 border-white/50 hover:bg-white/10 transition-all duration-200">
                                {{ $slide->secondary_button_label }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @if ($slides->count() > 1)
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

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-2.5">
            @foreach ($slides as $index => $slide)
                <button class="hero-dot h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'w-8 bg-[#B6D96C]' : 'w-2 bg-white/40 hover:bg-white/70' }}"
                    data-index="{{ $index }}"></button>
            @endforeach
        </div>
    @endif
</section>

@if ($slides->count() > 1)
    @push('scripts')
        <script>
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.hero-dot');
            let current = 0, timer;

            function goTo(n) {
                slides[current].classList.add('opacity-0', 'pointer-events-none');
                dots[current].classList.remove('bg-[#B6D96C]', 'w-8');
                dots[current].classList.add('bg-white/40', 'w-2');

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
@endif

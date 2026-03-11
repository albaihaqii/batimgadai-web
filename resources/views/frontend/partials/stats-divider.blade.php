<section class="relative bg-[#1F5C3A] grid-pattern-light overflow-hidden py-12">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="grid grid-cols-3 gap-8 text-center">
            @foreach([
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/>','title'=>'Pencairan Hari Ini','desc'=>'Dana cair di hari yang sama saat pengajuan gadai'],
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971z"/>','title'=>'Taksiran Transparan','desc'=>'Nilai taksiran dihitung langsung di depan nasabah'],
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5zM13.5 14.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125v-2.25zM18.375 14.625c0-.621.504-1.125 1.125-1.125h.375a1.125 1.125 0 010 2.25h-.375a1.125 1.125 0 01-1.125-1.125zM13.5 19.875c0-.621.504-1.125 1.125-1.125h.375a1.125 1.125 0 010 2.25h-.375A1.125 1.125 0 0113.5 19.875zM18.375 19.875c0-.621.504-1.125 1.125-1.125h.375a1.125 1.125 0 010 2.25h-.375a1.125 1.125 0 01-1.125-1.125z"/>','title'=>'Loker Barcode','desc'=>'Barang disimpan di loker khusus dengan sistem barcode terintegrasi'],
            ] as $item)
            <div class="group fade-up flex flex-col items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center group-hover:bg-[#B6D96C]/20 transition-all duration-300">
                    <svg class="w-6 h-6 text-[#B6D96C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $item['icon'] !!}
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-sm mb-1">{{ $item['title'] }}</p>
                    <p class="text-white/60 text-xs leading-relaxed">{{ $item['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
    const counters2 = document.querySelectorAll('.counter-2');
    const observer2 = new IntersectionObserver((entries) => {
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
            observer2.unobserve(el);
        });
    }, { threshold: 0.5 });
    counters2.forEach(c => observer2.observe(c));
</script>
@endpush
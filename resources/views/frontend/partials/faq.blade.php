<section id="faq" class="relative bg-[#1F5C3A] grid-pattern-light py-24 overflow-hidden">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="text-center mb-14 fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 text-[#B6D96C] text-xs font-bold uppercase tracking-wider mb-5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#B6D96C]"></span>
                FAQ
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-white">
                Pertanyaan yang <span class="text-[#B6D96C]">Sering Diajukan</span>
            </h2>
        </div>
        <div class="max-w-3xl mx-auto space-y-3">
            @foreach([
                ['q'=>'Barang apa saja yang dapat digadaikan?','a'=>'BATIM GADAI menerima barang elektronik (HP, laptop, tablet), barang bergerak yang memiliki nilai ekonomis, serta kendaraan bermotor dengan BPKB dan unit kendaraan.'],
                ['q'=>'Apakah bisa gadai tanpa datang ke outlet?','a'=>'Saat ini proses gadai wajib dilakukan di outlet karena memerlukan penilaian fisik barang oleh petugas kami secara langsung.'],
                ['q'=>'Apakah bisa gadai BPKB saja?','a'=>'Tidak, BATIM GADAI mensyaratkan kendaraan bermotor dengan BPKB beserta unit kendaraannya. BPKB tanpa unit kendaraan tidak dapat digadaikan.'],
                ['q'=>'Bagaimana cara melakukan perpanjangan gadai?','a'=>'Perpanjangan dilakukan langsung di outlet BATIM GADAI sebelum jatuh tempo dengan membayar biaya jasa pinjaman.'],
                ['q'=>'Berapa lama proses pencairan dana?','a'=>'Proses pencairan dana berlangsung sekitar 15-30 menit setelah proses penilaian barang dan persetujuan pinjaman selesai.'],
            ] as $i => $faq)
            <div class="fade-up border border-white/10 rounded-2xl overflow-hidden hover:border-[#B6D96C]/40 transition-all duration-300">
                <button onclick="toggleFaq({{ $i }})"
                        class="w-full flex items-center justify-between p-5 text-left bg-white/5 hover:bg-white/10 transition-colors duration-200">
                    <span class="font-semibold text-white text-sm pr-4">{{ $faq['q'] }}</span>
                    <svg id="faq-icon-{{ $i }}"
                            class="w-5 h-5 text-[#B6D96C] flex-shrink-0 transition-transform duration-300"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="faq-body-{{ $i }}" class="hidden border-t border-white/10">
                    <p class="px-5 py-4 text-white/60 text-sm leading-relaxed bg-white/5">{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@push('scripts')
<script>
    function toggleFaq(i) {
        const body = document.getElementById('faq-body-' + i);
        const icon = document.getElementById('faq-icon-' + i);
        body.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }
</script>
@endpush
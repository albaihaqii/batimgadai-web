<section id="kontak" class="relative bg-white grid-pattern py-24 overflow-hidden">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="text-center mb-14 fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#B6D96C]/20 text-[#1F5C3A] text-xs font-bold uppercase tracking-wider mb-5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#1F5C3A]"></span>
                Kontak
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900">
                Hubungi <span class="text-[#1F5C3A]">Kami</span>
            </h2>
            <p class="mt-3 text-gray-500">Silakan hubungi kami untuk informasi lebih lanjut</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto">
            @foreach([
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>','label'=>'Alamat','value'=>'Jl. Mangli No. 10, Jember, Jawa Timur','href'=>'#','delay'=>''],
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>','label'=>'Telepon','value'=>'(0331) 123-456','href'=>'tel:0331123456','delay'=>'fade-up-delay-1'],
                ['whatsapp'=>true,'label'=>'WhatsApp','value'=>'0812-3456-7890','href'=>'https://wa.me/6281234567890','delay'=>'fade-up-delay-2'],
                ['icon'=>'<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>','label'=>'Email','value'=>'info@batimgadai.com','href'=>'mailto:info@batimgadai.com','delay'=>'fade-up-delay-3'],
            ] as $k)
            <a href="{{ $k['href'] }}" class="fade-up {{ $k['delay'] }} bg-white rounded-2xl p-6 text-center shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1.5 transition-all duration-300 group">
                <div class="w-12 h-12 rounded-xl bg-[#1F5C3A] flex items-center justify-center mx-auto mb-4 group-hover:bg-[#B6D96C] transition-colors duration-300">
                    @if(!empty($k['whatsapp']))
                    <svg class="w-5 h-5 text-[#B6D96C] group-hover:text-[#1F5C3A] transition-colors duration-300" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    @else
                    <svg class="w-5 h-5 text-[#B6D96C] group-hover:text-[#1F5C3A] transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $k['icon'] !!}
                    </svg>
                    @endif
                </div>
                <p class="font-semibold text-gray-900 text-sm mb-1">{{ $k['label'] }}</p>
                <p class="text-gray-500 text-xs leading-relaxed">{{ $k['value'] }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>
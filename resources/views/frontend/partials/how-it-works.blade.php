<section id="alur" class="relative bg-white grid-pattern py-24 overflow-hidden">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="text-center mb-14 fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#B6D96C]/20 text-[#1F5C3A] text-xs font-bold uppercase tracking-wider mb-5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#1F5C3A]"></span>
                Alur Proses
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900">
                Alur Proses <span class="text-[#1F5C3A]">Gadai</span>
            </h2>
            <p class="mt-3 text-gray-500">Langkah mudah untuk mendapatkan pinjaman di BATIM GADAI</p>
        </div>

        {{-- Baris 1 --}}
        <div class="relative grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="hidden md:block absolute top-8 h-0.5 bg-[#B6D96C]/40 z-0" style="left:16.6%;right:16.6%"></div>
            @foreach([
                ['no'=>'01','title'=>'Datang ke Outlet','desc'=>'Nasabah datang langsung ke outlet BATIM GADAI terdekat membawa barang yang akan dijadikan jaminan gadai','delay'=>''],
                ['no'=>'02','title'=>'Input Data Nasabah','desc'=>'Petugas melakukan input data identitas nasabah ke dalam sistem informasi gadai elektronik BATIM GADAI','delay'=>'fade-up-delay-1'],
                ['no'=>'03','title'=>'Penaksiran Barang','desc'=>'Juru taksir melakukan penilaian fisik barang jaminan dan menentukan estimasi range nilai harga barang','delay'=>'fade-up-delay-2'],
            ] as $step)
            <div class="fade-up {{ $step['delay'] }} bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1.5 transition-all duration-300 relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-[#1F5C3A] flex items-center justify-center text-[#B6D96C] font-extrabold text-lg flex-shrink-0 shadow-lg">
                        {{ $step['no'] }}
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">LANGKAH {{ $step['no'] }}</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Baris 2 --}}
        <div class="relative grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="hidden md:block absolute top-8 h-0.5 bg-[#B6D96C]/40 z-0" style="left:16.6%;right:16.6%"></div>
            @foreach([
                ['no'=>'04','title'=>'Persetujuan Pinjaman','desc'=>'Pimpinan cabang meninjau hasil taksiran dan menentukan nilai pinjaman yang disetujui untuk diberikan kepada nasabah','delay'=>''],
                ['no'=>'05','title'=>'Cetak Surat Bukti Gadai','desc'=>'Admin mencetak Surat Bukti Gadai (SBG) sebagai bukti resmi transaksi gadai yang ditandatangani kedua belah pihak','delay'=>'fade-up-delay-1'],
                ['no'=>'06','title'=>'Dana Cair','desc'=>'Dana pinjaman langsung diberikan secara tunai kepada nasabah setelah seluruh proses administrasi selesai','delay'=>'fade-up-delay-2'],
            ] as $step)
            <div class="fade-up {{ $step['delay'] }} bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1.5 transition-all duration-300 relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-full bg-[#B6D96C] flex items-center justify-center text-[#1F5C3A] font-extrabold text-lg flex-shrink-0 shadow-lg">
                        {{ $step['no'] }}
                    </div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">LANGKAH {{ $step['no'] }}</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">{{ $step['title'] }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
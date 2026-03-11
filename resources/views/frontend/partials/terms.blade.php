<section id="syarat" class="relative bg-white grid-pattern py-24 overflow-hidden">
    <div class="max-w-screen-xl mx-auto px-6">
        <div class="text-center mb-14 fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[#B6D96C]/20 text-[#1F5C3A] text-xs font-bold uppercase tracking-wider mb-5">
                <span class="w-1.5 h-1.5 rounded-full bg-[#1F5C3A]"></span>
                Ketentuan
            </span>
            <h2 class="text-3xl lg:text-4xl font-extrabold text-gray-900">
                Syarat & <span class="text-[#1F5C3A]">Ketentuan</span>
            </h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-5xl mx-auto">
            @foreach([
                ['no'=>1,'text'=>'Barang jaminan harus milik sah nasabah dan bukan hasil dari tindakan melawan hukum'],
                ['no'=>2,'text'=>'Nasabah wajib membawa identitas diri yang sah (KTP/SIM/KTM) saat mengajukan gadai'],
                ['no'=>3,'text'=>'Jangka waktu gadai adalah 30 hari kalender terhitung sejak tanggal transaksi gadai'],
                ['no'=>4,'text'=>'Nasabah dapat memperpanjang masa gadai dengan membayar jasa pinjaman sebelum jatuh tempo'],
                ['no'=>5,'text'=>'Biaya jasa pinjaman dihitung berdasarkan persentase dari nilai pinjaman yang telah disetujui'],
                ['no'=>6,'text'=>'Barang jaminan akan disimpan dengan aman di gudang penyimpanan outlet selama masa gadai'],
                ['no'=>7,'text'=>'Jika pinjaman tidak dilunasi melewati jatuh tempo, barang dapat dijual sesuai ketentuan'],
                ['no'=>8,'text'=>'Nasabah berhak menebus barang jaminan sebelum jatuh tempo dengan melunasi seluruh pinjaman'],
                ['no'=>9,'text'=>'Segala kerusakan barang akibat force majeure di luar tanggung jawab pihak BATIM GADAI'],
                ['no'=>10,'text'=>'Dengan menggadaikan barang, nasabah dianggap menyetujui seluruh syarat dan ketentuan berlaku'],
            ] as $term)
            <div class="fade-up bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-start gap-4 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                <div class="w-9 h-9 rounded-lg bg-[#1F5C3A] flex items-center justify-center text-[#B6D96C] font-bold text-sm flex-shrink-0">
                    {{ $term['no'] }}
                </div>
                <p class="text-gray-600 text-sm leading-relaxed pt-1">{{ $term['text'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
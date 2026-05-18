@extends('layouts.app')

@section('content')
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Simulasi Harga Gadai</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Estimasi nilai gadai berdasarkan histori transaksi internal.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs text-gray-500 dark:text-gray-400">Total Transaksi Referensi</p>
                <p class="text-lg font-bold text-gray-800 dark:text-white">{{ number_format($totalTransaksi, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        {{-- Form Simulasi --}}
        <div class="col-span-12 xl:col-span-5">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="mb-5">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Parameter Simulasi</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Masukkan spesifikasi barang yang ingin disimulasikan</p>
                </div>

                <form id="simulationForm" class="space-y-5">
                    @csrf
                    {{-- Kategori --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori Barang <span class="text-red-500">*</span></label>
                        <select id="kategori" name="kategori" required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-theme-xs focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategoris as $kat)
                                <option value="{{ $kat }}">{{ ucwords(str_replace('_', ' ', $kat)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Kondisi --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Kondisi Barang <span class="text-red-500">*</span></label>
                        <select id="kondisi" name="kondisi" required
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-theme-xs focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <option value="">Pilih Kondisi</option>
                            <option value="baik">Baik</option>
                            <option value="cukup">Cukup</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                        </select>
                    </div>

                    {{-- Merk --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Merk</label>
                        <select id="merk" name="merk"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-theme-xs focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <option value="">Semua Merk</option>
                        </select>
                    </div>

                    {{-- Tipe / Model --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-300">Tipe / Model</label>
                        <select id="tipe_model" name="tipe_model"
                            class="h-11 w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-theme-xs focus:border-brand-300 focus:outline-none focus:ring-2 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <option value="">Semua Tipe</option>
                        </select>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" id="btnSimulasi"
                        class="inline-flex h-11 w-full items-center justify-center gap-2 rounded-lg bg-brand-500 px-5 py-2.5 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg id="btnIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.3-4.3"></path>
                        </svg>
                        <svg id="btnSpinner" class="hidden animate-spin" width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span id="btnText">Jalankan Simulasi</span>
                    </button>
                </form>

                {{-- Keterangan Metode --}}
                <div class="mt-5 rounded-lg border border-gray-100 bg-gray-50/50 p-3.5 dark:border-gray-800 dark:bg-gray-800/50">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Metode Estimasi</p>
                    <ul class="space-y-1 text-xs text-gray-400 dark:text-gray-500">
                        <li>• Data prioritas: 12 bulan terakhir</li>
                        <li>• Rentang estimasi: Percentile 60 – 75</li>
                        <li>• Pinjaman: 70% dari rentang taksiran</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Hasil Simulasi --}}
        <div class="col-span-12 xl:col-span-7">
            {{-- Placeholder awal --}}
            <div id="resultPlaceholder" class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-10 text-center dark:border-gray-700 dark:bg-gray-800/50">
                <svg class="mx-auto mb-4 h-16 w-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-400">Hasil Simulasi</h3>
                <p class="mt-2 text-sm text-gray-400 dark:text-gray-500">Pilih parameter dan klik "Jalankan Simulasi" untuk melihat estimasi harga gadai.</p>
            </div>

            {{-- Hasil --}}
            <div id="resultContainer" class="hidden space-y-5">
                {{-- Confidence + Data Info --}}
                <div id="confidenceBanner" class="rounded-2xl border p-4"></div>

                {{-- Rentang Estimasi Taksiran --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">Rentang Estimasi Taksiran</h4>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex-1 text-center rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 dark:border-blue-500/20 dark:bg-blue-500/10">
                            <p class="text-xs text-blue-500 dark:text-blue-400 mb-1">Batas Bawah (P60)</p>
                            <p id="taksiranRentangBawah" class="text-lg font-bold text-blue-700 dark:text-blue-300">-</p>
                        </div>
                        <span class="text-gray-400 font-medium">—</span>
                        <div class="flex-1 text-center rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 dark:border-blue-500/20 dark:bg-blue-500/10">
                            <p class="text-xs text-blue-500 dark:text-blue-400 mb-1">Batas Atas (P75)</p>
                            <p id="taksiranRentangAtas" class="text-lg font-bold text-blue-700 dark:text-blue-300">-</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        <div><p class="text-xs text-gray-400">Min</p><p id="taksiranMin" class="text-sm font-semibold text-gray-600 dark:text-gray-400">-</p></div>
                        <div><p class="text-xs text-gray-400">Median</p><p id="taksiranMedian" class="text-sm font-semibold text-gray-600 dark:text-gray-400">-</p></div>
                        <div><p class="text-xs text-gray-400">Rata-rata</p><p id="taksiranAvg" class="text-sm font-semibold text-gray-600 dark:text-gray-400">-</p></div>
                        <div><p class="text-xs text-gray-400">Max</p><p id="taksiranMax" class="text-sm font-semibold text-gray-600 dark:text-gray-400">-</p></div>
                    </div>
                </div>

                {{-- Rentang Estimasi Pinjaman --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03]">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-4">Rentang Rekomendasi Pinjaman <span class="text-xs text-gray-400">(70% Taksiran)</span></h4>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex-1 text-center rounded-xl border border-green-200 bg-green-50 px-4 py-3 dark:border-green-500/20 dark:bg-green-500/10">
                            <p class="text-xs text-green-500 dark:text-green-400 mb-1">Batas Bawah</p>
                            <p id="pinjamanRekBawah" class="text-lg font-bold text-green-700 dark:text-green-300">-</p>
                        </div>
                        <span class="text-gray-400 font-medium">—</span>
                        <div class="flex-1 text-center rounded-xl border border-green-200 bg-green-50 px-4 py-3 dark:border-green-500/20 dark:bg-green-500/10">
                            <p class="text-xs text-green-500 dark:text-green-400 mb-1">Batas Atas</p>
                            <p id="pinjamanRekAtas" class="text-lg font-bold text-green-700 dark:text-green-300">-</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-2">
                        <div><p class="text-xs text-gray-400">Min</p><p id="pinjamanMin" class="text-sm font-semibold text-gray-600 dark:text-gray-400">-</p></div>
                        <div><p class="text-xs text-gray-400">Median</p><p id="pinjamanMedian" class="text-sm font-semibold text-gray-600 dark:text-gray-400">-</p></div>
                        <div><p class="text-xs text-gray-400">Rata-rata</p><p id="pinjamanAvg" class="text-sm font-semibold text-gray-600 dark:text-gray-400">-</p></div>
                        <div><p class="text-xs text-gray-400">Max</p><p id="pinjamanMax" class="text-sm font-semibold text-gray-600 dark:text-gray-400">-</p></div>
                    </div>
                </div>

                {{-- Tabel Referensi --}}
                <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="px-5 pt-5 pb-3">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-white/90">Data Transaksi Referensi</h4>
                        <p class="text-xs text-gray-400 mt-0.5">Transaksi serupa terbaru (maks 10 data)</p>
                    </div>
                    <div class="custom-scrollbar max-w-full overflow-x-auto">
                        <table class="w-full min-w-[700px]">
                            <thead class="border-y border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900">
                                <tr>
                                    <th class="px-4 py-2.5 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">No SBG</th>
                                    <th class="px-4 py-2.5 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Barang</th>
                                    <th class="px-4 py-2.5 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Kondisi</th>
                                    <th class="px-4 py-2.5 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Taksiran</th>
                                    <th class="px-4 py-2.5 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Pinjaman</th>
                                    <th class="px-4 py-2.5 text-left text-theme-xs font-medium text-gray-500 dark:text-gray-400">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody id="referenceTableBody" class="divide-y divide-gray-100 dark:divide-gray-800"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Error --}}
            <div id="resultError" class="hidden rounded-2xl border border-red-200 bg-red-50 p-6 text-center dark:border-red-500/30 dark:bg-red-500/10">
                <svg class="mx-auto mb-3 h-10 w-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <p id="errorMessage" class="text-sm font-medium text-red-600 dark:text-red-400"></p>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const fmt = (n) => 'Rp ' + Number(n || 0).toLocaleString('id-ID');

        // Dynamic dropdown: kategori -> merk
        document.getElementById('kategori').addEventListener('change', function() {
            const merk = document.getElementById('merk');
            const tipe = document.getElementById('tipe_model');
            merk.innerHTML = '<option value="">Semua Merk</option>';
            tipe.innerHTML = '<option value="">Semua Tipe</option>';
            if (!this.value) return;
            fetch(`/superadmin/simulasi/merks?kategori=${encodeURIComponent(this.value)}`)
                .then(r => r.json())
                .then(data => data.forEach(m => { const o = document.createElement('option'); o.value = m; o.textContent = m; merk.appendChild(o); }));
        });

        // Dynamic dropdown: merk -> tipe_model
        document.getElementById('merk').addEventListener('change', function() {
            const tipe = document.getElementById('tipe_model');
            tipe.innerHTML = '<option value="">Semua Tipe</option>';
            const kat = document.getElementById('kategori').value;
            if (!kat) return;
            fetch(`/superadmin/simulasi/tipe-models?kategori=${encodeURIComponent(kat)}&merk=${encodeURIComponent(this.value)}`)
                .then(r => r.json())
                .then(data => data.forEach(t => { const o = document.createElement('option'); o.value = t; o.textContent = t; tipe.appendChild(o); }));
        });

        // Submit simulasi
        document.getElementById('simulationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSimulasi');
            const spinner = document.getElementById('btnSpinner');
            const icon = document.getElementById('btnIcon');
            const text = document.getElementById('btnText');

            btn.disabled = true; icon.classList.add('hidden'); spinner.classList.remove('hidden'); text.textContent = 'Memproses...';

            const formData = new FormData(this);
            fetch('/superadmin/simulasi/estimate', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': formData.get('_token'), 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ kategori: formData.get('kategori'), kondisi: formData.get('kondisi'), merk: formData.get('merk'), tipe_model: formData.get('tipe_model') })
            })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false; icon.classList.remove('hidden'); spinner.classList.add('hidden'); text.textContent = 'Jalankan Simulasi';
                if (!data.success) { showError(data.message || 'Terjadi kesalahan.'); return; }

                document.getElementById('resultPlaceholder').classList.add('hidden');
                document.getElementById('resultError').classList.add('hidden');
                document.getElementById('resultContainer').classList.remove('hidden');

                // Confidence banner
                const cb = document.getElementById('confidenceBanner');
                const colors = { green: 'border-green-200 bg-green-50 dark:border-green-500/30 dark:bg-green-500/10', yellow: 'border-yellow-200 bg-yellow-50 dark:border-yellow-500/30 dark:bg-yellow-500/10', orange: 'border-orange-200 bg-orange-50 dark:border-orange-500/30 dark:bg-orange-500/10', red: 'border-red-200 bg-red-50 dark:border-red-500/30 dark:bg-red-500/10' };
                const tc = { green: 'text-green-700 dark:text-green-400', yellow: 'text-yellow-700 dark:text-yellow-400', orange: 'text-orange-700 dark:text-orange-400', red: 'text-red-700 dark:text-red-400' };
                cb.className = `rounded-2xl border p-4 ${colors[data.confidence.color] || colors.green}`;
                const sumber = data.data_info ? ` • Sumber: ${data.data_info.sumber} (${data.data_info.total_12_bulan} dari ${data.data_info.total_semua} total)` : '';
                cb.innerHTML = `<div class="flex items-center justify-between flex-wrap gap-2"><div><p class="text-sm font-semibold ${tc[data.confidence.color]}">Tingkat Kepercayaan: ${data.confidence.label}</p><p class="text-xs ${tc[data.confidence.color]} opacity-75 mt-0.5">Berdasarkan ${data.total_data} transaksi serupa${sumber}</p></div><div class="text-right"><span class="text-2xl font-bold ${tc[data.confidence.color]}">${data.confidence.percentage}%</span></div></div>`;

                // Taksiran rentang
                document.getElementById('taksiranRentangBawah').textContent = fmt(data.taksiran.rentang_bawah);
                document.getElementById('taksiranRentangAtas').textContent = fmt(data.taksiran.rentang_atas);
                document.getElementById('taksiranMin').textContent = fmt(data.taksiran.minimum);
                document.getElementById('taksiranMedian').textContent = fmt(data.taksiran.median);
                document.getElementById('taksiranAvg').textContent = fmt(data.taksiran.rata_rata);
                document.getElementById('taksiranMax').textContent = fmt(data.taksiran.maksimum);

                // Pinjaman rentang
                document.getElementById('pinjamanRekBawah').textContent = fmt(data.pinjaman.rekomendasi_bawah);
                document.getElementById('pinjamanRekAtas').textContent = fmt(data.pinjaman.rekomendasi_atas);
                document.getElementById('pinjamanMin').textContent = fmt(data.pinjaman.minimum);
                document.getElementById('pinjamanMedian').textContent = fmt(data.pinjaman.median);
                document.getElementById('pinjamanAvg').textContent = fmt(data.pinjaman.rata_rata);
                document.getElementById('pinjamanMax').textContent = fmt(data.pinjaman.maksimum);

                // Tabel referensi
                const tbody = document.getElementById('referenceTableBody');
                tbody.innerHTML = '';
                if (data.referensi.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-8 text-center text-sm text-gray-400">Tidak ada data referensi.</td></tr>';
                } else {
                    data.referensi.forEach(r => {
                        const kl = { baik: 'Baik', cukup: 'Cukup', rusak_ringan: 'Rusak Ringan' };
                        const kb = { baik: 'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-400', cukup: 'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-400', rusak_ringan: 'bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-400' };
                        tbody.innerHTML += `<tr class="transition-colors hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-4 py-3 text-theme-sm font-medium text-gray-800 dark:text-white/90">${r.no_sbg || '-'}</td>
                            <td class="px-4 py-3"><p class="text-theme-sm text-gray-800 dark:text-white/90">${r.barang}</p><p class="text-xs text-gray-400">${r.merk} ${r.tipe_model}</p></td>
                            <td class="px-4 py-3"><span class="rounded-full px-2 py-0.5 text-theme-xs font-medium ${kb[r.kondisi] || ''}">${kl[r.kondisi] || r.kondisi}</span></td>
                            <td class="px-4 py-3 text-theme-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">${fmt(r.taksiran_akhir)}</td>
                            <td class="px-4 py-3 text-theme-sm font-medium text-green-600 dark:text-green-400 whitespace-nowrap">${fmt(r.nilai_pinjaman)}</td>
                            <td class="px-4 py-3 text-theme-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">${r.tgl_gadai}</td>
                        </tr>`;
                    });
                }
            })
            .catch(() => {
                btn.disabled = false; icon.classList.remove('hidden'); spinner.classList.add('hidden'); text.textContent = 'Jalankan Simulasi';
                showError('Gagal menghubungi server. Silakan coba lagi.');
            });
        });

        function showError(msg) {
            document.getElementById('resultPlaceholder').classList.add('hidden');
            document.getElementById('resultContainer').classList.add('hidden');
            document.getElementById('resultError').classList.remove('hidden');
            document.getElementById('errorMessage').textContent = msg;
        }
    </script>
    @endpush
@endsection

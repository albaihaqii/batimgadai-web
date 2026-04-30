@extends('layouts.app')
@section('content')

<x-common.page-breadcrumb pageTitle="Gadai Baru" />

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

    <div class="lg:col-span-2 space-y-6">

        {{-- Card 1: Pilih Nasabah --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Data Nasabah</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Cari dan pilih nasabah yang akan menggadaikan barang.</p>
            </div>
            <div class="p-6">
                <form method="GET" action="{{ route(auth()->user()->role . '.transaksi.gadai.create') }}">
                    <div class="relative">
                        <select name="nasabah_id" onchange="this.form.submit()"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            <option value="">Pilih Nasabah...</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" {{ $selectedCustomer && $selectedCustomer->id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->nama }} — {{ $customer->no_cif }}
                                </option>
                            @endforeach
                        </select>
                        <span class="absolute top-1/2 right-4 -translate-y-1/2 pointer-events-none text-gray-500">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </span>
                    </div>
                </form>

                @if($selectedCustomer)
                <div class="mt-5 p-4 rounded-xl bg-brand-50 border border-brand-100 dark:bg-brand-500/10 dark:border-brand-500/20">
                    <div class="grid grid-cols-2 gap-4">
                        <div><p class="text-xs text-gray-500 dark:text-gray-400">Nama</p><p class="text-sm font-semibold text-gray-800 dark:text-white mt-0.5">{{ $selectedCustomer->nama }}</p></div>
                        <div><p class="text-xs text-gray-500 dark:text-gray-400">No CIF</p><p class="text-sm font-semibold text-gray-800 dark:text-white mt-0.5">{{ $selectedCustomer->no_cif }}</p></div>
                        <div><p class="text-xs text-gray-500 dark:text-gray-400">No KTP</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-0.5">{{ $selectedCustomer->no_ktp }}</p></div>
                        <div><p class="text-xs text-gray-500 dark:text-gray-400">No HP</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-0.5">{{ $selectedCustomer->no_hp }}</p></div>
                        <div><p class="text-xs text-gray-500 dark:text-gray-400">Cabang</p><p class="text-sm font-medium text-gray-800 dark:text-white mt-0.5">{{ $selectedCustomer->branch->nama ?? '-' }}</p></div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Status</p>
                            <span class="inline-block mt-0.5 rounded-full px-2 py-0.5 text-theme-xs font-medium {{ $selectedCustomer->status === 'aktif' ? 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500' : 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500' }}">
                                {{ ucfirst($selectedCustomer->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if($selectedCustomer)
        <form method="POST" action="{{ route(auth()->user()->role . '.transaksi.gadai.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="nasabah_id" value="{{ $selectedCustomer->id }}">

        {{-- Card 2: Data Barang --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Data Barang Jaminan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Isi seluruh detail barang yang akan dijadikan jaminan gadai.</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Nama Barang --}}
                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Contoh: iPhone 13 Pro Max 256GB"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('nama_barang') ? 'border-red-500' : '' }}">
                        @error('nama_barang') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kategori <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="kategori" id="kategori"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 {{ $errors->has('kategori') ? 'border-red-500' : '' }}">
                                <option value="">Pilih Kategori</option>
                                <option value="handphone"          {{ old('kategori') == 'handphone' ? 'selected' : '' }}>Handphone</option>
                                <option value="laptop"             {{ old('kategori') == 'laptop' ? 'selected' : '' }}>Laptop</option>
                                <option value="tablet"             {{ old('kategori') == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="elektronik_lainnya" {{ old('kategori') == 'elektronik_lainnya' ? 'selected' : '' }}>Elektronik Lainnya</option>
                                <option value="kendaraan_motor"    {{ old('kategori') == 'kendaraan_motor' ? 'selected' : '' }}>Kendaraan Motor (Unit + BPKB)</option>
                                <option value="barang_rumah_tangga"{{ old('kategori') == 'barang_rumah_tangga' ? 'selected' : '' }}>Barang Rumah Tangga</option>
                                <option value="perhiasan"          {{ old('kategori') == 'perhiasan' ? 'selected' : '' }}>Perhiasan / Emas</option>
                            </select>
                            <span class="absolute top-1/2 right-4 -translate-y-1/2 pointer-events-none text-gray-500"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        </div>
                        @error('kategori') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kondisi --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kondisi Barang <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select name="kondisi"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 {{ $errors->has('kondisi') ? 'border-red-500' : '' }}">
                                <option value="">Pilih Kondisi</option>
                                <option value="baik"        {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="cukup"       {{ old('kondisi') == 'cukup' ? 'selected' : '' }}>Cukup</option>
                                <option value="rusak_ringan"{{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                            </select>
                            <span class="absolute top-1/2 right-4 -translate-y-1/2 pointer-events-none text-gray-500"><svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                        </div>
                        @error('kondisi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Merk --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Merk <span class="text-red-500">*</span></label>
                        <input type="text" name="merk" value="{{ old('merk') }}" placeholder="Contoh: Apple, Samsung, Honda"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('merk') ? 'border-red-500' : '' }}">
                        @error('merk') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tipe/Model --}}
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tipe / Model <span class="text-red-500">*</span></label>
                        <input type="text" name="tipe_model" value="{{ old('tipe_model') }}" placeholder="Contoh: iPhone 13 Pro, Vario 125"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('tipe_model') ? 'border-red-500' : '' }}">
                        @error('tipe_model') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kelengkapan --}}
                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kelengkapan <span class="text-red-500">*</span></label>
                        <textarea name="kelengkapan" rows="3" placeholder="Contoh: Charger original, dus/kotak, earphone, pelindung layar, manual book"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('kelengkapan') ? 'border-red-500' : '' }}">{{ old('kelengkapan') }}</textarea>
                        @error('kelengkapan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Foto Barang — UI Tombol Tambah Foto --}}
                    <div class="md:col-span-2">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Foto Barang <span class="text-red-500">*</span>
                            <span class="text-gray-400 font-normal ml-1">— maks 5 foto, tiap foto maks 2MB</span>
                        </label>

                        {{-- Input file hidden --}}
                        <input type="file" name="foto_barang[]" id="foto_barang_input" multiple
                            accept="image/jpg,image/jpeg,image/png" class="hidden"
                            onchange="handleFotoChange(this)">

                        {{-- Preview grid --}}
                        <div id="foto-grid" class="grid grid-cols-5 gap-3 mb-3" style="display:none"></div>

                        {{-- Tombol tambah --}}
                        <div id="foto-add-btn-wrap">
                            <button type="button" onclick="document.getElementById('foto_barang_input').click()"
                                class="inline-flex items-center gap-2 rounded-lg border border-dashed border-gray-300 bg-gray-50 px-5 py-3 text-sm font-medium text-gray-600 hover:border-brand-400 hover:bg-brand-50 hover:text-brand-600 transition-colors dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:border-brand-500">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                </svg>
                                Pilih Foto Barang
                            </button>
                            <p class="text-xs text-gray-400 mt-1.5">JPG, JPEG, PNG. Disarankan: foto depan, belakang, kiri, kanan, detail kondisi.</p>
                        </div>

                        @error('foto_barang') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        @error('foto_barang.*') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- Card 3: Nilai Taksiran --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Nilai Taksiran Awal</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Masukkan estimasi range nilai taksiran barang oleh petugas. Nilai final ditentukan admin saat approval.</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Taksiran Minimum <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 pointer-events-none font-medium">Rp</span>
                            <input type="number" name="nilai_taksiran_min" id="nilai_taksiran_min" value="{{ old('nilai_taksiran_min') }}" placeholder="0" min="1"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('nilai_taksiran_min') ? 'border-red-500' : '' }}">
                        </div>
                        @error('nilai_taksiran_min') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Taksiran Maksimum <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute top-1/2 left-4 -translate-y-1/2 text-sm text-gray-500 dark:text-gray-400 pointer-events-none font-medium">Rp</span>
                            <input type="number" name="nilai_taksiran_max" id="nilai_taksiran_max" value="{{ old('nilai_taksiran_max') }}" placeholder="0" min="1"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent pl-10 pr-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 {{ $errors->has('nilai_taksiran_max') ? 'border-red-500' : '' }}">
                        </div>
                        @error('nilai_taksiran_max') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">Nilai final ditentukan admin saat approval.</p>

                {{-- Preview Rate Jasa --}}
                <div id="preview-jasa" class="hidden mt-5 p-4 rounded-xl bg-brand-50 border border-brand-100 dark:bg-brand-500/10 dark:border-brand-500/20">
                    <div class="flex items-center gap-2 mb-3">
                        <div id="pv-loading" class="hidden">
                            <svg class="animate-spin text-brand-500" width="14" height="14" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-brand-600 dark:text-brand-400">
                            Estimasi Biaya Jasa <span class="font-normal text-gray-500">(berdasarkan nilai taksiran maksimum)</span>
                        </p>
                    </div>
                    <div id="pv-content" class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Jasa 1/2 Bulan (<span id="pv-persen-15">-</span>%)</p>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white" id="pv-nominal-15">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Jasa 1 Bulan (<span id="pv-persen-30">-</span>%)</p>
                            <p class="text-sm font-semibold text-gray-800 dark:text-white" id="pv-nominal-30">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Est. Total Tebus (1/2 bln)</p>
                            <p class="text-sm font-semibold text-brand-500" id="pv-tebus-15">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Est. Total Tebus (1 bln)</p>
                            <p class="text-sm font-semibold text-brand-500" id="pv-tebus-30">-</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">*Nilai final ditentukan admin saat approval</p>
                </div>
            </div>

            <div class="flex items-center gap-3 px-6 py-5 border-t border-gray-200 dark:border-gray-800">
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                    Ajukan Gadai
                </button>
                <a href="{{ route(auth()->user()->role . '.transaksi.gadai') }}"
                    class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    Batal
                </a>
            </div>
        </div>

        </form>
        @endif

    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Alur Pengajuan Gadai</h4>
            <div class="space-y-4">
                @foreach([
                    ['num'=>'1','text'=>'Pilih nasabah yang akan menggadaikan barang'],
                    ['num'=>'2','text'=>'Isi data barang jaminan secara lengkap'],
                    ['num'=>'3','text'=>'Upload foto barang (bisa lebih dari 1, maks 5)'],
                    ['num'=>'4','text'=>'Masukkan nilai taksiran awal (range)'],
                    ['num'=>'5','text'=>'Pengajuan masuk antrian approval admin'],
                    ['num'=>'6','text'=>'Admin setujui → assign loker → SBG diterbitkan'],
                ] as $step)
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-6 h-6 rounded-full bg-brand-50 dark:bg-brand-500/10 flex items-center justify-center mt-0.5">
                        <span class="text-xs font-bold text-brand-500">{{ $step['num'] }}</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $step['text'] }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Informasi Biaya</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Jasa gadai</span>
                    <span class="text-sm font-semibold text-gray-800 dark:text-white">Berdasarkan nilai pinjaman</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Masa gadai</span>
                    <span class="text-sm font-semibold text-gray-800 dark:text-white">30 hari</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-800">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Denda 1–15 hari</span>
                    <span class="text-sm font-semibold text-warning-600 dark:text-orange-400">Jasa 1/2 bulan</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Denda 16–30 hari</span>
                    <span class="text-sm font-semibold text-error-600 dark:text-error-500">Jasa 1 bulan</span>
                </div>
            </div>
            <a href="{{ route('superadmin.jasa-rate') }}" target="_blank"
                class="mt-4 inline-flex items-center gap-1.5 text-xs text-brand-500 hover:text-brand-600 transition-colors">
                Lihat tabel lengkap perhitungan jasa
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
            </a>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Kategori Barang Diterima</h4>
            <div class="space-y-2">
                @foreach(['Handphone','Laptop','Tablet','Elektronik Lainnya','Kendaraan Motor (Unit + BPKB)','Barang Rumah Tangga','Perhiasan / Emas'] as $kat)
                <div class="flex items-center gap-2">
                    <svg class="text-success-500 flex-shrink-0" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ $kat }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
// ── Foto Manager ──────────────────────────────────────────────
let selectedFiles = [];

function handleFotoChange(input) {
    const newFiles = Array.from(input.files);
    // Tambahkan file baru ke array, maks 5
    newFiles.forEach(f => {
        if (selectedFiles.length < 5) selectedFiles.push(f);
    });
    // Reset input agar bisa pilih file yang sama lagi
    input.value = '';
    renderFotoGrid();
    updateHiddenInput();
    updatePreviewJasa();
}

function removeFoto(idx) {
    selectedFiles.splice(idx, 1);
    renderFotoGrid();
    updateHiddenInput();
}

function renderFotoGrid() {
    const grid    = document.getElementById('foto-grid');
    const addWrap = document.getElementById('foto-add-btn-wrap');
    grid.innerHTML = '';

    if (selectedFiles.length === 0) {
        grid.style.display = 'none';
        addWrap.style.display = 'block';
        return;
    }

    grid.style.display = 'grid';

    selectedFiles.forEach((file, idx) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const wrap = document.createElement('div');
            wrap.className = 'relative group';
            wrap.innerHTML = `
                <img src="${e.target.result}" alt="Foto ${idx+1}"
                    class="w-full aspect-square object-cover rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity rounded-xl flex items-center justify-center">
                    <button type="button" onclick="removeFoto(${idx})"
                        class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600 transition-colors">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <span class="absolute top-1 left-1 w-5 h-5 rounded-full bg-brand-500 text-white text-xs flex items-center justify-center font-bold">${idx+1}</span>
            `;
            grid.appendChild(wrap);
        };
        reader.readAsDataURL(file);
    });

    // Tombol tambah di dalam grid jika masih < 5
    if (selectedFiles.length < 5) {
        const addCell = document.createElement('div');
        addCell.className = 'flex items-center justify-center aspect-square rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-700 cursor-pointer hover:border-brand-400 hover:bg-brand-50 dark:hover:border-brand-500 transition-colors';
        addCell.innerHTML = `
            <button type="button" onclick="document.getElementById('foto_barang_input').click()"
                class="flex flex-col items-center gap-1 text-gray-400 hover:text-brand-500 transition-colors">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                <span class="text-xs">Tambah</span>
            </button>
        `;
        grid.appendChild(addCell);
        addWrap.style.display = 'none';
    } else {
        addWrap.style.display = 'none';
    }
}

function updateHiddenInput() {
    // Buat DataTransfer baru dan assign ke input
    const dt    = new DataTransfer();
    const input = document.getElementById('foto_barang_input');
    selectedFiles.forEach(f => dt.items.add(f));
    input.files = dt.files;
}

// ── Preview Jasa Rate ──────────────────────────────────────────
let jasaTimeout;

function updatePreviewJasa() {
    clearTimeout(jasaTimeout);
    const nilaiMax = parseInt(document.getElementById('nilai_taksiran_max')?.value || 0);
    const kategori = document.getElementById('kategori')?.value || 'handphone';
    const tipe     = kategori === 'perhiasan' ? 'perhiasan' : 'umum';
    const box      = document.getElementById('preview-jasa');
    const loading  = document.getElementById('pv-loading');

    if (nilaiMax < 1) {
        box.classList.add('hidden');
        return;
    }

    box.classList.remove('hidden');
    loading.classList.remove('hidden');

    jasaTimeout = setTimeout(() => {
        fetch('/api/preview-jasa-rate?nilai=' + nilaiMax + '&tipe=' + tipe)
            .then(r => r.json())
            .then(data => {
                loading.classList.add('hidden');
                document.getElementById('pv-persen-15').textContent  = data.jasa_15_hari;
                document.getElementById('pv-persen-30').textContent  = data.jasa_30_hari;
                document.getElementById('pv-nominal-15').textContent = 'Rp ' + fmt(data.jasa_nominal_15);
                document.getElementById('pv-nominal-30').textContent = 'Rp ' + fmt(data.jasa_nominal_30);
                document.getElementById('pv-tebus-15').textContent   = 'Rp ' + fmt(data.total_tebus_15);
                document.getElementById('pv-tebus-30').textContent   = 'Rp ' + fmt(data.total_tebus_30);
            })
            .catch(() => {
                loading.classList.add('hidden');
                box.classList.add('hidden');
            });
    }, 400);
}

function fmt(n) {
    return parseInt(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

document.getElementById('nilai_taksiran_max')?.addEventListener('input', updatePreviewJasa);
document.getElementById('kategori')?.addEventListener('change', updatePreviewJasa);
</script>
@endpush

@endsection
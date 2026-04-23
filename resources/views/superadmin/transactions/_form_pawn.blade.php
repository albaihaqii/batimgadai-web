<div class="grid grid-cols-1 gap-5 md:grid-cols-2">
    {{-- No SBG --}}
    @if (isset($transaction))
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                No SBG
            </label>
            <input type="text" name="no_sbg" value="{{ old('no_sbg', optional($transaction)->no_sbg) }}"
                placeholder="No SBG akan terbit setelah approval"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                readonly />
        </div>
    @endif

    {{-- Customer --}}
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Customer <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <select name="customer_id"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                <option value="">Pilih nasabah</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}"
                        {{ old('customer_id', optional($transaction)->customer_id) == $customer->id ? 'selected' : '' }}>
                        {{ $customer->nama }} ({{ $customer->no_cif }})</option>
                @endforeach
            </select>
            <span class="absolute top-1/2 right-4 -translate-y-1/2 pointer-events-none text-gray-500">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </div>
    </div>

    {{-- Nama Barang --}}
    <div class="md:col-span-2">
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Nama Barang <span class="text-red-500">*</span>
        </label>
        <input type="text" name="item_name" value="{{ old('item_name', $transaction->item_name ?? '') }}"
            placeholder="Masukkan nama barang"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
            required />
    </div>

    {{-- Deskripsi Barang --}}
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Deskripsi Barang
        </label>
        <textarea name="item_description" rows="3" placeholder="Masukkan deskripsi barang"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">{{ old('item_description', $transaction->item_description ?? '') }}</textarea>
    </div>

    {{-- Kategori --}}
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Kategori
        </label>
        <input type="text" name="item_category" value="{{ old('item_category', $transaction->item_category ?? '') }}"
            placeholder="Masukkan kategori barang"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
    </div>

    {{-- Kondisi --}}
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Kondisi
        </label>
        <input type="text" name="item_condition"
            value="{{ old('item_condition', $transaction->item_condition ?? '') }}"
            placeholder="Masukkan kondisi barang"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
    </div>

    {{-- Kelengkapan --}}
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Kelengkapan
        </label>
        <input type="text" name="item_completeness"
            value="{{ old('item_completeness', $transaction->item_completeness ?? '') }}"
            placeholder="Masukkan kelengkapan barang"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30" />
    </div>

    {{-- Foto Barang --}}
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Foto Barang
        </label>
        <input type="file" name="item_photos[]" multiple accept="image/jpg,image/jpeg,image/png"
            class="focus:border-ring-brand-300 shadow-theme-xs h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400" />
        <p class="text-xs text-gray-400 mt-1.5">JPG, JPEG, PNG. Maks 2MB. (Opsional)</p>
        @if (!empty(optional($transaction)->item_photos))
            <div class="grid grid-cols-3 gap-2 mt-2">
                @foreach (optional($transaction)->item_photos as $photo)
                    <img src="{{ asset('storage/' . $photo) }}" class="h-24 object-cover rounded" />
                @endforeach
            </div>
        @endif
    </div>

    {{-- Cabang --}}
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Cabang <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <select name="branch_id" required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                <option value="">Pilih Cabang</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}"
                        {{ old('branch_id', optional($transaction)->branch_id) == $branch->id ? 'selected' : '' }}>
                        {{ $branch->nama }}</option>
                @endforeach
            </select>
            <span class="absolute top-1/2 right-4 -translate-y-1/2 pointer-events-none text-gray-500">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </div>
    </div>

    {{-- Petugas --}}
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Petugas <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <select name="officer_id" required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                <option value="">Pilih Petugas</option>
                @foreach ($officers as $officer)
                    <option value="{{ $officer->id }}"
                        {{ old('officer_id', optional($transaction)->officer_id) == $officer->id ? 'selected' : '' }}>
                        {{ $officer->nama }}</option>
                @endforeach
            </select>
            <span class="absolute top-1/2 right-4 -translate-y-1/2 pointer-events-none text-gray-500">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </div>
    </div>

    {{-- Range Taksiran Min --}}
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Range Taksiran Min <span class="text-red-500">*</span>
        </label>
        <input type="number" step="0.01" name="officer_appraisal_min"
            value="{{ old('officer_appraisal_min', optional($transaction)->officer_appraisal_min) }}"
            placeholder="0.00"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
            required />
    </div>

    {{-- Range Taksiran Max --}}
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Range Taksiran Max <span class="text-red-500">*</span>
        </label>
        <input type="number" step="0.01" name="officer_appraisal_max"
            value="{{ old('officer_appraisal_max', optional($transaction)->officer_appraisal_max) }}"
            placeholder="0.00"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
            required />
    </div>

    {{-- Nominal Pinjaman Nasabah --}}
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Nominal Pinjaman Nasabah <span class="text-red-500">*</span>
        </label>
        <input type="number" step="0.01" name="loan_amount"
            value="{{ old('loan_amount', optional($transaction)->loan_amount) }}" placeholder="0.00"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
            required />
    </div>

    {{-- Status --}}
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Status <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <select name="status" required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full appearance-none rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 pr-10 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                {{ isset($transaction) ? '' : 'disabled' }}>
                @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'completed' => 'Completed'] as $key => $label)
                    <option value="{{ $key }}"
                        {{ old('status', optional($transaction)->status ?? 'pending') == $key ? 'selected' : '' }}>
                        {{ $label }}</option>
                @endforeach
            </select>
            <span class="absolute top-1/2 right-4 -translate-y-1/2 pointer-events-none text-gray-500">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M4.79175 7.396L10.0001 12.6043L15.2084 7.396" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </div>
        @if (!isset($transaction))
            <p class="text-xs text-gray-400 mt-1.5">Status akan otomatis diset ke "Pending" saat pengajuan</p>
        @endif
    </div>

    {{-- Tanggal Transaksi --}}
    <div>
        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
            Tanggal Transaksi <span class="text-red-500">*</span>
        </label>
        <input type="date" name="transaction_date"
            value="{{ old('transaction_date', optional(optional($transaction)->transaction_date)->format('Y-m-d') ?? now()->toDateString()) }}"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
            required />
    </div>
</div>

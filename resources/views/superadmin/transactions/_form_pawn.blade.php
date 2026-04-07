<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">No SBG</label>
        <input name="no_sbg" value="{{ old('no_sbg', optional($transaction)->no_sbg) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            {{ isset($transaction) ? 'readonly' : '' }} required />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer</label>
        <select name="customer_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih nasabah</option>
            @foreach ($customers as $customer)
                <option value="{{ $customer->id }}"
                    {{ old('customer_id', optional($transaction)->customer_id) == $customer->id ? 'selected' : '' }}>
                    {{ $customer->nama }} ({{ $customer->no_cif }})</option>
            @endforeach
        </select>
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Barang</label>
        <input name="item_name" value="{{ old('item_name', optional(optional($transaction)->item_data)['name']) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi Barang</label>
        <textarea name="item_description"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('item_description', optional(optional($transaction)->item_data)['description']) }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
        <input name="item_category"
            value="{{ old('item_category', optional(optional($transaction)->item_data)['category']) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kondisi</label>
        <input name="item_condition"
            value="{{ old('item_condition', optional(optional($transaction)->item_data)['condition']) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kelengkapan</label>
        <input name="item_completeness"
            value="{{ old('item_completeness', optional(optional($transaction)->item_data)['completeness']) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto Barang (max 2MB)</label>
        <input type="file" name="item_photos[]" multiple class="mt-1 block w-full" accept="image/*" />
        @if (!empty(optional($transaction)->item_photos))
            <div class="grid grid-cols-3 gap-2 mt-2">
                @foreach (optional($transaction)->item_photos as $photo)
                    <img src="{{ asset('storage/' . $photo) }}" class="h-24 object-cover rounded" />
                @endforeach
            </div>
        @endif
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cabang</label>
        <select name="branch_id" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih Cabang</option>
            @foreach ($branches as $branch)
                <option value="{{ $branch->id }}"
                    {{ old('branch_id', optional($transaction)->branch_id) == $branch->id ? 'selected' : '' }}>
                    {{ $branch->nama_cabang }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Petugas</label>
        <select name="officer_id" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">Pilih Petugas</option>
            @foreach ($officers as $officer)
                <option value="{{ $officer->id }}"
                    {{ old('officer_id', optional($transaction)->officer_id) == $officer->id ? 'selected' : '' }}>
                    {{ $officer->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Range Taksiran Min</label>
        <input type="number" step="0.01" name="officer_appraisal_min"
            value="{{ old('officer_appraisal_min', optional($transaction)->officer_appraisal_min) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Range Taksiran Max</label>
        <input type="number" step="0.01" name="officer_appraisal_max"
            value="{{ old('officer_appraisal_max', optional($transaction)->officer_appraisal_max) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nominal Pinjaman</label>
        <input type="number" step="0.01" name="loan_amount"
            value="{{ old('loan_amount', optional($transaction)->loan_amount) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Taksiran Final</label>
        <input type="number" step="0.01" name="final_appraisal"
            value="{{ old('final_appraisal', optional($transaction)->final_appraisal) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
        <select name="status" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'completed' => 'Completed'] as $key => $label)
                <option value="{{ $key }}"
                    {{ old('status', optional($transaction)->status ?? 'pending') == $key ? 'selected' : '' }}>
                    {{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Transaksi</label>
        <input type="date" name="transaction_date"
            value="{{ old('transaction_date', optional(optional($transaction)->transaction_date)->format('Y-m-d') ?? now()->toDateString()) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required />
    </div>
</div>

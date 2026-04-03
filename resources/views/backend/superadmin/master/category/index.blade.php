@extends('layouts.app')

@section('content')
    <div class="space-y-6" x-data="{
        isOpen: false,
        isEdit: false,
        formData: { id: '', nama: '', deskripsi: '', status: 'active' },
        openCreateModal() {
            this.isEdit = false;
            this.formData = { id: '', nama: '', deskripsi: '', status: 'active' };
            this.isOpen = true;
        },
        editCategory(id, nama, deskripsi, status) {
            this.isEdit = true;
            this.formData = { id, nama, deskripsi, status };
            this.isOpen = true;
        },
        closeModal() {
            this.isOpen = false;
        }
    }" @keydown.escape="closeModal()">
        <!-- Page Content with Blur Effect -->
        <div :class="isOpen ? 'blur-sm' : ''">

            <!-- Breadcrumb -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Master Category</h2>
                </div>
                <button @click="openCreateModal()"
                    class="rounded-lg bg-brand-500 px-6 py-2 text-white hover:bg-brand-600 transition-colors">
                    + Tambah Category
                </button>
            </div>

            <!-- Alert Messages -->
            @if ($message = Session::get('success'))
                <div class="rounded-lg bg-green-50 p-4 border border-green-200 dark:bg-green-900/20 dark:border-green-800">
                    <p class="text-green-800 dark:text-green-200">{{ $message }}</p>
                </div>
            @endif

            <!-- Table -->
            <div
                class="mt-6 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">No</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Nama</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Deskripsi
                            </th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Dibuat</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($categories as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $categories->firstItem() + $loop->index }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">
                                    {{ $category->nama }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ Str::limit($category->deskripsi, 50) }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span
                                        class="px-2 py-1 rounded text-xs font-medium {{ $category->status === 'active' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                        {{ ucfirst($category->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $category->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-sm flex gap-2">
                                    <button
                                        @click="editCategory('{{ $category->id_categories }}', '{{ $category->nama }}', '{{ $category->deskripsi }}', '{{ $category->status }}')"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                        Edit
                                    </button>
                                    <form action="{{ route('category.destroy', $category->id_categories) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Yakin ingin hapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    Belum ada data category
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($categories->hasPages())
                <div class="flex justify-center">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>

        <!-- Modal Create/Edit -->
        <div @keydown.escape="closeModal()">

            <!-- Modal Content -->
            <div x-show="isOpen" class="fixed inset-0 flex items-center justify-center z-50" x-transition>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">

                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                            <span x-text="isEdit ? 'Edit Category' : 'Tambah Category'"></span>
                        </h3>
                        <button @click="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Form -->
                    <form :action="isEdit ? '/master/category/' + formData.id : '{{ route('category.store') }}'"
                        method="POST" class="p-6 space-y-4">
                        @csrf
                        <template x-if="isEdit">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <!-- Nama Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nama Category <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" x-model="formData.nama"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:bg-gray-700 dark:text-white"
                                required>
                            @error('nama')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Deskripsi Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Deskripsi
                            </label>
                            <textarea name="deskripsi" x-model="formData.deskripsi" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:bg-gray-700 dark:text-white"></textarea>
                            @error('deskripsi')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Status Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Status
                            </label>
                            <select name="status" x-model="formData.status"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-500 dark:bg-gray-700 dark:text-white">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            @error('status')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Modal Footer -->
                        <div class="flex gap-3 pt-4">
                            <button type="button" @click="closeModal()"
                                class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-1 px-4 py-2 bg-brand-500 text-white rounded-lg hover:bg-brand-600 transition font-medium">
                                <span x-text="isEdit ? 'Simpan Perubahan' : 'Tambah'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

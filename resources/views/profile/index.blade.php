@extends('layouts.app')
@section('content')

    {{-- Breadcrumb --}}
    <x-common.page-breadcrumb pageTitle="Edit Profil" />

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-medium dark:bg-green-500/10 dark:border-green-500/20 dark:text-green-400">
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-800">
                <h3 class="text-base font-semibold text-gray-800 dark:text-white">Informasi Profil</h3>
            </div>

            <form method="POST" action="{{ route(auth()->user()->role . '.profile.update') }}" enctype="multipart/form-data" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                {{-- Foto Profil --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                        Foto Profil
                    </label>
                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-16 h-16 rounded-full bg-brand-500 flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if($user->foto)
                                <img src="{{ asset('storage/' . $user->foto) }}?v={{ time() }}" alt="{{ $user->nama }}" class="w-full h-full object-cover" id="foto-preview" />
                            @else
                                <span class="text-white font-bold text-xl" id="foto-initial">
                                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                                </span>
                                <img src="" alt="" class="w-full h-full object-cover hidden" id="foto-preview" />
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $user->nama }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ ucfirst($user->role) }}</p>
                        </div>
                    </div>
                    <input type="file" name="foto" id="foto" accept="image/jpg,image/jpeg,image/png"
                        class="focus:border-ring-brand-300 shadow-theme-xs h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700 placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400"
                        onchange="previewFoto(this)">
                    <p class="text-xs text-gray-400 mt-1.5">JPG, JPEG, PNG. Maks 2MB.</p>
                    @error('foto') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Nama --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', $user->nama) }}"
                        placeholder="Masukkan nama lengkap"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    @error('nama') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        placeholder="Masukkan email"
                        class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Role (read only) --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Role</label>
                    <input type="text" value="{{ ucfirst($user->role) }}" disabled
                        class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-400 cursor-not-allowed dark:border-gray-700 dark:bg-gray-800 dark:text-gray-500">
                </div>

                {{-- Cabang (read only) --}}
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Cabang</label>
                    <input type="text" value="{{ $user->cabang_id ? 'Cabang #' . $user->cabang_id : 'Semua Cabang' }}" disabled
                        class="dark:bg-dark-900 shadow-theme-xs h-11 w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-sm text-gray-400 cursor-not-allowed dark:border-gray-700 dark:bg-gray-800 dark:text-gray-500">
                </div>

                <button type="submit"
                    class="flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-6 py-2.5 text-sm font-semibold text-white shadow-theme-xs hover:bg-brand-600 transition-colors">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

@push('scripts')
<script>
function previewFoto(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('foto-preview');
            const initial = document.getElementById('foto-initial');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (initial) initial.classList.add('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
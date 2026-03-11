<header class="fixed w-full z-50 bg-white border-b border-gray-100 shadow-sm">
    <nav class="max-w-screen-xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                {{-- Ganti img src nanti --}}
                <img src="{{ asset('frontend/images/logo.png') }}" alt="BATIM GADAI" class="h-9 w-auto"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                <div class="w-9 h-9 rounded-lg bg-[#1F5C3A] items-center justify-center hidden">
                    <span class="text-white font-bold text-sm">BG</span>
                </div>
                <span class="font-bold text-xl text-gray-900 tracking-tight">BATIM GADAI</span>
            </a>

            {{-- Desktop Menu --}}
            <ul class="hidden lg:flex items-center gap-8">
                @foreach([
                    ['href'=>'#beranda','label'=>'Beranda'],
                    ['href'=>'#tentang','label'=>'Tentang Kami'],
                    ['href'=>'#layanan','label'=>'Layanan'],
                    ['href'=>'#cabang','label'=>'Cabang'],
                    ['href'=>'#syarat','label'=>'Syarat & Ketentuan'],
                    ['href'=>'#faq','label'=>'FAQ'],
                    ['href'=>'#kontak','label'=>'Kontak'],
                ] as $menu)
                <li>
                    <a href="{{ $menu['href'] }}"
                        class="text-sm font-medium text-gray-900 hover:text-[#B6D96C] transition-colors duration-200">
                        {{ $menu['label'] }}
                    </a>
                </li>
                @endforeach
            </ul>

            {{-- Login Button --}}
            <div class="hidden lg:block">
                <a href="{{ route('login') }}"
                    class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-[#B6D96C] text-[#1F5C3A] hover:bg-[#a8cc5a] transition-all duration-200">
                    Login
                </a>
            </div>

            {{-- Hamburger --}}
            <button id="mobile-menu-btn" class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden lg:hidden mt-4 pb-4 border-t border-gray-100">
            <ul class="flex flex-col gap-1 mt-4">
                @foreach([
                    ['href'=>'#beranda','label'=>'Beranda'],
                    ['href'=>'#tentang','label'=>'Tentang Kami'],
                    ['href'=>'#layanan','label'=>'Layanan'],
                    ['href'=>'#cabang','label'=>'Cabang'],
                    ['href'=>'#syarat','label'=>'Syarat & Ketentuan'],
                    ['href'=>'#faq','label'=>'FAQ'],
                    ['href'=>'#kontak','label'=>'Kontak'],
                ] as $menu)
                <li>
                    <a href="{{ $menu['href'] }}"
                        class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-900 hover:text-[#1F5C3A] hover:bg-[#B6D96C]/20 transition-all">
                        {{ $menu['label'] }}
                    </a>
                </li>
                @endforeach
                <li class="pt-2">
                    <a href="{{ route('login') }}"
                            class="block text-center px-5 py-2.5 rounded-lg text-sm font-semibold bg-[#B6D96C] text-[#1F5C3A]">
                        Login
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>

@push('scripts')
<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', function () {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>
@endpush
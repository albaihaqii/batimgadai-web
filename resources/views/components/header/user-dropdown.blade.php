<div class="relative" x-data="{
    dropdownOpen: false,
    toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
    },
    closeDropdown() {
        this.dropdownOpen = false;
    }
}" @click.away="closeDropdown()">

    <!-- User Button -->
    <button
        class="flex items-center text-gray-700 dark:text-gray-400"
        @click.prevent="toggleDropdown()"
        type="button"
    >
        <span class="mr-3 overflow-hidden rounded-full h-11 w-11 bg-brand-500 flex items-center justify-center flex-shrink-0">
            @if(auth()->user()->foto)
                <img src="{{ url('storage/' . auth()->user()->foto) }}?v={{ auth()->user()->updated_at?->timestamp ?? time() }}" alt="{{ auth()->user()->nama }}" class="w-full h-full object-cover" />
            @else
                <span class="text-white font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                </span>
            @endif
        </span>
        <span class="block mr-1 font-medium text-theme-sm">{{ auth()->user()->nama }}</span>
        <svg
            class="w-5 h-5 transition-transform duration-200"
            :class="{ 'rotate-180': dropdownOpen }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown -->
    <div
        x-show="dropdownOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-[17px] flex w-[260px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark z-50"
        style="display: none;"
    >
        <!-- User Info -->
        <div class="flex items-center gap-3 mb-3 pb-3 border-b border-gray-200 dark:border-gray-800">
            <span class="overflow-hidden rounded-full h-10 w-10 bg-brand-500 flex items-center justify-center flex-shrink-0">
                @if(auth()->user()->foto)
                    <img src="{{ url('storage/' . auth()->user()->foto) }}?v={{ auth()->user()->updated_at?->timestamp ?? time() }}" alt="{{ auth()->user()->nama }}" class="w-full h-full object-cover" />
                @else
                    <span class="text-white font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </span>
                @endif
            </span>
            <div>
                <span class="block font-semibold text-gray-800 text-theme-sm dark:text-white">{{ auth()->user()->nama }}</span>
                <span class="block text-theme-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold mt-1
                    @if(auth()->user()->role === 'superadmin') bg-purple-100 text-purple-700
                    @elseif(auth()->user()->role === 'admin') bg-blue-100 text-blue-700
                    @else bg-green-100 text-green-700 @endif">
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </div>

        <!-- Menu Items -->
        <ul class="flex flex-col gap-1 pb-3 border-b border-gray-200 dark:border-gray-800">
            <li>
                <a href="{{ route(auth()->user()->role . '.profile') }}"
                    class="flex items-center gap-3 px-3 py-2 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                    @click="closeDropdown()"
                >
                    <span class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25Z" fill="currentColor"/>
                        </svg>
                    </span>
                    Edit Profil
                </a>
            </li>
            <li>
                <a href="{{ route(auth()->user()->role . '.password') }}"
                    class="flex items-center gap-3 px-3 py-2 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                    @click="closeDropdown()"
                >
                    <span class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.25C9.37665 1.25 7.25 3.37665 7.25 6V7.25H6C4.48122 7.25 3.25 8.48122 3.25 10V20C3.25 21.5188 4.48122 22.75 6 22.75H18C19.5188 22.75 20.75 21.5188 20.75 20V10C20.75 8.48122 19.5188 7.25 18 7.25H16.75V6C16.75 3.37665 14.6234 1.25 12 1.25ZM15.25 7.25V6C15.25 4.20507 13.7949 2.75 12 2.75C10.2051 2.75 8.75 4.20507 8.75 6V7.25H15.25ZM6 8.75C5.30964 8.75 4.75 9.30964 4.75 10V20C4.75 20.6904 5.30964 21.25 6 21.25H18C18.6904 21.25 19.25 20.6904 19.25 20V10C19.25 9.30964 18.6904 8.75 18 8.75H6ZM12 12.25C12.4142 12.25 12.75 12.5858 12.75 13V17C12.75 17.4142 12.4142 17.75 12 17.75C11.5858 17.75 11.25 17.4142 11.25 17V13C11.25 12.5858 11.5858 12.25 12 12.25Z" fill="currentColor"/>
                        </svg>
                    </span>
                    Ganti Password
                </a>
            </li>
        </ul>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="flex items-center w-full gap-3 px-3 py-2 mt-3 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                @click="closeDropdown()"
            >
                <span class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </span>
                Logout
            </button>
        </form>
    </div>
</div>

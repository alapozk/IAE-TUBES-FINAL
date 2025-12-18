<nav x-data="navigationMenu()" class="sticky top-0 z-50 bg-white shadow-md border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <!-- Logo Section -->
            <div class="flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 group">
                    <x-application-logo class="h-8 w-8 fill-current text-blue-600 group-hover:text-blue-700 transition" />
                    <span class="hidden sm:block text-lg font-bold text-gray-800 group-hover:text-blue-600 transition">App</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-1">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" 
                    class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-100 transition">
                    {{ __('Dashboard') }}
                </x-nav-link>
            </div>

            <!-- Right Section -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <!-- User Dropdown -->
                    <div class="relative" @click.outside="dropdownOpen = false">
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="inline-flex items-center px-4 py-2 space-x-2 text-sm font-medium text-gray-700 bg-gray-50 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <span>{{ Auth::user()->name }}</span>
                            <svg :class="{'rotate-180': dropdownOpen}" class="h-4 w-4 transition-transform duration-200" 
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="dropdownOpen" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1">
                            
                            @if (Route::has('profile.edit'))
                                <a href="{{ route('profile.edit') }}" 
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition flex items-center space-x-2">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>{{ __('Profile') }}</span>
                                </a>
                            @endif

                            <hr class="my-1 border-gray-100">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition flex items-center space-x-2"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>{{ __('Log Out') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Auth Links -->
                    <div class="flex items-center space-x-3">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 transition">
                                {{ __('Log in') }}
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                                {{ __('Register') }}
                            </a>
                        @endif
                    </div>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <svg x-show="!mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="mobileMenuOpen" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                    class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-100 transition">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
            </div>

            @auth
                <div class="border-t border-gray-200 px-4 py-3">
                    <div class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500 mt-1">{{ Auth::user()->email }}</div>
                </div>

                <div class="px-2 py-2 space-y-1">
                    @if (Route::has('profile.edit'))
                        <x-responsive-nav-link :href="route('profile.edit')"
                            class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-100 transition">
                            {{ __('Profile') }}
                        </x-responsive-nav-link>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:bg-red-50 transition"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </div>
            @else
                <div class="border-t border-gray-200 px-2 py-2 space-y-1">
                    @if (Route::has('login'))
                        <x-responsive-nav-link :href="route('login')"
                            class="block px-3 py-2 rounded-md text-base font-medium hover:bg-gray-100 transition">
                            {{ __('Log in') }}
                        </x-responsive-nav-link>
                    @endif

                    @if (Route::has('register'))
                        <x-responsive-nav-link :href="route('register')"
                            class="block px-3 py-2 rounded-md text-base font-medium text-blue-600 hover:bg-blue-50 transition">
                            {{ __('Register') }}
                        </x-responsive-nav-link>
                    @endif
                </div>
            @endauth
        </div>
    </div>
</nav>

<script>
    function navigationMenu() {
        return {
            mobileMenuOpen: false,
            dropdownOpen: false,
            
            init() {
                // Close mobile menu saat screen resize ke desktop
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 768) {
                        this.mobileMenuOpen = false;
                    }
                });
            }
        }
    }
</script>
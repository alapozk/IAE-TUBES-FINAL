<nav class="bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm fixed w-full top-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4">
        <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-lg flex items-center justify-center transform group-hover:scale-110 transition-transform duration-200">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">
                EduConnect
            </span>
        </a>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-8">
            <a href="{{ url('/#features') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Fitur</a>
            <a href="{{ url('/#courses') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Kursus</a>
            <a href="{{ url('/#about') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">Tentang</a>
            
            @auth
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-600">Halo, <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span></span>
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button class="text-gray-600 hover:text-red-500 transition-colors font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 font-medium transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    Daftar Gratis
                </a>
            @endauth
        </div>

        <!-- Mobile Menu Button -->
        <button id="mobile-menu-btn" class="md:hidden text-gray-700 hover:text-blue-600 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-200 px-6 py-4 space-y-4">
        <a href="{{ url('/#features') }}" class="block text-gray-700 hover:text-blue-600 font-medium">Fitur</a>
        <a href="{{ url('/#courses') }}" class="block text-gray-700 hover:text-blue-600 font-medium">Kursus</a>
        <a href="{{ url('/#about') }}" class="block text-gray-700 hover:text-blue-600 font-medium">Tentang</a>
        @auth
            <a href="{{ url('/dashboard') }}" class="block px-4 py-2 bg-blue-600 text-white rounded-lg text-center">Dashboard</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left text-red-500 font-medium">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" class="block px-4 py-2 border border-blue-600 text-blue-600 rounded-lg text-center">Login</a>
            <a href="{{ route('register') }}" class="block px-4 py-2 bg-blue-600 text-white rounded-lg text-center">Daftar Gratis</a>
        @endauth
    </div>
</nav>

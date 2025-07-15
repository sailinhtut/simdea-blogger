<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Simdea Blog - @yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex flex-col min-h-screen bg-white text-gray-900">

    {{-- Simple Navbar --}}
    <nav class="w-full border-b bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto flex items-center justify-between px-4 py-3 md:py-4">
            <a href="{{ url('/') }}" class="text-lg font-bold text-indigo-600">Simdea Blog</a>

            <button id="mobile-menu-toggle" class="block md:hidden text-gray-700 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div id="mobile-menu" class="hidden md:flex md:items-center gap-4">
                <a href="{{ url('/') }}"
                    class="text-sm font-medium {{ Request::is('/') ? 'text-indigo-600 font-semibold' : 'text-gray-700 hover:text-indigo-600' }}">
                    Home
                </a>
                <a href="{{ url('/blogs') }}"
                    class="text-sm font-medium {{ Request::is('blogs') ? 'text-indigo-600 font-semibold' : 'text-gray-700 hover:text-indigo-600' }}">
                    Blogs
                </a>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu-links" class="md:hidden hidden px-4 pb-3">
            <a href="{{ url('/') }}"
                class="block py-2 text-sm {{ Request::is('/') ? 'text-indigo-600 font-semibold' : 'text-gray-700 hover:text-indigo-600' }}">
                Home
            </a>
            <a href="{{ url('/blogs') }}"
                class="block py-2 text-sm {{ Request::is('blogs') ? 'text-indigo-600 font-semibold' : 'text-gray-700 hover:text-indigo-600' }}">
                Blogs
            </a>
        </div>
    </nav>


    {{-- Main Content --}}
    <main class="flex-grow container mx-auto px-4 py-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-gray-50 py-4 border-t text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} Simdea Blog. All rights reserved.
    </footer>

    {{-- Scripts --}}
    @stack('scripts')

    <script>
        // Toggle mobile menu
        document.getElementById('mobile-menu-toggle')?.addEventListener('click', () => {
            const links = document.getElementById('mobile-menu-links');
            links.classList.toggle('hidden');
        });
    </script>
</body>

</html>

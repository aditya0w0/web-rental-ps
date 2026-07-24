<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @hasSection('title')
            <title>@yield('title')</title>
        @else
            <title>{{ config('app.name', 'Laravel') }}</title>
        @endif

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </head>
    <body class="font-sans antialiased bg-white text-slate-900">
        <div class="min-h-screen bg-white">
            @include('layouts.navigation')

            @isset($header)
                <header class="border-b border-slate-200 bg-white">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-slate-950">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>

        <footer class="border-t border-slate-200 bg-slate-950 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="text-lg font-semibold mb-2">PlayHub</h4>
                    <p class="text-white/80">Jalan Sulawesi Gg 1b Sapuro Kebulen, Kota Pekalongan</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-2">Kontak</h4>
                    <p class="text-white/80 flex items-center gap-2"><i class="fas fa-envelope" aria-hidden="true"></i> PlayHubPekalongan01@gmail.com</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-2">Sosial Media</h4>
                    <div class="flex flex-col sm:flex-row sm:flex-wrap gap-3">
                        <a href="#" class="flex items-center gap-2 text-white/80 hover:text-white"><i class="fab fa-instagram" aria-hidden="true"></i> @PlayHub</a>
                        <a href="#" class="flex items-center gap-2 text-white/80 hover:text-white"><i class="fab fa-facebook" aria-hidden="true"></i> PlayHub</a>
                        <a href="#" class="flex items-center gap-2 text-white/80 hover:text-white"><i class="fab fa-tiktok" aria-hidden="true"></i> PlayHub Pekalongan</a>
                    </div>
                </div>
            </div>
            <div class="text-center text-xs text-white/70 pb-6">&copy; {{ date('Y') }} PlayHub. All rights reserved.</div>
        </footer>
    </body>
</html>

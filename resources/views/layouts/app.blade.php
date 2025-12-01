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

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=orbitron:400,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-green-50">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-green-600 shadow relative overflow-hidden">
                    <style>
                        .header-stars{position:absolute;inset:0;pointer-events:none}
                        .header-stars span{position:absolute;color:rgba(255,255,255,.9);filter:drop-shadow(0 0 6px rgba(255,255,255,.6));animation:twinkle 2.2s ease-in-out infinite}
                        .header-stars span.heart{color:#ffd1dc}
                        @keyframes twinkle{0%,100%{opacity:.2;transform:scale(.9) translateY(0)}50%{opacity:1;transform:scale(1.2) translateY(-2px)}}
                    </style>
                    <div class="header-stars">
                        <span style="left:8%;top:20%;font-size:10px">★</span>
                        <span style="left:15%;top:60%;font-size:8px;animation-duration:2.8s">★</span>
                        <span style="left:22%;top:35%;font-size:9px;animation-duration:2.4s">★</span>
                        <span class="heart" style="left:28%;top:50%;font-size:8px;animation-duration:3.1s">♥</span>
                        <span style="left:36%;top:25%;font-size:10px;animation-duration:2.6s">★</span>
                        <span style="left:44%;top:55%;font-size:8px;animation-duration:2.9s">★</span>
                        <span style="left:62%;top:40%;font-size:9px;animation-duration:2.5s">★</span>
                        <span class="heart" style="left:70%;top:65%;font-size:8px;animation-duration:3s">♥</span>
                        <span style="left:78%;top:30%;font-size:10px;animation-duration:2.7s">★</span>
                        <span style="left:86%;top:55%;font-size:8px;animation-duration:2.3s">★</span>
                    </div>
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-white">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>
        </div>
        <footer class="border-t text-white" style="background: linear-gradient(90deg,#ff9aa2 0%, #f3b5a8 45%, #9b72cf 75%, #f3d295 100%)">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h4 class="text-lg font-semibold mb-2">PlayHub</h4>
                    <p class="text-white/90">Jalan Sulawesi Gg 1b Sapuro Kebulen, Kota Pekalongan</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-2">Kontak</h4>
                    <p class="text-white/90 flex items-center gap-2"><i class="fas fa-envelope"></i> PlayHubPekalongan01@gmail.com</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-2">Sosial Media</h4>
                    <div class="flex items-center gap-4">
                        <a href="#" class="flex items-center gap-2 text-white/90 hover:text-white"><i class="fab fa-instagram"></i> @PlayHub</a>
                        <a href="#" class="flex items-center gap-2 text-white/90 hover:text-white"><i class="fab fa-facebook"></i> PlayHub</a>
                        <a href="#" class="flex items-center gap-2 text-white/90 hover:text-white"><i class="fab fa-tiktok"></i> PlayHub Pekalongan</a>
                    </div>
                </div>
            </div>
            <div class="text-center text-xs text-white/80 pb-6">© {{ date('Y') }} PlayHub. All rights reserved.</div>
        </footer>
    </body>
</html>
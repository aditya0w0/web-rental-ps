<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <style>
            .auth-wrap{min-height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;position:relative;background:linear-gradient(110deg,#ff9aa2 0%, #f3b5a8 32%, #9b72cf 68%, #f3d295 100%)}
            .auth-texture{position:absolute;inset:0;pointer-events:none;background-image:radial-gradient(rgba(255,255,255,.22) 1px, transparent 1px), radial-gradient(rgba(255,255,255,.14) 1px, transparent 1px);background-size:12px 12px, 24px 24px;background-position:0 0, 10px 10px;mix-blend-mode:soft-light}
            .auth-grid{position:absolute;inset:0;pointer-events:none;background-image:linear-gradient(rgba(255,255,255,.09) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.09) 1px, transparent 1px);background-size:22px 22px;mix-blend-mode:overlay;opacity:.6}
            .auth-noise{position:absolute;inset:0;pointer-events:none;background-image:repeating-linear-gradient(0deg, rgba(255,255,255,.1) 0 1px, rgba(255,255,255,0) 1px 3px), repeating-linear-gradient(90deg, rgba(255,255,255,.08) 0 1px, rgba(255,255,255,0) 1px 3px);mix-blend-mode:overlay;opacity:.5}
            .auth-shimmer{position:absolute;inset:-20%;pointer-events:none;background:conic-gradient(from 180deg at 50% 50%, rgba(255,255,255,.25), transparent 25%, rgba(255,255,255,.2) 50%, transparent 75%);filter:blur(28px);animation:spin 16s linear infinite;mix-blend-mode:soft-light;opacity:.38}
            .auth-shimmer2{position:absolute;inset:-25%;pointer-events:none;background:conic-gradient(from 0deg at 50% 50%, rgba(255,255,255,.2), transparent 30%, rgba(255,255,255,.18) 60%, transparent 90%);filter:blur(36px);animation:spin2 24s linear infinite;mix-blend-mode:overlay;opacity:.3}
            @keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}
            @keyframes spin2{from{transform:rotate(0deg)}to{transform:rotate(-360deg)}}
        </style>
        <div class="auth-wrap">
            <div class="auth-texture"></div>
            <div class="auth-grid"></div>
            <div class="auth-noise"></div>
            <div class="auth-shimmer"></div>
            <div class="auth-shimmer2"></div>
            <div>
                <a href="/">
                    <x-application-logo class="w-40 h-40" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg border border-white/30">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

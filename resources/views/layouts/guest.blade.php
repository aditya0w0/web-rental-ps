<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-100 text-slate-900">
        <main class="min-h-screen px-4 py-8">
            <div class="mx-auto flex min-h-[calc(100vh-4rem)] w-full max-w-md flex-col justify-center">
                <a href="{{ route('home') }}" class="mb-6 inline-flex items-center self-start">
                    <x-application-logo class="h-14 w-auto max-w-[13rem]" />
                </a>
                <section class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    {{ $slot }}
                </section>
            </div>
        </main>
    </body>
</html>

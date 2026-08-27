<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Npontu Tracker') }}</title>

        <!-- Fonts (Plus Jakarta Sans Exclusively) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-[#141414] antialiased bg-[#f0f0f0] selection:bg-indigo-500 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" wire:navigate class="flex items-center gap-3 group">
                    <x-application-logo class="w-12 h-12 transition-transform group-hover:scale-105" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-7 py-6 bg-white border-[1.6px] border-white/90 shadow-[0_4px_20px_rgba(24,30,45,0.045)] overflow-hidden sm:rounded-[22px]">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

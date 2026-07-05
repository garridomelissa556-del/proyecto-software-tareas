<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=space-grotesk:500,600,700|inter:400,500,600|jetbrains-mono:500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-ink antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-10 sm:pt-0 bg-paper">

            <a href="/" class="flex flex-col items-center gap-3 mb-2">
                <div class="w-14 h-14 rounded-2xl bg-brand flex items-center justify-center shadow-lg shadow-brand/30">
                    <span class="font-display font-bold text-white text-xl">MT</span>
                </div>
                <span class="font-display font-semibold text-ink text-lg">Mis Tareas</span>
            </a>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-white shadow-sm overflow-hidden sm:rounded-2xl border border-line">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
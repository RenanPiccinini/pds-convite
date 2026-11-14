<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PDS Convite') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|outfit:300,400,500,600,700,800,900|plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-slate-100 bg-[#060814] min-h-screen relative overflow-x-hidden selection:bg-cyan-500 selection:text-black">
        <!-- Parallax Background Elements & Glowing Orbs -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <!-- Top Right Cyan Glow -->
            <div class="absolute -top-32 -right-32 w-96 h-96 bg-cyan-500/20 rounded-full blur-[120px] parallax-layer-slow"></div>
            <!-- Bottom Left Purple Glow -->
            <div class="absolute -bottom-32 -left-32 w-[30rem] h-[30rem] bg-purple-600/20 rounded-full blur-[140px] parallax-layer-fast"></div>
            <!-- Center Electric Glow -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-blue-600/10 rounded-full blur-[100px]"></div>
            <!-- Subtle Cyber Grid Background -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#1f29370f_1px,transparent_1px),linear-gradient(to_bottom,#1f29370f_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)]"></div>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col justify-center items-center p-4 sm:p-6">
            <div class="mb-6">
                <a href="{{ route('home') }}" class="group transition-transform duration-300 hover:scale-105 inline-block">
                    <x-application-logo />
                </a>
            </div>

            <div class="w-full sm:max-w-md perspective-1000">
                <div class="glass-panel rounded-3xl p-8 sm:p-10 shadow-2xl border border-cyan-500/20 backdrop-blur-2xl relative transition-all duration-300 hover:border-cyan-500/40 transform-style-3d">
                    {{ $slot }}
                </div>
            </div>

            <!-- Footer copyright -->
            <div class="mt-8 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} PDS - Piccinini Digital Solutions. Todos os direitos reservados.
            </div>
        </div>
    </body>
</html>

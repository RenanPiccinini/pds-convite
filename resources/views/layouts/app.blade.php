<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PDS Convite') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|outfit:300,400,500,600,700,800,900|plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#060814] text-slate-100 min-h-screen selection:bg-cyan-500 selection:text-black" x-data="{ mobileSidebarOpen: false }">
        <!-- Parallax Background Elements & Glowing Orbs -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
            <div class="absolute -top-40 -right-40 w-[32rem] h-[32rem] bg-cyan-500/15 rounded-full blur-[140px] parallax-layer-slow"></div>
            <div class="absolute bottom-10 left-10 w-[28rem] h-[28rem] bg-purple-600/15 rounded-full blur-[130px] parallax-layer-fast"></div>
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#1f293708_1px,transparent_1px),linear-gradient(to_bottom,#1f293708_1px,transparent_1px)] bg-[size:4rem_4rem]"></div>
        </div>

        <div class="relative z-10 flex min-h-screen">
            <!-- Desktop Sidebar (Screenshots 3, 4, 5) -->
            <aside class="hidden lg:flex flex-col w-64 border-r border-slate-800/80 bg-[#080d22]/80 backdrop-blur-xl shrink-0 p-5 justify-between">
                <div>
                    <!-- Logo & Brand -->
                    <div class="pb-6 mb-4 border-b border-slate-800/60">
                        <a href="{{ route('events.index') }}" class="flex items-center gap-3 group">
                            <img src="{{ asset('images/pds-logo.png') }}" alt="PDS Logo" class="h-10 w-10 object-contain drop-shadow-[0_0_12px_rgba(0,240,255,0.7)] group-hover:scale-105 transition-transform" />
                            <div class="flex flex-col">
                                <span class="font-black text-lg text-white font-display tracking-tight">
                                    PDS <span class="text-cyan-400">CONVITE</span>
                                </span>
                                <span class="text-[9px] uppercase tracking-widest text-slate-400 font-mono">Piccinini Solutions</span>
                            </div>
                        </a>
                    </div>

                    <!-- Navigation Items -->
                    <nav class="space-y-1.5">
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('profile.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Configurações</span>
                        </a>

                        <a href="{{ route('events.index') }}" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('events.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Meus eventos</span>
                        </a>

                        <!-- Logout Form -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all text-left">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Sair</span>
                            </button>
                        </form>
                    </nav>
                </div>

                <!-- Bottom Links (Screenshots 3, 4: Enviar feedback, Central de ajuda) -->
                <div class="pt-6 border-t border-slate-800/60 space-y-2">
                    <a href="https://wa.me/?text=Feedback%20sobre%20o%20PDS%20Convite" target="_blank" class="flex items-center gap-2.5 text-xs text-slate-500 hover:text-slate-300 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Enviar feedback</span>
                    </a>
                    <a href="https://wa.me/?text=Ajuda%20com%20o%20PDS%20Convite" target="_blank" class="flex items-center gap-2.5 text-xs text-slate-500 hover:text-slate-300 transition-colors">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Central de ajuda</span>
                    </a>
                </div>
            </aside>

            <!-- Mobile Drawer Backdrop -->
            <div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false" class="fixed inset-0 z-40 bg-black/80 backdrop-blur-md lg:hidden"></div>

            <!-- Mobile Drawer (100% IDENTICAL TO DESKTOP FEATURES) -->
            <aside x-show="mobileSidebarOpen" x-cloak class="fixed inset-y-0 left-0 z-50 w-72 bg-[#080d22] border-r border-slate-800 p-5 flex flex-col justify-between lg:hidden transition-transform shadow-2xl">
                <div>
                    <!-- Brand + Close button -->
                    <div class="flex items-center justify-between pb-5 mb-4 border-b border-slate-800">
                        <a href="{{ route('events.index') }}" class="flex items-center gap-2.5">
                            <img src="{{ asset('images/pds-logo.png') }}" alt="PDS Logo" class="h-9 w-9 object-contain drop-shadow-[0_0_10px_rgba(0,240,255,0.7)]" />
                            <div class="flex flex-col">
                                <span class="font-black text-base text-white font-display">PDS <span class="text-cyan-400">CONVITE</span></span>
                                <span class="text-[8px] uppercase tracking-wider text-slate-400 font-mono">Piccinini Solutions</span>
                            </div>
                        </a>
                        <button @click="mobileSidebarOpen = false" class="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- User Info Card on Mobile -->
                    <div class="mb-4 p-3 rounded-2xl bg-slate-900/90 border border-slate-800 flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold text-sm border border-cyan-500/30 shrink-0">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Organizador' }}</div>
                            <div class="text-[10px] text-slate-400 truncate">{{ Auth::user()->email ?? '' }}</div>
                        </div>
                    </div>

                    <!-- Navigation Items on Mobile -->
                    <nav class="space-y-1.5">
                        <a href="{{ route('events.index') }}" @click="mobileSidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('events.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>Meus eventos</span>
                        </a>

                        <a href="{{ route('profile.edit') }}" @click="mobileSidebarOpen = false" class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('profile.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/40' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Configurações</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all text-left">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Sair</span>
                            </button>
                        </form>
                    </nav>
                </div>

                <!-- Bottom Links on Mobile (Enviar feedback, Central de ajuda) -->
                <div class="pt-5 border-t border-slate-800 space-y-2">
                    <a href="https://wa.me/?text=Feedback%20sobre%20o%20PDS%20Convite" target="_blank" class="flex items-center gap-2.5 text-xs text-slate-400 hover:text-white transition-colors py-1">
                        <svg class="h-4 w-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Enviar feedback</span>
                    </a>
                    <a href="https://wa.me/?text=Ajuda%20com%20o%20PDS%20Convite" target="_blank" class="flex items-center gap-2.5 text-xs text-slate-400 hover:text-white transition-colors py-1">
                        <svg class="h-4 w-4 text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Central de ajuda</span>
                    </a>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Mobile Top Bar -->
                <header class="lg:hidden flex items-center justify-between px-3 sm:px-4 py-3 border-b border-slate-800/80 bg-[#080d22]/95 backdrop-blur-md sticky top-0 z-30">
                    <div class="flex items-center gap-2">
                        <button @click="mobileSidebarOpen = true" class="p-2 text-slate-300 hover:text-white rounded-lg hover:bg-slate-800" title="Menu">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <a href="{{ route('events.index') }}" class="flex items-center gap-2">
                            <img src="{{ asset('images/pds-logo.png') }}" alt="PDS Logo" class="h-8 w-8 object-contain drop-shadow-[0_0_8px_rgba(0,240,255,0.7)]" />
                            <span class="font-black text-sm text-white font-display">PDS <span class="text-cyan-400">CONVITE</span></span>
                        </a>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('events.create') }}" class="inline-flex items-center gap-1 text-xs bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-bold px-3 py-1.5 rounded-lg shadow-sm border border-cyan-400/30">
                            <span>+</span>
                            <span>Novo</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="p-1.5 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </a>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 p-3.5 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="mb-5 p-3.5 sm:p-4 rounded-2xl bg-cyan-950/60 border border-cyan-500/50 text-cyan-200 text-xs sm:text-sm flex items-center justify-between shadow-lg shadow-cyan-500/10">
                            <div class="flex items-center gap-2.5">
                                <svg class="h-5 w-5 text-cyan-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-cyan-400 hover:text-cyan-200 text-xs font-bold p-1">✕</button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-5 p-3.5 sm:p-4 rounded-2xl bg-rose-950/60 border border-rose-500/50 text-rose-200 text-xs sm:text-sm flex items-center justify-between shadow-lg shadow-rose-500/10">
                            <div class="flex items-center gap-2.5">
                                <svg class="h-5 w-5 text-rose-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ session('error') }}</span>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-200 text-xs font-bold p-1">✕</button>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>

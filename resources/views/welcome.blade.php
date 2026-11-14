<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDS Convite - Piccinini Digital Solutions</title>
    <meta name="description" content="Sistema profissional de convites online e confirmação de presença com integração WhatsApp e sem limite de convidados.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800,900|plus-jakarta-sans:400,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#060814] text-slate-100 min-h-screen relative overflow-x-hidden selection:bg-cyan-500 selection:text-black">
    <!-- Ambient Parallax & 3D Glowing Orbs -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <!-- Top Left Cyan Aurora -->
        <div class="absolute -top-40 -left-40 w-[40rem] h-[40rem] bg-cyan-500/20 rounded-full blur-[160px] parallax-layer-slow"></div>
        <!-- Center Right Purple Aurora -->
        <div class="absolute top-1/4 -right-40 w-[45rem] h-[45rem] bg-purple-600/25 rounded-full blur-[170px] parallax-layer-fast"></div>
        <!-- Bottom Blue Aurora -->
        <div class="absolute -bottom-40 left-1/3 w-[35rem] h-[35rem] bg-blue-600/20 rounded-full blur-[150px]"></div>
        <!-- Futuristic Cyber Grid lines -->
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#1f293710_1px,transparent_1px),linear-gradient(to_bottom,#1f293710_1px,transparent_1px)] bg-[size:4rem_4rem]"></div>
    </div>

    <div class="relative z-10 flex flex-col min-h-screen">
        <!-- Navigation Bar -->
        <header class="w-full border-b border-slate-800/80 bg-[#080d22]/80 backdrop-blur-xl sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between gap-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 group shrink-0">
                    <img src="{{ asset('images/pds-logo.png') }}" alt="PDS Logo" class="h-9 w-9 sm:h-12 sm:w-12 object-contain drop-shadow-[0_0_15px_rgba(0,240,255,0.7)] group-hover:scale-105 transition-transform" />
                    <div class="flex flex-col">
                        <span class="text-base sm:text-xl font-black text-white font-display tracking-tight">
                            PDS <span class="text-cyan-400">CONVITE</span>
                        </span>
                        <span class="text-[8px] sm:text-[9px] uppercase tracking-widest text-slate-400 font-mono hidden xs:inline">Piccinini Digital Solutions</span>
                    </div>
                </a>

                <div class="flex items-center gap-2 sm:gap-4">
                    @auth
                        <a href="{{ route('events.index') }}" class="btn-pds-primary px-4 sm:px-5 py-2 sm:py-2.5 rounded-full text-white font-bold text-xs sm:text-sm">
                            Acessar Painel
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold text-slate-300 hover:text-cyan-400 transition-colors px-2 py-1">
                            Entrar
                        </a>
                        <a href="{{ route('register') }}" class="btn-pds-primary px-3.5 sm:px-5 py-2 sm:py-2.5 rounded-full text-white font-bold text-xs sm:text-sm">
                            <span>Criar Conta<span class="hidden sm:inline"> Gratuita</span></span>
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        <!-- Hero Section with 3D Showcase -->
        <main class="flex-1">
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 sm:pt-16 pb-16 sm:pb-24 text-center overflow-hidden">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3 sm:px-4 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-300 text-[11px] sm:text-xs font-semibold mb-6 sm:mb-8 shadow-[0_0_20px_rgba(0,240,255,0.2)] max-w-full">
                    <span class="relative flex h-2 w-2 shrink-0">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                    </span>
                    <span class="truncate">Piccinini Digital Solutions • Tecnologia de Alta Performance</span>
                </div>

                <!-- Main Title -->
                <h1 class="text-3xl sm:text-6xl lg:text-7xl font-black text-white tracking-tight font-display max-w-5xl mx-auto leading-tight">
                    Confirmação de Presença
                    <span class="block text-gradient-pds">Elegante, Rápida & Sem Limites</span>
                </h1>

                <p class="mt-4 sm:mt-6 text-sm sm:text-xl text-slate-300 max-w-2xl mx-auto font-light leading-relaxed">
                    Crie seus eventos, compartilhe o link diretamente no WhatsApp e receba a confirmação dos seus convidados em menos de 10 segundos, com suporte a acompanhantes.
                </p>

                <!-- CTA Buttons -->
                <div class="mt-8 sm:mt-10 flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 max-w-md sm:max-w-none mx-auto">
                    @auth
                        <a href="{{ route('events.index') }}" class="btn-pds-primary w-full sm:w-auto px-8 py-3.5 sm:py-4 rounded-full text-white font-bold text-sm sm:text-base flex items-center justify-center gap-2 shadow-2xl">
                            <span>Ir para Meus Eventos</span>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-pds-primary w-full sm:w-auto px-8 py-3.5 sm:py-4 rounded-full text-white font-bold text-sm sm:text-base flex items-center justify-center gap-2 shadow-2xl">
                            <span>Começar Agora (Grátis)</span>
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 sm:py-4 rounded-full bg-slate-900 hover:bg-slate-800 border border-slate-700 text-white font-semibold text-sm sm:text-base transition-colors flex items-center justify-center">
                            Acessar Gerenciador
                        </a>
                    @endauth
                </div>

                <!-- 3D Perspective Card Showcase -->
                <div class="mt-12 sm:mt-20 max-w-4xl mx-auto perspective-2000">
                    <div class="glass-panel p-2 sm:p-4 rounded-2xl sm:rounded-[2.5rem] border border-cyan-500/30 shadow-[0_30px_100px_rgba(0,0,0,0.8),0_0_50px_rgba(0,240,255,0.2)] transform-style-3d transition-transform duration-500 hover:scale-[1.01]" data-tilt>
                        <div class="rounded-xl sm:rounded-3xl bg-[#090e24] p-4 sm:p-10 text-left border border-slate-800 space-y-5 sm:space-y-6">
                            <!-- Showcase Top Bar -->
                            <div class="flex flex-wrap items-center justify-between gap-2 sm:gap-4 border-b border-slate-800 pb-3 sm:pb-4">
                                <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        <div class="h-2.5 w-2.5 sm:h-3 sm:w-3 rounded-full bg-rose-500"></div>
                                        <div class="h-2.5 w-2.5 sm:h-3 sm:w-3 rounded-full bg-amber-500"></div>
                                        <div class="h-2.5 w-2.5 sm:h-3 sm:w-3 rounded-full bg-emerald-500"></div>
                                    </div>
                                    <span class="text-[11px] sm:text-xs text-slate-400 font-mono truncate max-w-[150px] sm:max-w-none">pdsconvite.online/convite/96c5a861</span>
                                </div>
                                <div class="px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 text-[10px] sm:text-xs font-bold shrink-0">
                                    ♾️ SEM LIMITE DE CONVITES
                                </div>
                            </div>

                            <!-- Mock Interactive View of the Invitation -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div class="space-y-4">
                                    <div class="text-xs uppercase tracking-widest text-cyan-400 font-bold font-mono">Prévia do Convite</div>
                                    <h3 class="text-2xl sm:text-3xl font-extrabold text-white">Confraternização Irigaray 2026</h3>
                                    <div class="space-y-2 text-xs text-slate-300">
                                        <div class="flex items-center gap-2">
                                            <span>📅</span>
                                            <span><strong>Quando:</strong> 11 de dez. de 2026 às 18:30</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span>📍</span>
                                            <span><strong>Onde:</strong> Rua Edu Chaves, 453 - São João, Porto Alegre</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-amber-400">
                                            <span>⏱</span>
                                            <span><strong>Contagem regressiva ativa</strong> em tempo real</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-slate-400 leading-relaxed">
                                        O convidado não precisa baixar aplicativo e nem criar conta: basta abrir o link, digitar o nome e os acompanhantes!
                                    </p>
                                </div>

                                <div class="bg-white text-slate-800 p-6 rounded-3xl shadow-2xl space-y-4 border border-slate-100">
                                    <div class="text-center">
                                        <div class="text-[10px] uppercase text-slate-500 font-serif">Confirme sua presença</div>
                                        <div class="text-lg font-bold text-slate-900">Confraternização 2026</div>
                                    </div>
                                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 text-xs flex justify-between font-bold">
                                        <span>⏱ Prazo:</span>
                                        <span>12d : 03h : 12m : 33s</span>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Seu nome</label>
                                        <input type="text" readonly value="Jessica Aleixo" class="w-full text-xs px-3 py-2 bg-slate-100 rounded-xl border border-slate-200 text-slate-900 font-medium" />
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Acompanhantes</label>
                                        <div class="text-xs p-2 rounded-xl bg-purple-50 text-purple-700 font-medium border border-purple-200">
                                            + Lucas Aleixo
                                        </div>
                                    </div>
                                    <button class="w-full py-2.5 rounded-xl bg-slate-900 text-white font-bold text-xs shadow-md">
                                        Confirmar presença
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Grid -->
            <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 border-t border-slate-800/80">
                <div class="text-center max-w-3xl mx-auto mb-16">
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-white font-display">
                        Tudo o que você precisa para o seu evento
                    </h2>
                    <p class="mt-3 text-sm text-slate-400">
                        Projetado para aniversários, casamentos, formaturas, confraternizações empresariais e eventos VIP.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Feature 1 -->
                    <div class="glass-card p-8 rounded-3xl space-y-4 border border-slate-800" data-tilt>
                        <div class="h-12 w-12 rounded-2xl bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 flex items-center justify-center text-2xl shadow-[0_0_20px_rgba(0,240,255,0.2)]">
                            💬
                        </div>
                        <h3 class="text-lg font-bold text-white">Disparo Fácil no WhatsApp</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Envie convites nominais ou em grupos com mensagens formatadas com link direto. Seus convidados confirmam em 1 clique.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="glass-card p-8 rounded-3xl space-y-4 border border-slate-800" data-tilt>
                        <div class="h-12 w-12 rounded-2xl bg-purple-500/10 border border-purple-500/30 text-purple-400 flex items-center justify-center text-2xl shadow-[0_0_20px_rgba(168,85,247,0.2)]">
                            ♾️
                        </div>
                        <h3 class="text-lg font-bold text-white">Convites Ilimitados</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Sem limite de 20 convidados ou cobranças por pessoa. Receba centenas ou milhares de confirmações com tranquilidade.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="glass-card p-8 rounded-3xl space-y-4 border border-slate-800" data-tilt>
                        <div class="h-12 w-12 rounded-2xl bg-blue-500/10 border border-blue-500/30 text-blue-400 flex items-center justify-center text-2xl shadow-[0_0_20px_rgba(59,130,246,0.2)]">
                            📊
                        </div>
                        <h3 class="text-lg font-bold text-white">Lista e Exportação Excel</h3>
                        <p class="text-xs text-slate-400 leading-relaxed">
                            Visualize a lista em modo Normal ou por Grupo de Titular + Acompanhantes. Exporte tudo em CSV / Excel para a recepção do evento.
                        </p>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="border-t border-slate-800/80 bg-[#04060e] py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/pds-logo.png') }}" alt="PDS Logo" class="h-8 w-8 object-contain" />
                    <span class="text-sm font-bold text-white">PDS Convite</span>
                    <span class="text-xs text-slate-500">• Piccinini Digital Solutions</span>
                </div>
                <div class="text-xs text-slate-500">
                    &copy; {{ date('Y') }} Piccinini Digital Solutions. Todos os direitos reservados.
                </div>
            </div>
        </footer>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Confirme sua presença - {{ $event->title }} | PDS Convite</title>
    <meta name="description" content="Confirmação de presença para o evento {{ $event->title }}. Confirme seu nome e acompanhantes online.">

    <!-- OpenGraph meta tags for WhatsApp previews -->
    <meta property="og:title" content="Convite: {{ $event->title }}">
    <meta property="og:description" content="Confirme sua presença até {{ $event->formatted_rsvp_deadline }}. Local: {{ $event->location_address }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $event->public_url }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800,900|plus-jakarta-sans:400,600,700,800|playfair-display:600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#060814] text-slate-100 min-h-screen relative overflow-x-hidden selection:bg-cyan-500 selection:text-black"
    x-data="{
        name: '',
        phone: '',
        companions: [],
        addCompanion() {
            if (this.companions.length < {{ $event->max_companions }}) {
                this.companions.push('');
            } else {
                window.pdsAlert('Limite máximo de {{ $event->max_companions }} acompanhantes atingido.', 'Limite Atingido', 'warning');
            }
        },
        removeCompanion(index) {
            this.companions.splice(index, 1);
        },
        submitting: false,
        successModal: false,
        confirmedGuest: null,
        
        // Countdown timer calculation
        deadlineTimestamp: new Date('{{ $event->rsvp_deadline->toIso8601String() }}').getTime(),
        days: '00',
        hours: '00',
        minutes: '00',
        seconds: '00',
        isExpired: false,

        initCountdown() {
            const update = () => {
                const now = new Date().getTime();
                const diff = this.deadlineTimestamp - now;

                if (diff <= 0) {
                    this.isExpired = true;
                    this.days = '00';
                    this.hours = '00';
                    this.minutes = '00';
                    this.seconds = '00';
                    return;
                }

                const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((diff % (1000 * 60)) / 1000);

                this.days = String(d).padStart(2, '0');
                this.hours = String(h).padStart(2, '0');
                this.minutes = String(m).padStart(2, '0');
                this.seconds = String(s).padStart(2, '0');
            };

            update();
            setInterval(update, 1000);
        },

        async submitRsvp() {
            if (!this.name || this.name.trim().length < 3) {
                window.pdsAlert('Por favor, informe seu nome completo para confirmar sua presença.', 'Nome Obrigatório', 'info');
                return;
            }

            if (this.isExpired) {
                window.pdsAlert('Desculpe, o prazo de confirmação para este evento já foi encerrado.', 'Prazo Encerrado', 'warning');
                return;
            }

            this.submitting = true;

            try {
                const res = await fetch('{{ route('public.rsvp.confirm', $event->slug) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: this.name.trim(),
                        phone: this.phone.trim(),
                        companions: this.companions.filter(c => c && c.trim().length > 0)
                    })
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    this.confirmedGuest = data.guest;
                    this.successModal = true;
                    if (window.triggerConfetti) {
                        window.triggerConfetti();
                    }
                } else {
                    window.pdsAlert(data.message || 'Erro ao confirmar presença. Tente novamente.', 'Aviso', 'warning');
                }
            } catch (err) {
                console.error(err);
                window.pdsAlert('Erro de conexão ao servidor. Verifique sua conexão com a internet.', 'Erro de Conexão', 'warning');
            } finally {
                this.submitting = false;
            }
        }
    }"
    x-init="initCountdown()">

    <!-- Ambient glowing lights & Parallax Layers -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-32 -left-32 w-[34rem] h-[34rem] bg-cyan-500/15 rounded-full blur-[140px] parallax-layer-slow"></div>
        <div class="absolute top-1/3 -right-32 w-[36rem] h-[36rem] bg-purple-600/15 rounded-full blur-[150px] parallax-layer-fast"></div>
        <div class="absolute -bottom-32 left-1/3 w-[30rem] h-[30rem] bg-blue-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#1e293b0a_1px,transparent_1px),linear-gradient(to_bottom,#1e293b0a_1px,transparent_1px)] bg-[size:4rem_4rem]"></div>
    </div>

    <div class="relative z-10 min-h-screen flex flex-col justify-between py-8 px-4 sm:px-6">
        <!-- Top Branding / Logo -->
        <header class="flex justify-center mb-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 opacity-90 hover:opacity-100 transition-opacity">
                <img src="{{ asset('images/pds-logo.png') }}" alt="PDS Convite" class="h-8 w-8 object-contain drop-shadow-[0_0_10px_rgba(0,240,255,0.7)]" />
                <span class="text-sm font-black tracking-wider text-white font-display">
                    PDS <span class="text-cyan-400">CONVITE</span>
                </span>
            </a>
        </header>

        <!-- Main Invitation RSVP Container (Matches Screenshot 1) -->
        <main class="w-full max-w-lg mx-auto space-y-6">
            <!-- Header Titles (Screenshot 1: "Confirme sua presença" + Title) -->
            <div class="text-center space-y-2">
                <div class="text-sm uppercase tracking-widest text-slate-300 font-serif italic">
                    Confirme sua presença
                </div>
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white font-display tracking-tight text-gradient-silver">
                    {{ $event->title }}
                </h1>
                <div class="w-16 h-0.5 mx-auto bg-gradient-to-r from-transparent via-cyan-400 to-transparent"></div>
            </div>

            <!-- RSVP White/Glass Card (Screenshot 1) -->
            <div class="bg-white/95 text-slate-800 rounded-[2.5rem] p-6 sm:p-8 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.6),0_0_40px_rgba(0,240,255,0.15)] backdrop-blur-xl border border-white/40 transform-style-3d relative" data-tilt>
                <div class="space-y-6">
                    <!-- Quando (Screenshot 1) -->
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 text-slate-800 font-bold text-sm">
                            <span class="text-slate-600">📅</span>
                            <span>Quando</span>
                        </div>
                        <div class="text-slate-800 font-medium text-sm pl-6">
                            {{ $event->formatted_event_date }}
                        </div>
                        <div class="text-xs text-amber-700 font-medium flex items-center gap-1 pl-6">
                            <span>⏱ Confirmar até: {{ $event->formatted_rsvp_deadline }}</span>
                        </div>
                    </div>

                    <!-- Onde (Screenshot 1) -->
                    <div class="space-y-1">
                        <div class="flex items-center gap-2 text-slate-800 font-bold text-sm">
                            <span class="text-slate-600">📍</span>
                            <span>Onde</span>
                        </div>
                        @if($event->location_name)
                            <div class="text-xs font-bold text-slate-700 pl-6">
                                {{ $event->location_name }}
                            </div>
                        @endif
                        <div class="text-slate-700 text-xs sm:text-sm leading-relaxed pl-6">
                            {{ $event->location_address }}
                        </div>
                        <div class="pl-6 pt-0.5">
                            <a href="{{ $event->map_url }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-semibold underline">
                                <span>Abrir no mapa</span>
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Prazo para confirmar (Countdown Banner - Screenshot 1) -->
                    <div class="p-3.5 rounded-2xl bg-[#fff9e6] border border-[#fde68a] text-amber-900 flex items-center justify-between shadow-sm">
                        <div class="text-xs font-semibold flex items-center gap-1.5">
                            <span>⏱</span>
                            <span>Prazo para confirmar</span>
                        </div>

                        <!-- Countdown digits (Screenshot 1: 12 03 12 33 / dias horas min seg) -->
                        <div class="flex items-center gap-2 text-center" x-show="!isExpired">
                            <div>
                                <span class="font-extrabold text-sm block" x-text="days">12</span>
                                <span class="text-[9px] uppercase text-amber-700 block">dias</span>
                            </div>
                            <span class="text-xs font-bold text-amber-400">:</span>
                            <div>
                                <span class="font-extrabold text-sm block" x-text="hours">03</span>
                                <span class="text-[9px] uppercase text-amber-700 block">horas</span>
                            </div>
                            <span class="text-xs font-bold text-amber-400">:</span>
                            <div>
                                <span class="font-extrabold text-sm block" x-text="minutes">12</span>
                                <span class="text-[9px] uppercase text-amber-700 block">min</span>
                            </div>
                            <span class="text-xs font-bold text-amber-400">:</span>
                            <div>
                                <span class="font-extrabold text-sm block text-amber-600" x-text="seconds">33</span>
                                <span class="text-[9px] uppercase text-amber-700 block">seg</span>
                            </div>
                        </div>

                        <div x-show="isExpired" class="text-xs font-bold text-rose-600 uppercase">
                            Prazo Encerrado
                        </div>
                    </div>

                    <!-- Extra Event Notes / Dress Code if present -->
                    @if ($event->description)
                        <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200 text-xs text-slate-600 leading-relaxed">
                            <span class="font-bold text-slate-800">Observações:</span> {{ $event->description }}
                        </div>
                    @endif

                    <!-- RSVP Form Fields (Screenshot 1) -->
                    <div class="space-y-4 pt-2">
                        <!-- Seu nome (Screenshot 1) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center gap-1">
                                <span>Seu nome</span>
                                <span class="text-slate-400 text-[10px]" title="Informe seu nome completo">ⓘ</span>
                            </label>
                            <input type="text" x-model="name" placeholder="Nome e sobrenome" :disabled="isExpired"
                                class="w-full px-4 py-3 rounded-2xl bg-slate-100/80 border border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:bg-white text-sm transition-all shadow-inner" />
                        </div>

                        <!-- Telefone WhatsApp (Opcional) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 flex items-center gap-1">
                                <span>WhatsApp / Celular</span>
                                <span class="text-slate-400 text-[10px] font-normal">(opcional)</span>
                            </label>
                            <input type="text" x-model="phone" placeholder="(00) 00000-0000" :disabled="isExpired"
                                class="w-full px-4 py-3 rounded-2xl bg-slate-100/80 border border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:bg-white text-sm transition-all shadow-inner" />
                        </div>

                        <!-- Acompanhantes (Screenshot 1: "+ Adicionar") -->
                        @if ($event->allow_companions)
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-700">
                                    Acompanhantes
                                </label>

                                <!-- Dynamic List of Companion Inputs -->
                                <div class="space-y-2">
                                    <template x-for="(companion, index) in companions" :key="index">
                                        <div class="flex items-center gap-2 animate-fadeIn">
                                            <input type="text" x-model="companions[index]" placeholder="Nome completo do acompanhante"
                                                class="flex-1 px-4 py-2.5 rounded-2xl bg-slate-100 border border-slate-200 text-slate-900 placeholder-slate-400 text-xs focus:ring-2 focus:ring-cyan-500 focus:bg-white" />
                                            <button type="button" @click="removeCompanion(index)" class="p-2 text-slate-400 hover:text-rose-500 rounded-full hover:bg-slate-100" title="Remover">
                                                ✕
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                <!-- "+ Adicionar" Button (Screenshot 1) -->
                                <button type="button" @click="addCompanion()" :disabled="isExpired"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition-colors">
                                    <span class="text-base leading-none">⊕</span>
                                    <span>Adicionar</span>
                                </button>
                            </div>
                        @endif

                        <!-- Submit CTA Button (Screenshot 1: "Confirmar presença") -->
                        <div class="pt-3">
                            <button type="button" @click="submitRsvp()" :disabled="submitting || isExpired"
                                class="w-full py-4 px-6 rounded-2xl text-white font-bold text-sm tracking-wide transition-all duration-300 flex items-center justify-center gap-2 shadow-xl"
                                :class="isExpired ? 'bg-slate-400 cursor-not-allowed' : (name.length >= 3 ? 'bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 hover:from-cyan-600 hover:to-purple-600 hover:scale-[1.01] active:scale-[0.99] shadow-cyan-500/20' : 'bg-slate-300 text-slate-500 cursor-not-allowed')">
                                <template x-if="submitting">
                                    <span>Confirmando presença...</span>
                                </template>
                                <template x-if="!submitting">
                                    <span class="flex items-center gap-2">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Confirmar presença</span>
                                    </span>
                                </template>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="mt-8 text-center text-xs text-slate-500">
            Tecnologia <span class="text-cyan-400 font-bold">PDS Convite</span> • Piccinini Digital Solutions
        </footer>
    </div>

    <!-- ================= SUCCESS CONFIRMATION MODAL ================= -->
    <div x-show="successModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-xl animate-fadeIn">
        <div class="glass-panel w-full max-w-md rounded-3xl p-6 sm:p-8 border border-cyan-400/40 shadow-[0_0_50px_rgba(0,240,255,0.3)] text-center space-y-6 transform-style-3d">
            <!-- Glowing Success Icon -->
            <div class="h-20 w-20 mx-auto rounded-full bg-emerald-500/20 border-2 border-emerald-400 flex items-center justify-center text-emerald-400 shadow-[0_0_30px_rgba(52,211,153,0.5)] animate-bounce">
                <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <div class="space-y-1">
                <h2 class="text-2xl font-black text-white font-display">Presença Confirmada!</h2>
                <p class="text-xs text-slate-300">
                    Obrigado por confirmar! Sua participação foi registrada com sucesso.
                </p>
            </div>

            <!-- Ticket Summary Card -->
            <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-700/80 text-left text-xs space-y-2.5">
                <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                    <span class="text-slate-400">Evento:</span>
                    <span class="font-bold text-white">{{ $event->title }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-slate-800 pb-2">
                    <span class="text-slate-400">Titular:</span>
                    <span class="font-bold text-cyan-400" x-text="confirmedGuest?.name"></span>
                </div>
                <template x-if="confirmedGuest?.companions && confirmedGuest.companions.length > 0">
                    <div class="space-y-1">
                        <span class="text-slate-400">Acompanhantes:</span>
                        <div class="flex flex-wrap gap-1.5 pt-1">
                            <template x-for="c in confirmedGuest.companions" :key="c">
                                <span class="px-2 py-0.5 rounded-lg bg-purple-500/20 text-purple-300 border border-purple-500/30 text-[11px]" x-text="c"></span>
                            </template>
                        </div>
                    </div>
                </template>
                <div class="flex items-center justify-between pt-1">
                    <span class="text-slate-400">Data & Hora:</span>
                    <span class="text-slate-200">{{ $event->formatted_event_date }}</span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-2 pt-2">
                <a href="{{ $event->google_calendar_url }}" target="_blank" class="w-full py-3 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-600 text-white font-semibold text-xs flex items-center justify-center gap-2 transition-colors">
                    <span>📅 Adicionar ao Google Agenda</span>
                </a>

                <a href="{{ $event->map_url }}" target="_blank" class="w-full py-3 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-600 text-white font-semibold text-xs flex items-center justify-center gap-2 transition-colors">
                    <span>📍 Ver Local no Google Maps</span>
                </a>

                <button type="button" @click="location.reload()" class="w-full py-3 px-4 rounded-xl btn-pds-primary text-white font-bold text-xs">
                    Concluir
                </button>
            </div>
        </div>
    </div>
</body>
</html>

<x-app-layout>
    <div class="space-y-5 sm:space-y-6">
        <!-- Header (Screenshot 3) -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-white font-display tracking-tight flex items-center gap-2.5">
                    <span>Eventos</span>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 font-mono">
                        {{ $events->count() }}
                    </span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">Crie eventos, compartilhe links ilimitados no WhatsApp e acompanhe presenças em tempo real</p>
            </div>

            <a href="{{ route('events.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl sm:rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-bold text-xs sm:text-sm shadow-[0_0_20px_rgba(0,240,255,0.4)] transition-all duration-200 hover:scale-[1.02] border border-cyan-300/30">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Adicionar Evento</span>
            </a>
        </div>

        <!-- Tabs: Próximos / Anteriores (Screenshot 3 - 100% RESPONSIVE) -->
        <div class="flex justify-center my-4 sm:my-6">
            <div class="inline-flex p-1 rounded-full bg-slate-900/95 border border-slate-800 shadow-inner max-w-full">
                <a href="{{ route('events.index', ['tab' => 'proximos']) }}" 
                    class="px-3.5 sm:px-6 py-2 rounded-full text-xs sm:text-sm font-semibold transition-all duration-200 flex items-center gap-1.5 sm:gap-2 {{ $tab !== 'anteriores' ? 'bg-[#18233f] text-white shadow-md border border-cyan-500/30 text-cyan-300' : 'text-slate-400 hover:text-slate-200' }}">
                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>Próximos ({{ $upcomingCount }})</span>
                </a>

                <a href="{{ route('events.index', ['tab' => 'anteriores']) }}" 
                    class="px-3.5 sm:px-6 py-2 rounded-full text-xs sm:text-sm font-semibold transition-all duration-200 flex items-center gap-1.5 sm:gap-2 {{ $tab === 'anteriores' ? 'bg-[#18233f] text-white shadow-md border border-purple-500/30 text-purple-300' : 'text-slate-400 hover:text-slate-200' }}">
                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Anteriores ({{ $pastCount }})</span>
                </a>
            </div>
        </div>

        <!-- Events List -->
        @if ($events->isEmpty())
            <div class="glass-panel rounded-3xl p-8 sm:p-12 text-center border border-slate-800/80 max-w-lg mx-auto my-8 sm:my-12">
                <div class="h-16 w-16 sm:h-20 sm:w-20 mx-auto rounded-3xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 mb-4 sm:mb-5 shadow-[0_0_30px_rgba(0,240,255,0.2)]">
                    <svg class="h-8 w-8 sm:h-10 sm:w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-white mb-2">Nenhum evento {{ $tab === 'anteriores' ? 'anterior' : 'agendado' }}</h3>
                <p class="text-xs sm:text-sm text-slate-400 mb-6">Comece criando um evento incrível e compartilhe o link de confirmação diretamente com seus convidados.</p>
                <a href="{{ route('events.create') }}" class="btn-pds-primary inline-flex items-center gap-2 px-6 py-3 rounded-2xl text-white font-bold text-xs sm:text-sm">
                    + Criar Primeiro Evento
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-3 sm:gap-4">
                @foreach ($events as $event)
                    <div onclick="window.location.href='{{ route('events.show', $event) }}'" 
                        class="glass-card rounded-2xl p-4 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 border border-slate-800/80 hover:border-cyan-500/40 cursor-pointer transition-all duration-200 hover:scale-[1.005] hover:shadow-[0_0_25px_rgba(0,240,255,0.15)] group" data-tilt>
                        <div class="space-y-2 flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <a href="{{ route('events.show', $event) }}" class="text-base sm:text-xl font-bold text-white hover:text-cyan-400 transition-colors truncate max-w-full">
                                    {{ $event->title }}
                                </a>
                                @if (!$event->isRsvpOpen())
                                    <span class="text-[9px] sm:text-[10px] uppercase font-bold px-2 py-0.5 rounded-full bg-rose-500/20 text-rose-300 border border-rose-500/40 shrink-0">
                                        Prazo encerrado
                                    </span>
                                @else
                                    <span class="text-[9px] sm:text-[10px] uppercase font-bold px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 shrink-0">
                                        Confirmando
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs text-slate-400">
                                <span class="flex items-center gap-1.5 text-cyan-300 font-medium">
                                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    {{ $event->totalAttendeesCount() }} confirmado(s)
                                </span>

                                <span class="text-slate-600">•</span>

                                <span class="text-slate-400 flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $event->days_until_event }}
                                </span>

                                <span class="text-slate-600">•</span>

                                <span class="text-slate-400 flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5 sm:h-4 sm:w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $event->formatted_event_date }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions & Dropdown Menu (Screenshot 3 - RESPONSIVE) -->
                        <div class="flex items-center justify-between sm:justify-end gap-2 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-800/80" x-data="{ open: false }">
                            <a href="{{ route('events.show', $event) }}" class="flex-1 sm:flex-initial justify-center px-4 py-2 rounded-xl text-xs font-semibold bg-cyan-500/10 hover:bg-cyan-500/20 text-cyan-300 border border-cyan-500/30 transition-all flex items-center gap-1.5">
                                <span>Ver convidados</span>
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>

                            <div class="relative shrink-0" onclick="event.stopPropagation()">
                                <button @click="open = !open" @click.outside="open = false" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="5" r="2"/>
                                        <circle cx="12" cy="12" r="2"/>
                                        <circle cx="12" cy="19" r="2"/>
                                    </svg>
                                </button>

                                <div x-show="open" x-cloak class="absolute right-0 mt-2 w-52 rounded-2xl bg-[#0e1635] border border-slate-700/80 shadow-2xl z-30 py-2 text-xs divide-y divide-slate-800/80">
                                    <div class="py-1">
                                        <a href="{{ $event->public_url }}" target="_blank" class="flex items-center gap-2 px-4 py-2 text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                            <span>Abrir convite público</span>
                                        </a>

                                        <button type="button" @click="navigator.clipboard.writeText('{{ $event->public_url }}'); window.showPdsModal({ title: 'Link Copiado!', message: 'O link do convite foi copiado com sucesso!', type: 'copy' }); open = false;" class="w-full text-left flex items-center gap-2 px-4 py-2 text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                            <span>Copiar link</span>
                                        </button>

                                        <a href="{{ $event->whats_app_share_url }}" target="_blank" class="flex items-center gap-2 px-4 py-2 text-emerald-400 hover:bg-emerald-500/10">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/>
                                            </svg>
                                            <span>Enviar pelo WhatsApp</span>
                                        </a>
                                    </div>

                                    <div class="py-1">
                                        <a href="{{ route('events.edit', $event) }}" class="flex items-center gap-2 px-4 py-2 text-slate-300 hover:bg-slate-800">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Editar dados</span>
                                        </a>

                                        <form method="POST" action="{{ route('events.destroy', $event) }}" @submit.prevent="window.pdsConfirm('Tem certeza que deseja excluir este evento? Todos os convidados serão removidos permanentemente.', () => $el.submit(), 'Excluir Evento', 'Sim, Excluir')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-rose-400 hover:bg-rose-500/10">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Excluir evento</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>

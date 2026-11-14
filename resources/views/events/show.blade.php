<x-app-layout>
    <div class="space-y-6" x-data="{
        addGuestModal: false,
        editGuestModal: false,
        companionsModal: false,
        whatsappModal: false,
        selectedGuest: { id: null, name: '', phone: '', companions: [] },
        newCompanions: [''],
        copyLink(url) {
            navigator.clipboard.writeText(url);
            alert('Link do convite copiado com sucesso! Agora você pode colar no WhatsApp.');
        },
        openEditGuest(guest) {
            this.selectedGuest = JSON.parse(JSON.stringify(guest));
            this.editGuestModal = true;
        },
        openCompanions(guest) {
            this.selectedGuest = JSON.parse(JSON.stringify(guest));
            this.companionsModal = true;
        }
    }">
        <!-- Top Navigation (Screenshot 4: < Convidados +) -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('events.index') }}" class="p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl sm:text-3xl font-black text-white font-display flex items-center gap-2">
                        <span>Convidados</span>
                        <button type="button" @click="addGuestModal = true" class="text-cyan-400 hover:text-cyan-300 text-2xl font-light leading-none p-1 transition-transform hover:scale-110" title="Adicionar Convidado Manualmente">
                            +
                        </button>
                    </h1>
                </div>
            </div>

            <!-- Event Title Badge -->
            <div class="hidden sm:flex items-center gap-2 text-xs text-slate-400 bg-slate-900/80 px-4 py-2 rounded-full border border-slate-800">
                <span class="text-cyan-400 font-bold">{{ $event->title }}</span>
                <span>•</span>
                <span>{{ $event->formatted_event_date }}</span>
            </div>
        </div>

        <!-- Top Action Cards (Screenshot 4: Link, Convite nominal, Lista de presente) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            <!-- 1. Link -->
            <button type="button" @click="copyLink('{{ $event->public_url }}')" 
                class="glass-panel p-4 rounded-2xl flex flex-col items-center justify-center gap-2 border border-purple-500/30 hover:border-purple-400 transition-all hover:scale-[1.02] group">
                <div class="h-10 w-10 rounded-xl bg-purple-500/10 text-purple-400 flex items-center justify-center group-hover:bg-purple-500/20 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-200">Copiar Link</span>
            </button>

            <!-- 2. Convite Nominal / WhatsApp -->
            <button type="button" @click="whatsappModal = true" 
                class="glass-panel p-4 rounded-2xl flex flex-col items-center justify-center gap-2 border border-cyan-500/30 hover:border-cyan-400 transition-all hover:scale-[1.02] group">
                <div class="h-10 w-10 rounded-xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center group-hover:bg-cyan-500/20 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-200">WhatsApp / Nominal</span>
            </button>

            <!-- 3. Abrir Convite Público -->
            <a href="{{ $event->public_url }}" target="_blank" 
                class="glass-panel p-4 rounded-2xl flex flex-col items-center justify-center gap-2 border border-emerald-500/30 hover:border-emerald-400 transition-all hover:scale-[1.02] group">
                <div class="h-10 w-10 rounded-xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center group-hover:bg-emerald-500/20 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-200">Ver Página do Convite</span>
            </a>

            <!-- 4. Exportar CSV / Excel -->
            <a href="{{ route('events.export', $event) }}" 
                class="glass-panel p-4 rounded-2xl flex flex-col items-center justify-center gap-2 border border-amber-500/30 hover:border-amber-400 transition-all hover:scale-[1.02] group">
                <div class="h-10 w-10 rounded-xl bg-amber-500/10 text-amber-400 flex items-center justify-center group-hover:bg-amber-500/20 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </div>
                <span class="text-xs font-bold text-slate-200">Exportar (Excel/CSV)</span>
            </a>
        </div>

        <!-- View Mode Switch: Normal vs Grupo (Screenshot 4) -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 py-2 border-y border-slate-800/80">
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-400 flex items-center gap-1.5">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Visualizar lista como
                </span>

                <div class="inline-flex p-1 rounded-xl bg-slate-900 border border-slate-800">
                    <a href="{{ route('events.show', ['event' => $event, 'view' => 'normal', 'sort' => $sort, 'search' => $search]) }}" 
                        class="px-4 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-all {{ $viewMode !== 'group' ? 'bg-[#18233f] text-cyan-300 shadow border border-cyan-500/30' : 'text-slate-400 hover:text-white' }}">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <span>Normal</span>
                    </a>

                    <a href="{{ route('events.show', ['event' => $event, 'view' => 'group', 'sort' => $sort, 'search' => $search]) }}" 
                        class="px-4 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 transition-all {{ $viewMode === 'group' ? 'bg-[#18233f] text-purple-300 shadow border border-purple-500/30' : 'text-slate-400 hover:text-white' }}">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Grupo</span>
                    </a>
                </div>
            </div>

            <!-- Search and Filter (Screenshot 4) -->
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <!-- Search Box -->
                <form method="GET" action="{{ route('events.show', $event) }}" class="relative flex-1 sm:w-64">
                    <input type="hidden" name="view" value="{{ $viewMode }}">
                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nome..." 
                        class="w-full pl-9 pr-3 py-1.5 bg-slate-900 border border-slate-800 rounded-xl text-xs text-white placeholder-slate-500 focus:ring-1 focus:ring-cyan-500">
                    <svg class="h-4 w-4 text-slate-500 absolute left-2.5 top-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </form>

                <!-- Sort toggle (Data de inscrição vs Nome) -->
                <a href="{{ route('events.show', ['event' => $event, 'view' => $viewMode, 'sort' => $sort === 'name' ? 'recent' : 'name', 'search' => $search]) }}" 
                    class="px-3 py-1.5 rounded-xl bg-slate-900 border border-slate-800 text-xs text-slate-300 hover:text-white flex items-center gap-1.5 shrink-0">
                    <svg class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>{{ $sort === 'name' ? 'Ordem: Nome A-Z' : 'Data de inscrição' }}</span>
                </a>
            </div>
        </div>

        <!-- Guest List Container (Screenshot 4 & 5) -->
        <div class="space-y-3">
            @if ($guests->isEmpty())
                <div class="glass-panel rounded-2xl p-8 text-center border border-slate-800">
                    <div class="text-3xl mb-2">📋</div>
                    <div class="text-base font-bold text-white mb-1">Nenhum convidado confirmado ainda</div>
                    <div class="text-xs text-slate-400 max-w-sm mx-auto mb-4">
                        Compartilhe o link do evento no WhatsApp ou adicione convidados manualmente usando o botão abaixo.
                    </div>
                    <button type="button" @click="addGuestModal = true" class="btn-pds-primary px-5 py-2 rounded-xl text-xs font-bold text-white">
                        + Adicionar Convidado
                    </button>
                </div>
            @else
                @php $index = 1; @endphp
                @foreach ($guests as $guest)
                    @if ($viewMode === 'group')
                        <!-- GROUP VIEW: Main guest with nested companion cards -->
                        <div class="glass-panel rounded-2xl p-4 sm:p-5 border border-slate-800/80 hover:border-slate-700 transition-all space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <!-- Number Badge (Screenshot 4) -->
                                    <div class="h-8 w-8 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-xs font-bold text-slate-300">
                                        {{ $index++ }}
                                    </div>

                                    <div>
                                        <div class="text-sm font-bold text-white flex items-center gap-2">
                                            <span>{{ $guest->name }}</span>
                                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-cyan-500/10 text-cyan-400 border border-cyan-500/20">Titular</span>
                                        </div>
                                        <div class="text-xs text-slate-400 italic">
                                            {{ $guest->time_ago }}
                                            @if($guest->phone) • {{ $guest->phone }} @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- 3-dots action menu (Screenshot 5) -->
                                <div class="relative" x-data="{ openMenu: false }">
                                    <button @click="openMenu = !openMenu" @click.outside="openMenu = false" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                            <circle cx="12" cy="5" r="2"/>
                                            <circle cx="12" cy="12" r="2"/>
                                            <circle cx="12" cy="19" r="2"/>
                                        </svg>
                                    </button>

                                    <!-- Dropdown menu items (Screenshot 5) -->
                                    <div x-show="openMenu" x-cloak class="absolute right-0 mt-1 w-48 rounded-2xl bg-[#0f1738] border border-slate-700 shadow-2xl z-30 py-1.5 text-xs">
                                        <button type="button" @click="openCompanions({{ json_encode($guest->load('companions')) }}); openMenu = false;" class="w-full text-left px-4 py-2.5 flex items-center gap-2 text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                            <span>Acompanhantes ({{ $guest->companions->count() }})</span>
                                        </button>

                                        <button type="button" @click="openEditGuest({{ json_encode($guest) }}); openMenu = false;" class="w-full text-left px-4 py-2.5 flex items-center gap-2 text-slate-300 hover:bg-slate-800 hover:text-white">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            <span>Editar</span>
                                        </button>

                                        <form method="POST" action="{{ route('guests.destroy', $guest) }}" onsubmit="return confirm('Excluir este convidado?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full text-left px-4 py-2.5 flex items-center gap-2 text-rose-400 hover:bg-rose-500/10">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Excluir</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Companions list -->
                            @if ($guest->companions->isNotEmpty())
                                <div class="pl-11 space-y-1.5 pt-1 border-t border-slate-800/60">
                                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Acompanhantes vinculados:</div>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($guest->companions as $comp)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-purple-500/10 border border-purple-500/20 text-purple-300 text-xs">
                                                <span>{{ $comp->name }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- NORMAL VIEW: Row (Matches Screenshot 4) -->
                        <div class="glass-panel rounded-2xl p-4 sm:p-5 flex items-center justify-between border border-slate-800/80 hover:border-slate-700 transition-all">
                            <div class="flex items-center gap-3">
                                <!-- Number Badge (Screenshot 4) -->
                                <div class="h-8 w-8 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-xs font-bold text-slate-300">
                                    {{ $index++ }}
                                </div>

                                <div>
                                    <div class="text-sm font-bold text-white flex items-center gap-2">
                                        <span>{{ $guest->name }}</span>
                                        @if ($guest->companions->isNotEmpty())
                                            <span class="text-[11px] font-normal text-purple-400">
                                                (+ {{ $guest->companions->count() }} {{ $guest->companions->count() == 1 ? 'acompanhante' : 'acompanhantes' }})
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-400 italic">
                                        {{ $guest->time_ago }}
                                        @if($guest->phone) • {{ $guest->phone }} @endif
                                    </div>
                                </div>
                            </div>

                            <!-- 3-dots action menu (Screenshot 5) -->
                            <div class="relative" x-data="{ openMenu: false }">
                                <button @click="openMenu = !openMenu" @click.outside="openMenu = false" class="p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="5" r="2"/>
                                        <circle cx="12" cy="12" r="2"/>
                                        <circle cx="12" cy="19" r="2"/>
                                    </svg>
                                </button>

                                <!-- Dropdown menu items (Screenshot 5) -->
                                <div x-show="openMenu" x-cloak class="absolute right-0 mt-1 w-48 rounded-2xl bg-[#0f1738] border border-slate-700 shadow-2xl z-30 py-1.5 text-xs">
                                    <button type="button" @click="openCompanions({{ json_encode($guest->load('companions')) }}); openMenu = false;" class="w-full text-left px-4 py-2.5 flex items-center gap-2 text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span>Acompanhantes ({{ $guest->companions->count() }})</span>
                                    </button>

                                    <button type="button" @click="openEditGuest({{ json_encode($guest) }}); openMenu = false;" class="w-full text-left px-4 py-2.5 flex items-center gap-2 text-slate-300 hover:bg-slate-800 hover:text-white">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        <span>Editar</span>
                                    </button>

                                    <form method="POST" action="{{ route('guests.destroy', $guest) }}" onsubmit="return confirm('Excluir este convidado?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-left px-4 py-2.5 flex items-center gap-2 text-rose-400 hover:bg-rose-500/10">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            <span>Excluir</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

            <!-- "+ Adicionar convidado" button (Screenshot 4) -->
            <button type="button" @click="addGuestModal = true" 
                class="w-full py-4 px-6 rounded-2xl border-2 border-dashed border-slate-800 hover:border-cyan-500/50 bg-slate-900/30 hover:bg-cyan-500/5 text-slate-400 hover:text-cyan-300 font-semibold text-sm flex items-center justify-between transition-all">
                <span class="flex items-center gap-2">
                    <span class="text-lg leading-none">+</span>
                    <span>Adicionar convidado</span>
                </span>
                <span class="text-xs text-slate-500 font-normal">Opcional</span>
            </button>
        </div>

        <!-- Bottom Unlimited Plan Counter (Elevated from Screenshot 4: 1 / 20 RESTAM 19 -> ILIMITADO) -->
        <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-30 w-auto">
            <div class="px-6 py-3 rounded-full bg-slate-900/90 backdrop-blur-xl border border-cyan-500/40 shadow-[0_10px_35px_rgba(0,0,0,0.8),0_0_20px_rgba(0,240,255,0.2)] flex items-center gap-4 text-xs">
                <div class="flex items-center gap-2">
                    <span class="font-black text-white text-sm">{{ $totalAttendees }}</span>
                    <span class="text-slate-400">confirmados</span>
                </div>

                <div class="h-4 w-px bg-slate-700"></div>

                <div class="flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-cyan-500/20 text-cyan-300 font-bold border border-cyan-500/40">
                    <span>SEM LIMITE</span>
                    <span class="text-sm">♾️</span>
                </div>

                <a href="{{ route('events.export', $event) }}" class="flex items-center gap-1.5 px-3 py-1 rounded-full bg-gradient-to-r from-cyan-500 to-purple-600 text-white font-bold hover:brightness-110 transition-all">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Baixar Lista</span>
                </a>
            </div>
        </div>

        <!-- ================= MODALS ================= -->

        <!-- 1. Modal Adicionar Convidado Manualmente -->
        <div x-show="addGuestModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            <div @click.outside="addGuestModal = false" class="glass-panel w-full max-w-lg rounded-3xl p-6 sm:p-8 border border-cyan-500/30 shadow-2xl space-y-5">
                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <span class="text-cyan-400">+</span> Adicionar Convidado
                    </h3>
                    <button @click="addGuestModal = false" class="text-slate-400 hover:text-white text-lg">✕</button>
                </div>

                <form method="POST" action="{{ route('guests.store', $event) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                            Nome Completo do Convidado *
                        </label>
                        <input type="text" name="name" required placeholder="Ex: Jessica Aleixo"
                            class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white text-sm focus:ring-cyan-500" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                            Telefone / WhatsApp (Opcional)
                        </label>
                        <input type="text" name="phone" placeholder="(00) 00000-0000"
                            class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white text-sm focus:ring-cyan-500" />
                    </div>

                    <!-- Dynamic Companions -->
                    <div class="space-y-2 pt-2">
                        <div class="flex items-center justify-between">
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">
                                Acompanhantes (Opcional)
                            </label>
                            <button type="button" @click="newCompanions.push('')" class="text-xs text-cyan-400 hover:underline">
                                + Adicionar Acompanhante
                            </button>
                        </div>

                        <template x-for="(comp, i) in newCompanions" :key="i">
                            <div class="flex items-center gap-2">
                                <input type="text" :name="'companions[' + i + ']'" x-model="newCompanions[i]" placeholder="Nome do acompanhante"
                                    class="w-full px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs" />
                                <button type="button" @click="newCompanions.splice(i, 1)" class="p-2 text-rose-400 hover:bg-rose-500/10 rounded-lg">✕</button>
                            </div>
                        </template>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-800">
                        <button type="button" @click="addGuestModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:bg-slate-800">
                            Cancelar
                        </button>
                        <button type="submit" class="btn-pds-primary px-6 py-2.5 rounded-xl text-xs font-bold text-white">
                            Salvar Convidado
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 2. Modal Editar Convidado (Screenshot 5: Editar) -->
        <div x-show="editGuestModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            <div @click.outside="editGuestModal = false" class="glass-panel w-full max-w-md rounded-3xl p-6 sm:p-8 border border-slate-700 shadow-2xl space-y-5">
                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                    <h3 class="text-lg font-bold text-white">Editar Convidado</h3>
                    <button @click="editGuestModal = false" class="text-slate-400 hover:text-white text-lg">✕</button>
                </div>

                <form :action="'/guests/' + selectedGuest.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                            Nome Completo *
                        </label>
                        <input type="text" name="name" x-model="selectedGuest.name" required
                            class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white text-sm" />
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1">
                            Telefone / WhatsApp
                        </label>
                        <input type="text" name="phone" x-model="selectedGuest.phone"
                            class="w-full px-4 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-white text-sm" />
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-800">
                        <button type="button" @click="editGuestModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:bg-slate-800">
                            Cancelar
                        </button>
                        <button type="submit" class="btn-pds-primary px-6 py-2.5 rounded-xl text-xs font-bold text-white">
                            Atualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. Modal Gerenciar Acompanhantes (Screenshot 5: Acompanhantes) -->
        <div x-show="companionsModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            <div @click.outside="companionsModal = false" class="glass-panel w-full max-w-md rounded-3xl p-6 sm:p-8 border border-purple-500/30 shadow-2xl space-y-5">
                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                    <div>
                        <h3 class="text-lg font-bold text-white">Acompanhantes</h3>
                        <p class="text-xs text-purple-300" x-text="'Convidado titular: ' + selectedGuest.name"></p>
                    </div>
                    <button @click="companionsModal = false" class="text-slate-400 hover:text-white text-lg">✕</button>
                </div>

                <!-- List of existing companions -->
                <div class="space-y-2">
                    <template x-if="!selectedGuest.companions || selectedGuest.companions.length === 0">
                        <p class="text-xs text-slate-500 text-center py-4">Nenhum acompanhante cadastrado para este titular.</p>
                    </template>

                    <template x-for="comp in selectedGuest.companions" :key="comp.id">
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-900/80 border border-slate-800">
                            <span class="text-xs font-semibold text-white" x-text="comp.name"></span>
                            <form :action="'/companions/' + comp.id" method="POST" onsubmit="return confirm('Remover este acompanhante?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-rose-400 hover:text-rose-300 p-1">Excluir</button>
                            </form>
                        </div>
                    </template>
                </div>

                <!-- Add companion form -->
                <form :action="'/guests/' + selectedGuest.id + '/companions'" method="POST" class="pt-3 border-t border-slate-800 flex items-center gap-2">
                    @csrf
                    <input type="text" name="name" required placeholder="Novo acompanhante..."
                        class="flex-1 px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-white text-xs" />
                    <button type="submit" class="btn-pds-primary px-4 py-2 rounded-xl text-xs font-bold text-white shrink-0">
                        + Incluir
                    </button>
                </form>
            </div>
        </div>

        <!-- 4. Modal Convite WhatsApp / Nominal -->
        <div x-show="whatsappModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
            <div @click.outside="whatsappModal = false" class="glass-panel w-full max-w-lg rounded-3xl p-6 sm:p-8 border border-emerald-500/30 shadow-2xl space-y-5">
                <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-lg">
                            💬
                        </div>
                        <h3 class="text-lg font-bold text-white">Enviar Convite no WhatsApp</h3>
                    </div>
                    <button @click="whatsappModal = false" class="text-slate-400 hover:text-white text-lg">✕</button>
                </div>

                <div class="space-y-3">
                    <div class="text-xs text-slate-400">
                        Mensagem pré-formatada pronta para disparar para seus contatos e grupos do WhatsApp:
                    </div>

                    <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-800 text-xs font-mono text-slate-200 whitespace-pre-wrap leading-relaxed shadow-inner">
{{ $event->whats_app_share_text }}
                    </div>

                    <div class="pt-3 flex flex-col sm:flex-row gap-3">
                        <a href="{{ $event->whats_app_share_url }}" target="_blank" class="flex-1 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs text-center flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            <span>Abrir e Enviar no WhatsApp</span>
                        </a>

                        <button type="button" @click="navigator.clipboard.writeText(`{{ addslashes($event->whats_app_share_text) }}`); alert('Mensagem copiada para a área de transferência!');" class="py-3 px-4 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold text-xs text-center border border-slate-700">
                            Copiar Mensagem
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

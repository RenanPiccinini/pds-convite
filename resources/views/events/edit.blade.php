<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- Back Navigation & Title -->
        <div class="flex items-center gap-3">
            <a href="{{ route('events.show', $event) }}" class="p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-white font-display">Editar Evento</h1>
                <p class="text-xs text-slate-400">Atualize informações de data, local e regras de confirmação</p>
            </div>
        </div>

        <!-- Event Form -->
        <div class="glass-panel rounded-3xl p-4 sm:p-8 border border-slate-800 shadow-2xl">
            <form method="POST" action="{{ route('events.update', $event) }}" class="space-y-5 sm:space-y-6">
                @csrf
                @method('PUT')

                <!-- Event Title -->
                <div>
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Nome do Evento *
                    </label>
                    <input id="title" type="text" name="title" value="{{ old('title', $event->title) }}" required
                        class="block w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-400 text-base sm:text-sm shadow-inner" />
                    <x-input-error :messages="$errors->get('title')" class="mt-1" />
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Status do Evento
                    </label>
                    <select id="status" name="status" class="block w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-slate-100 text-base sm:text-sm focus:ring-cyan-500/50">
                        <option value="active" {{ old('status', $event->status) === 'active' ? 'selected' : '' }}>Ativo (Recebendo Confirmações)</option>
                        <option value="closed" {{ old('status', $event->status) === 'closed' ? 'selected' : '' }}>Encerrado (Bloquear Novas Confirmações)</option>
                    </select>
                </div>

                <!-- Dates Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Event Date -->
                    <div>
                        <label for="event_date" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Data e Horário do Evento ("Quando") *
                        </label>
                        <input id="event_date" type="datetime-local" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}" required
                            class="block w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-400 text-base sm:text-sm shadow-inner" />
                        <x-input-error :messages="$errors->get('event_date')" class="mt-1" />
                    </div>

                    <!-- RSVP Deadline -->
                    <div>
                        <label for="rsvp_deadline" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Prazo Limite de Confirmação ("Confirmar até") *
                        </label>
                        <input id="rsvp_deadline" type="datetime-local" name="rsvp_deadline" value="{{ old('rsvp_deadline', $event->rsvp_deadline->format('Y-m-d\TH:i')) }}" required
                            class="block w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-slate-100 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-400 text-base sm:text-sm shadow-inner" />
                        <x-input-error :messages="$errors->get('rsvp_deadline')" class="mt-1" />
                    </div>
                </div>

                <!-- Location Address -->
                <div>
                    <label for="location_address" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Endereço Completo ("Onde") *
                    </label>
                    <input id="location_address" type="text" name="location_address" value="{{ old('location_address', $event->location_address) }}" required
                        class="block w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-400 text-base sm:text-sm shadow-inner" />
                    <x-input-error :messages="$errors->get('location_address')" class="mt-1" />
                </div>

                <!-- Location Name & Google Maps URL -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="location_name" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Nome do Local (Opcional)
                        </label>
                        <input id="location_name" type="text" name="location_name" value="{{ old('location_name', $event->location_name) }}"
                            class="block w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-400 text-base sm:text-sm shadow-inner" />
                    </div>

                    <div>
                        <label for="location_map_url" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                            Link do Google Maps (Opcional)
                        </label>
                        <input id="location_map_url" type="url" name="location_map_url" value="{{ old('location_map_url', $event->location_map_url) }}"
                            class="block w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-400 text-base sm:text-sm shadow-inner" />
                    </div>
                </div>

                <!-- Description / Extra details -->
                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Descrição / Traje / Instruções aos convidados (Opcional)
                    </label>
                    <textarea id="description" name="description" rows="3"
                        class="block w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-400 text-base sm:text-sm shadow-inner">{{ old('description', $event->description) }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-3">
                    <a href="{{ route('events.show', $event) }}" class="px-5 py-3 rounded-xl border border-slate-700 text-slate-300 text-xs sm:text-sm font-semibold hover:bg-slate-800 text-center">
                        Cancelar
                    </a>
                    <button type="submit" class="btn-pds-primary px-8 py-3 rounded-xl text-white font-bold text-xs sm:text-sm text-center">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

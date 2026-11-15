<x-app-layout>
    <div class="space-y-6 max-w-4xl mx-auto" x-data="{
        status: '{{ $user->whatsapp_status }}',
        phone: '{{ $user->whatsapp_phone ?? '' }}',
        loadingQr: false,
        qrCodeBase64: null,
        pairingCode: null,
        pollingInterval: null,
        errorMessage: null,

        init() {
            if (this.status === 'connecting') {
                this.loadQrCode();
            } else if (this.status === 'connected') {
                // If connected, occasionally poll or leave as connected
            }
        },

        async loadQrCode() {
            this.loadingQr = true;
            this.errorMessage = null;

            try {
                const response = await fetch('{{ route('whatsapp.qrcode') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.status === 'connected') {
                    this.status = 'connected';
                    this.phone = data.phone || this.phone;
                    this.loadingQr = false;
                    this.stopPolling();
                    return;
                }

                if (data.success && (data.base64 || data.code)) {
                    let base64 = data.base64;
                    if (base64 && !base64.startsWith('data:image')) {
                        base64 = 'data:image/png;base64,' + base64;
                    }
                    this.qrCodeBase64 = base64;
                    this.pairingCode = data.pairingCode || null;
                    this.status = 'connecting';
                    this.loadingQr = false;
                    this.startPolling();
                } else {
                    this.errorMessage = data.message || 'Não foi possível gerar o QR Code. Tente novamente em alguns segundos.';
                    this.loadingQr = false;
                }
            } catch (error) {
                this.errorMessage = 'Erro de comunicação ao carregar o QR Code.';
                this.loadingQr = false;
            }
        },

        startPolling() {
            if (this.pollingInterval) clearInterval(this.pollingInterval);

            this.pollingInterval = setInterval(async () => {
                try {
                    const response = await fetch('{{ route('whatsapp.status') }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (data.status === 'connected') {
                        this.stopPolling();
                        this.status = 'connected';
                        this.phone = data.phone || this.phone;
                        this.qrCodeBase64 = null;
                        if (window.triggerConfetti) {
                            window.triggerConfetti();
                        }
                    }
                } catch (e) {
                    console.error('Polling error', e);
                }
            }, 3000);
        },

        stopPolling() {
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval);
                this.pollingInterval = null;
            }
        },

        async disconnectWhatsApp() {
            window.pdsConfirm(
                'Tem certeza que deseja desconectar o WhatsApp? Seus convidados deixarão de receber mensagens automáticas até que você reconecte.',
                async () => {
                    try {
                        const response = await fetch('{{ route('whatsapp.disconnect') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();
                        if (data.success) {
                            this.status = 'disconnected';
                            this.phone = '';
                            this.qrCodeBase64 = null;
                            this.stopPolling();
                        }
                    } catch (e) {
                        window.location.reload();
                    }
                },
                'Desconectar WhatsApp',
                'Sim, Desconectar'
            );
        }
    }">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-white font-display tracking-tight flex items-center gap-2.5">
                    <span class="text-emerald-400">
                        <svg class="h-8 w-8 inline-block" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    </span>
                    <span>Conexão WhatsApp</span>
                </h1>
                <p class="text-xs text-slate-400 mt-1">
                    Conecte seu WhatsApp para que seus convidados recebam confirmações automáticas instantâneas ao confirmarem presença.
                </p>
            </div>

            <!-- Status Indicator Badge -->
            <div class="flex items-center gap-2">
                <template x-if="status === 'connected'">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/40 text-emerald-400 text-xs font-bold shadow-[0_0_15px_rgba(16,185,129,0.25)] animate-pulse">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        <span>CONECTADO</span>
                    </span>
                </template>
                <template x-if="status === 'connecting'">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-500/10 border border-amber-500/40 text-amber-400 text-xs font-bold">
                        <span class="h-2 w-2 rounded-full bg-amber-400 animate-ping"></span>
                        <span>AGUARDANDO LEITURA</span>
                    </span>
                </template>
                <template x-if="status === 'disconnected'">
                    <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-800 border border-slate-700 text-slate-400 text-xs font-semibold">
                        <span class="h-2 w-2 rounded-full bg-slate-500"></span>
                        <span>NÃO CONECTADO</span>
                    </span>
                </template>
            </div>
        </div>

        @if (!$isConfigured)
            <!-- Warning if Evolution API is not yet configured in .env -->
            <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs space-y-1">
                <div class="font-bold flex items-center gap-2">
                    <span>⚠️ Configuração da Evolution API Pendente</span>
                </div>
                <p class="text-slate-300">
                    A URL ou a API Key da Evolution API ainda não foram configuradas nas variáveis de ambiente. Defina <code class="bg-black/40 px-1 py-0.5 rounded text-amber-200">EVOLUTION_API_URL</code> e <code class="bg-black/40 px-1 py-0.5 rounded text-amber-200">EVOLUTION_API_KEY</code> no seu arquivo <strong>.env</strong> para permitir conexões ativas.
                </p>
            </div>
        @endif

        <!-- ================= STATE 1: CONNECTED ================= -->
        <template x-if="status === 'connected'">
            <div class="space-y-5 animate-fadeIn">
                <div class="glass-card rounded-3xl p-6 sm:p-8 border border-emerald-500/40 shadow-[0_0_40px_rgba(16,185,129,0.15)] space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-800 pb-6">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 rounded-2xl bg-emerald-500/20 border-2 border-emerald-400 flex items-center justify-center text-emerald-400 text-3xl shadow-[0_0_20px_rgba(16,185,129,0.3)]">
                                ✓
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <span>WhatsApp Ativo e Conectado</span>
                                </h3>
                                <p class="text-xs text-slate-300 mt-0.5" x-text="phone ? 'Número conectado: +' + phone : 'Sessão ativa e sincronizada'"></p>
                                <p class="text-[11px] text-emerald-400/90 font-mono mt-1">Instância: {{ $user->whatsapp_instance ?? ('pds_user_' . $user->id) }}</p>
                            </div>
                        </div>

                        <button type="button" @click="disconnectWhatsApp()" class="px-4 py-2.5 rounded-xl border border-rose-500/40 text-rose-400 hover:bg-rose-500/10 text-xs font-bold transition-colors flex items-center justify-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            <span>Desconectar WhatsApp</span>
                        </button>
                    </div>

                    <!-- Details & Features -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-1">
                            <div class="text-emerald-400 text-base">⚡️ Em Tempo Real</div>
                            <div class="text-xs font-bold text-white">Disparo Instantâneo</div>
                            <div class="text-[11px] text-slate-400">Assim que o convidado confirma, a mensagem chega no celular dele.</div>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-1">
                            <div class="text-cyan-400 text-base">📍 Informações Completas</div>
                            <div class="text-xs font-bold text-white">Dados do Evento</div>
                            <div class="text-[11px] text-slate-400">A mensagem inclui nome, data, horário, endereço e link do Maps.</div>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-900/80 border border-slate-800 space-y-1">
                            <div class="text-purple-400 text-base">🔒 Conexão Individual</div>
                            <div class="text-xs font-bold text-white">Seu Próprio Número</div>
                            <div class="text-[11px] text-slate-400">As mensagens saem diretamente do número conectado nesta conta.</div>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <!-- ================= STATE 2: DISCONNECTED OR CONNECTING ================= -->
        <template x-if="status !== 'connected'">
            <div class="space-y-6">
                <!-- QR Code Box -->
                <div class="glass-panel rounded-3xl p-6 sm:p-10 border border-slate-800 text-center space-y-6 max-w-2xl mx-auto shadow-2xl">
                    <div class="space-y-2">
                        <h2 class="text-xl sm:text-2xl font-black text-white font-display">
                            Vincular WhatsApp ao Sistema
                        </h2>
                        <p class="text-xs text-slate-400 max-w-md mx-auto">
                            Abra o WhatsApp no seu celular e escaneie o código abaixo para ativar o envio automático de confirmações.
                        </p>
                    </div>

                    <!-- Error Alert -->
                    <template x-if="errorMessage">
                        <div class="p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs">
                            <span x-text="errorMessage"></span>
                        </div>
                    </template>

                    <!-- QR Code Area -->
                    <div class="flex flex-col items-center justify-center min-h-[260px]">
                        <!-- 1. Generating Spinner -->
                        <template x-if="loadingQr">
                            <div class="space-y-4 py-8">
                                <div class="h-12 w-12 mx-auto border-4 border-cyan-500 border-t-transparent rounded-full animate-spin"></div>
                                <div class="text-xs text-cyan-300 font-semibold animate-pulse">
                                    Conectando à Evolution API e gerando QR Code...
                                </div>
                            </div>
                        </template>

                        <!-- 2. QR Code Image Display -->
                        <template x-if="!loadingQr && qrCodeBase64">
                            <div class="space-y-4 animate-scaleUp">
                                <div class="p-4 bg-white rounded-3xl shadow-2xl inline-block border-4 border-cyan-400/40">
                                    <img :src="qrCodeBase64" alt="WhatsApp QR Code" class="h-56 w-56 sm:h-64 sm:w-64 object-contain rounded-xl" />
                                </div>

                                <div class="flex items-center justify-center gap-2 text-xs text-emerald-400 font-medium animate-pulse">
                                    <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                                    <span>Aguardando leitura do QR Code pelo celular...</span>
                                </div>

                                <template x-if="pairingCode">
                                    <div class="p-3 bg-slate-900 border border-slate-700 rounded-2xl text-xs text-slate-300 space-y-1">
                                        <div class="text-[11px] text-slate-400">Ou conecte com o código de pareamento:</div>
                                        <div class="font-mono text-base font-bold text-cyan-400 tracking-widest" x-text="pairingCode"></div>
                                    </div>
                                </template>

                                <div>
                                    <button type="button" @click="loadQrCode()" class="text-xs text-slate-400 hover:text-cyan-400 underline transition-colors">
                                        Recarregar novo QR Code
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- 3. Start Connection CTA Button -->
                        <template x-if="!loadingQr && !qrCodeBase64">
                            <div class="space-y-4 py-6">
                                <div class="h-20 w-20 mx-auto rounded-full bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-3xl shadow-[0_0_30px_rgba(16,185,129,0.2)]">
                                    <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                </div>
                                <button type="button" @click="loadQrCode()" class="btn-pds-primary px-8 py-3.5 rounded-2xl text-white font-bold text-xs sm:text-sm shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all">
                                    Conectar WhatsApp & Gerar QR Code
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- Step-by-Step Instructions -->
                    <div class="pt-6 border-t border-slate-800 text-left space-y-3">
                        <div class="text-xs font-bold uppercase tracking-wider text-slate-300">Como conectar:</div>
                        <ol class="space-y-2 text-xs text-slate-400 list-decimal list-inside leading-relaxed">
                            <li>Abra o aplicativo do <strong class="text-white">WhatsApp</strong> no seu celular.</li>
                            <li>Toque no ícone de <strong class="text-white">Mais opções (⋮)</strong> no Android ou em <strong class="text-white">Configurações</strong> no iPhone.</li>
                            <li>Selecione <strong class="text-white">Aparelhos conectados</strong> e depois toque em <strong class="text-white">Conectar um aparelho</strong>.</li>
                            <li>Aponte a câmera do celular para esta tela para escanear o QR Code acima.</li>
                            <li>Assim que escaneado, esta página <strong class="text-cyan-400">atualizará automaticamente</strong> indicando que a conexão foi estabelecida com sucesso.</li>
                        </ol>
                    </div>
                </div>
            </div>
        </template>
    </div>
</x-app-layout>

<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center gap-3">
            <a href="{{ route('events.index') }}" class="p-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-400 hover:text-white transition-colors" title="Voltar para eventos">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-black text-white font-display">Configurações da Conta</h1>
                <p class="text-xs text-slate-400">Gerencie seus dados de acesso, senha e preferências de organizador</p>
            </div>
        </div>

        <!-- 1. Profile Information -->
        <div class="glass-panel rounded-3xl p-5 sm:p-8 border border-slate-800 shadow-2xl space-y-5">
            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                    <span class="text-cyan-400">👤</span> Informações Pessoais
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Atualize seu nome completo e endereço de e-mail de acesso
                </p>
            </div>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-4 max-w-xl">
                @csrf
                @method('patch')

                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Nome Completo
                    </label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                        class="block w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-400 text-base sm:text-sm shadow-inner" />
                    <x-input-error class="mt-1" :messages="$errors->get('name')" />
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        E-mail de Login
                    </label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                        class="block w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-400 text-base sm:text-sm shadow-inner" />
                    <x-input-error class="mt-1" :messages="$errors->get('email')" />
                </div>

                <div class="pt-2 flex items-center gap-4">
                    <button type="submit" class="btn-pds-primary px-6 py-2.5 rounded-xl text-white font-bold text-xs sm:text-sm">
                        Salvar Informações
                    </button>

                    @if (session('status') === 'profile-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-xs text-cyan-400 font-semibold flex items-center gap-1">
                            <span>✓</span> <span>Salvo com sucesso!</span>
                        </p>
                    @endif
                </div>
            </form>
        </div>

        <!-- 2. Update Password -->
        <div class="glass-panel rounded-3xl p-5 sm:p-8 border border-slate-800 shadow-2xl space-y-5">
            <div class="border-b border-slate-800 pb-4">
                <h2 class="text-base sm:text-lg font-bold text-white flex items-center gap-2">
                    <span class="text-purple-400">🔒</span> Alterar Senha
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Garanta que sua conta esteja protegida utilizando uma senha segura
                </p>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="space-y-4 max-w-xl">
                @csrf
                @method('put')

                <div>
                    <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Senha Atual
                    </label>
                    <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                        class="block w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-400 text-base sm:text-sm shadow-inner" />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1" />
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Nova Senha
                    </label>
                    <input id="password" name="password" type="password" autocomplete="new-password"
                        class="block w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-400 text-base sm:text-sm shadow-inner" />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1" />
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-1.5">
                        Confirmar Nova Senha
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                        class="block w-full px-4 py-3 bg-slate-900/80 border border-slate-700 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-400 text-base sm:text-sm shadow-inner" />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1" />
                </div>

                <div class="pt-2 flex items-center gap-4">
                    <button type="submit" class="btn-pds-primary px-6 py-2.5 rounded-xl text-white font-bold text-xs sm:text-sm">
                        Atualizar Senha
                    </button>

                    @if (session('status') === 'password-updated')
                        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-xs text-purple-400 font-semibold flex items-center gap-1">
                            <span>✓</span> <span>Senha alterada com sucesso!</span>
                        </p>
                    @endif
                </div>
            </form>
        </div>

        <!-- 3. Delete Account -->
        <div class="glass-panel rounded-3xl p-5 sm:p-8 border border-rose-500/30 shadow-2xl space-y-4" x-data="{ confirmDeleteModal: false }">
            <div class="border-b border-slate-800 pb-3">
                <h2 class="text-base sm:text-lg font-bold text-rose-400 flex items-center gap-2">
                    <span>⚠️</span> Excluir Conta
                </h2>
                <p class="text-xs text-slate-400 mt-1">
                    Ao excluir sua conta, todos os seus eventos, convidados e confirmações de presença serão permanentemente removidos.
                </p>
            </div>

            <div>
                <button type="button" @click="confirmDeleteModal = true" class="px-5 py-2.5 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 font-bold text-xs sm:text-sm transition-all">
                    Excluir Minha Conta Permanentemente
                </button>
            </div>

            <!-- Delete confirmation modal -->
            <div x-show="confirmDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/85 backdrop-blur-md">
                <div @click.outside="confirmDeleteModal = false" class="glass-panel w-full max-w-md rounded-3xl p-5 sm:p-8 border border-rose-500/40 shadow-2xl space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <h3 class="text-base sm:text-lg font-bold text-rose-400">Confirmar Exclusão</h3>
                        <button @click="confirmDeleteModal = false" class="text-slate-400 hover:text-white text-lg p-1">✕</button>
                    </div>

                    <p class="text-xs text-slate-300 leading-relaxed">
                        Tem certeza absoluta que deseja excluir sua conta? Esta ação é irreversível. Por favor, digite sua senha atual para confirmar:
                    </p>

                    <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                        @csrf
                        @method('delete')

                        <div>
                            <input id="delete_password" name="password" type="password" required placeholder="Digite sua senha atual"
                                class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white text-base sm:text-sm focus:ring-rose-500 focus:border-rose-400" />
                            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1" />
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button" @click="confirmDeleteModal = false" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-400 hover:bg-slate-800">
                                Cancelar
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs shadow-lg shadow-rose-600/30">
                                Sim, Excluir Minha Conta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
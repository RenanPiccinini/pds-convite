<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white font-display tracking-tight">
            Acessar seus eventos
        </h1>
        <p class="text-xs text-slate-400 mt-2 max-w-xs mx-auto">
            Gerencie seus convites, confirme presenças e envie links via WhatsApp
        </p>
    </div>

    <!-- Formulário Exclusivo de Login (Apenas campos e botão Entrar) -->
    <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                E-mail
            </label>
            <div class="relative rounded-2xl group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-cyan-400 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="seu@email.com"
                    class="block w-full pl-12 pr-4 py-3.5 bg-slate-900/60 border border-slate-700/60 rounded-2xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-400 text-base sm:text-sm transition-all shadow-inner" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                Sua senha
            </label>
            <div class="relative rounded-2xl group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-purple-400 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                    </svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                    class="block w-full pl-12 pr-4 py-3.5 bg-slate-900/60 border border-slate-700/60 rounded-2xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-400 text-base sm:text-sm transition-all shadow-inner" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-xs pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer select-none py-1.5 touch-manipulation">
                <input id="remember_me" type="checkbox" class="w-4 h-4 rounded bg-slate-800 border-slate-700 text-cyan-500 shadow-sm focus:ring-cyan-500/30 cursor-pointer" name="remember">
                <span class="ms-2 text-slate-400 hover:text-slate-300">Lembrar de mim</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-cyan-400 hover:text-cyan-300 font-medium transition-colors py-1.5 px-1 touch-manipulation" href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        <!-- Main Submit Button: Entrar -->
        <div class="pt-2">
            <button type="submit" class="w-full min-h-[48px] py-3.5 px-4 rounded-2xl text-white font-bold tracking-wide text-base sm:text-sm bg-gradient-to-r from-cyan-500 via-blue-600 to-purple-600 hover:from-cyan-400 hover:via-blue-500 hover:to-purple-500 shadow-[0_4px_25px_rgba(0,240,255,0.4)] transition-all duration-300 hover:scale-[1.01] active:scale-[0.98] border border-cyan-300/30 flex items-center justify-center cursor-pointer touch-manipulation select-none">
                Entrar
            </button>
        </div>
    </form>

    <!-- Fora do Formulário: Botão Direto para o Cadastro (Impossibilita submit acidental no mobile) -->
    <div class="pt-4">
        <a href="{{ route('register') }}" class="w-full min-h-[48px] py-3 px-4 rounded-2xl text-slate-200 hover:text-white font-semibold text-sm bg-slate-900/80 hover:bg-slate-800/90 border border-slate-700/80 hover:border-cyan-500/50 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer touch-manipulation select-none shadow-sm active:scale-[0.98]">
            <span>Não tem uma conta?</span>
            <span class="text-cyan-400 font-bold">Cadastrar-se Grátis</span>
        </a>
    </div>

    <!-- Help / Support Links -->
    <div class="pt-3 flex flex-wrap items-center justify-center gap-x-4 gap-y-2 text-xs text-slate-400 text-center">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="py-1.5 px-1 hover:text-cyan-400 transition-colors touch-manipulation">Recuperar senha</a>
            <span class="text-slate-600 hidden sm:inline">|</span>
        @endif
        <a href="https://wa.me/?text=Olá,%20preciso%20de%20ajuda%20com%20o%20PDS%20Convite" target="_blank" class="py-1.5 px-1 hover:text-cyan-400 transition-colors touch-manipulation flex items-center gap-1.5">
            <span>💬</span>
            <span>Suporte no WhatsApp</span>
        </a>
    </div>
</x-guest-layout>

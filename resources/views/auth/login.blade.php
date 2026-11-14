<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white font-display tracking-tight">
            Acessar seus eventos
        </h1>
        <p class="text-xs text-slate-400 mt-2">
            Gerencie seus convites, confirme presenças e envie links via WhatsApp
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
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

        <!-- Remember Me -->
        <div class="flex items-center justify-between text-xs">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded bg-slate-800 border-slate-700 text-cyan-500 shadow-sm focus:ring-cyan-500/30" name="remember">
                <span class="ms-2 text-slate-400 hover:text-slate-300">Lembrar de mim</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-cyan-400 hover:text-cyan-300 transition-colors" href="{{ route('password.request') }}">
                    Esqueceu a senha?
                </a>
            @endif
        </div>

        <div>
            <button type="submit" class="w-full py-3.5 px-4 rounded-2xl text-white font-bold tracking-wide text-sm bg-gradient-to-r from-cyan-500 via-blue-600 to-purple-600 hover:from-cyan-400 hover:via-blue-500 hover:to-purple-500 shadow-[0_4px_25px_rgba(0,240,255,0.4)] transition-all duration-300 hover:scale-[1.01] active:scale-[0.99] border border-cyan-300/30">
                Entrar
            </button>
        </div>

        <!-- Divider "ou" -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-slate-800"></div>
            </div>
            <div class="relative flex justify-center text-xs uppercase">
                <span class="bg-[#0b1022] px-3 text-slate-500 font-semibold tracking-wider">ou</span>
            </div>
        </div>

        <!-- Google Login Button (Screenshot 2) -->
        <div>
            <button type="button" onclick="window.showPdsModal({ title: 'Login com Google', message: 'A autenticação via Google está disponível para contas cadastradas.', type: 'info' })" class="w-full flex items-center justify-center gap-3 py-3 px-4 rounded-2xl bg-slate-900/80 hover:bg-slate-800/90 border border-slate-700/80 text-white font-semibold text-sm transition-all duration-200 hover:border-slate-500 shadow-md">
                <svg class="h-5 w-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" />
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" />
                </svg>
                <span>Fazer Login com o Google</span>
            </button>
        </div>

        <!-- Footer Links (Screenshot 2: Criar uma conta | Recuperar senha | Ajuda) -->
        <div class="pt-4 flex items-center justify-center gap-3 text-xs text-slate-400">
            <a href="{{ route('register') }}" class="hover:text-cyan-400 transition-colors">Registrar / Criar conta</a>
            <span class="text-slate-600">|</span>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="hover:text-cyan-400 transition-colors">Recuperar senha</a>
                <span class="text-slate-600">|</span>
            @endif
            <a href="https://wa.me/?text=Olá,%20preciso%20de%20ajuda%20com%20o%20PDS%20Convite" target="_blank" class="hover:text-cyan-400 transition-colors">Ajuda</a>
        </div>
    </form>
</x-guest-layout>

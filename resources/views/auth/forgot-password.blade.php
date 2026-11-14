<x-guest-layout>
    <div class="text-center mb-6">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white font-display tracking-tight">
            Recuperar Senha
        </h1>
        <p class="text-xs text-slate-400 mt-2 max-w-xs mx-auto">
            Informe seu e-mail cadastrado e enviaremos um link para você redefinir sua senha com segurança.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                E-mail
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="seu@email.com"
                class="block w-full px-4 py-3.5 bg-slate-900/60 border border-slate-700/60 rounded-2xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-400 text-base sm:text-sm transition-all shadow-inner" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full min-h-[48px] py-3.5 px-4 rounded-2xl text-white font-bold tracking-wide text-xs sm:text-sm bg-gradient-to-r from-cyan-500 via-blue-600 to-purple-600 hover:from-cyan-400 hover:via-blue-500 hover:to-purple-500 shadow-[0_4px_25px_rgba(0,240,255,0.4)] transition-all duration-300 hover:scale-[1.01] active:scale-[0.98] border border-cyan-300/30 flex items-center justify-center cursor-pointer touch-manipulation select-none">
                Enviar Link de Recuperação
            </button>
        </div>

        <div class="pt-1">
            <a href="{{ route('login') }}" class="w-full min-h-[48px] py-3 px-4 rounded-2xl text-slate-200 hover:text-white font-semibold text-sm bg-slate-900/80 hover:bg-slate-800/90 border border-slate-700/80 hover:border-cyan-500/50 transition-all duration-200 flex items-center justify-center gap-2 cursor-pointer touch-manipulation select-none shadow-sm active:scale-[0.98]">
                ← Voltar para o login
            </a>
        </div>
    </form>
</x-guest-layout>

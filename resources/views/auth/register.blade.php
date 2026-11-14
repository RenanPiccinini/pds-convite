<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-white font-display tracking-tight">
            Criar conta
        </h1>
        <p class="text-xs text-slate-400 mt-2">
            Comece a criar seus eventos no PDS Convite sem limite de convidados!
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                Seu Nome Completo
            </label>
            <div class="relative rounded-2xl group">
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Ex: Roberto Silva"
                    class="block w-full px-4 py-3 bg-slate-900/60 border border-slate-700/60 rounded-2xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-400 text-base sm:text-sm transition-all shadow-inner" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                E-mail
            </label>
            <div class="relative rounded-2xl group">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="seu@email.com"
                    class="block w-full px-4 py-3 bg-slate-900/60 border border-slate-700/60 rounded-2xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/50 focus:border-cyan-400 text-base sm:text-sm transition-all shadow-inner" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                Senha
            </label>
            <div class="relative rounded-2xl group">
                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres"
                    class="block w-full px-4 py-3 bg-slate-900/60 border border-slate-700/60 rounded-2xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-400 text-base sm:text-sm transition-all shadow-inner" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-1.5">
                Confirmar Senha
            </label>
            <div class="relative rounded-2xl group">
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repita a senha"
                    class="block w-full px-4 py-3 bg-slate-900/60 border border-slate-700/60 rounded-2xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-400 text-base sm:text-sm transition-all shadow-inner" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3.5 px-4 rounded-2xl text-white font-bold tracking-wide text-sm bg-gradient-to-r from-cyan-500 via-blue-600 to-purple-600 hover:from-cyan-400 hover:via-blue-500 hover:to-purple-500 shadow-[0_4px_25px_rgba(0,240,255,0.4)] transition-all duration-300 hover:scale-[1.01] active:scale-[0.99] border border-cyan-300/30">
                Cadastrar e Criar Eventos
            </button>
        </div>

        <div class="pt-4 text-center text-xs text-slate-400">
            Já possui uma conta?
            <a href="{{ route('login') }}" class="text-cyan-400 font-semibold hover:underline ms-1">
                Entrar
            </a>
        </div>
    </form>
</x-guest-layout>

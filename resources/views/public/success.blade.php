<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Presença Confirmada - {{ $event->title }} | PDS Convite</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#060814] text-slate-100 min-h-screen flex items-center justify-center p-4">
    <div class="glass-panel w-full max-w-md rounded-3xl p-8 border border-cyan-400/40 text-center space-y-6">
        <div class="h-20 w-20 mx-auto rounded-full bg-emerald-500/20 border-2 border-emerald-400 flex items-center justify-center text-emerald-400 text-3xl">
            ✓
        </div>
        <div>
            <h1 class="text-2xl font-black text-white">Presença Confirmada!</h1>
            <p class="text-xs text-slate-400 mt-1">Sua confirmação para {{ $event->title }} foi registrada.</p>
        </div>

        @if($guest)
            <div class="p-4 rounded-2xl bg-slate-900 border border-slate-800 text-left text-xs space-y-2">
                <div><span class="text-slate-400">Nome:</span> <strong class="text-white">{{ $guest->name }}</strong></div>
                <div><span class="text-slate-400">Quando:</span> <span class="text-slate-200">{{ $event->formatted_event_date }}</span></div>
                <div><span class="text-slate-400">Onde:</span> <span class="text-slate-200">{{ $event->location_address }}</span></div>
            </div>
        @endif

        <div class="space-y-2">
            <a href="{{ $event->google_calendar_url }}" target="_blank" class="block w-full py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-semibold">
                📅 Adicionar ao Google Agenda
            </a>
            <a href="{{ $event->map_url }}" target="_blank" class="block w-full py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-xs font-semibold">
                📍 Ver Local no Mapa
            </a>
            <a href="{{ $event->public_url }}" class="block w-full py-3 rounded-xl btn-pds-primary text-xs font-bold">
                Voltar ao Convite
            </a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (window.triggerConfetti) window.triggerConfetti();
        });
    </script>
</body>
</html>

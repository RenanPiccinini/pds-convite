<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\EvolutionApiService;
use Illuminate\Http\Request;

class PublicRsvpController extends Controller
{
    protected EvolutionApiService $evolutionService;

    public function __construct(EvolutionApiService $evolutionService)
    {
        $this->evolutionService = $evolutionService;
    }

    public function show(string $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        $isOpen = $event->isRsvpOpen();
        $isPast = $event->isPast();

        return view('public.rsvp', compact('event', 'isOpen', 'isPast'));
    }

    public function confirm(Request $request, string $slug)
    {
        $event = Event::where('slug', $slug)->firstOrFail();

        if (! $event->isRsvpOpen()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'O prazo para confirmação de presença para este evento já foi encerrado.',
                ], 422);
            }

            return redirect()->back()->with('error', 'O prazo de confirmação para este evento já foi encerrado.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ], [
            'name.required' => 'Por favor, informe seu nome completo.',
            'name.min' => 'O nome deve ter no mínimo 3 caracteres.',
        ]);

        // Validação: não permitir que a mesma pessoa confirme presença duas vezes no mesmo evento
        if ($duplicate = $event->findDuplicateGuest($validated['name'], $validated['phone'] ?? null)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $duplicate['message'],
                ], 422);
            }

            return redirect()->back()->withInput()->with('error', $duplicate['message']);
        }

        $guest = $event->guests()->create([
            'name' => trim($validated['name']),
            'phone' => $validated['phone'] ?? null,
            'confirmed_at' => now(),
            'ip_address' => $request->ip(),
        ]);

        // Disparar mensagem no WhatsApp pelo número conectado pelo organizador do evento
        if (! empty($guest->phone) && $event->user && $event->user->isWhatsAppConnected()) {
            try {
                $message = "Olá, *{$guest->name}*! 🎉\n\n"
                    . "Sua presença no evento *{$event->title}* foi confirmada com sucesso!\n\n"
                    . "📅 *Data & Hora:* {$event->formatted_event_date}\n"
                    . "📍 *Local:* {$event->location_address}\n";

                if (! empty($event->map_url)) {
                    $message .= "🗺 *Localização:* {$event->map_url}\n";
                }

                $message .= "\nAgradecemos sua confirmação e esperamos você lá!";

                $this->evolutionService->sendTextMessage(
                    $event->user->whatsapp_instance,
                    $guest->phone,
                    $message
                );
            } catch (\Throwable $e) {
                // Falha silenciosa para não impedir a confirmação do convidado
                \Illuminate\Support\Facades\Log::warning("Erro ao enviar confirmação WhatsApp: " . $e->getMessage());
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Presença confirmada com sucesso!',
                'guest' => [
                    'id' => $guest->id,
                    'name' => $guest->name,
                    'phone' => $guest->phone,
                ],
                'event' => [
                    'title' => $event->title,
                    'date' => $event->formatted_event_date,
                    'address' => $event->location_address,
                    'map_url' => $event->map_url,
                    'calendar_url' => $event->google_calendar_url,
                ],
            ]);
        }

        return redirect()->route('public.rsvp.success', ['slug' => $slug, 'guest' => $guest->id])
            ->with('confirmed_guest', $guest);
    }

    public function success(string $slug, Request $request)
    {
        $event = Event::where('slug', $slug)->firstOrFail();
        $guestId = $request->query('guest');
        $guest = $guestId ? $event->guests()->find($guestId) : null;

        return view('public.success', compact('event', 'guest'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PublicRsvpController extends Controller
{
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

        $guest = $event->guests()->create([
            'name' => trim($validated['name']),
            'phone' => $validated['phone'] ?? null,
            'confirmed_at' => now(),
            'ip_address' => $request->ip(),
        ]);

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

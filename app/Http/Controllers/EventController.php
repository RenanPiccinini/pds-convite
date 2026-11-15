<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'proximos');
        $query = Auth::user()->events();

        if ($tab === 'anteriores') {
            $events = $query->where('event_date', '<', now())->orderBy('event_date', 'desc')->get();
        } else {
            $events = $query->where('event_date', '>=', now()->startOfDay())->orderBy('event_date', 'asc')->get();
        }

        $upcomingCount = Auth::user()->events()->where('event_date', '>=', now()->startOfDay())->count();
        $pastCount = Auth::user()->events()->where('event_date', '<', now()->startOfDay())->count();

        return view('events.index', compact('events', 'tab', 'upcomingCount', 'pastCount'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'event_date' => ['required', 'date'],
            'rsvp_deadline' => ['required', 'date'],
            'location_name' => ['nullable', 'string', 'max:255'],
            'location_address' => ['required', 'string', 'max:500'],
            'location_map_url' => ['nullable', 'url', 'max:1000'],
            'whatsapp_message' => ['nullable', 'string', 'max:1000'],
        ]);

        // Generate unique slug similar to convide.online e.g. 8-char hex or slug-hex
        do {
            $slug = strtolower(Str::random(8));
        } while (Event::where('slug', $slug)->exists());

        $event = Auth::user()->events()->create([
            'slug' => $slug,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'event_date' => $validated['event_date'],
            'rsvp_deadline' => $validated['rsvp_deadline'],
            'location_name' => $validated['location_name'] ?? null,
            'location_address' => $validated['location_address'],
            'location_map_url' => $validated['location_map_url'] ?? null,
            'allow_companions' => false,
            'max_companions' => 0,
            'whatsapp_message' => $validated['whatsapp_message'] ?? null,
            'status' => 'active',
            'is_unlimited' => true,
        ]);

        return redirect()->route('events.show', $event)->with('success', 'Evento criado com sucesso! Link gerado e pronto para envio.');
    }

    public function show(Event $event, Request $request)
    {
        // Authorization
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $viewMode = $request->query('view', 'normal'); // normal or group
        $sort = $request->query('sort', 'recent'); // recent or name
        $search = $request->query('search');

        $guestsQuery = $event->guests();

        if ($search) {
            $guestsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($sort === 'name') {
            $guestsQuery->orderBy('name', 'asc');
        } else {
            $guestsQuery->orderBy('confirmed_at', 'desc');
        }

        $guests = $guestsQuery->get();

        // Calculate statistics
        $totalGuests = $guests->count();
        $totalAttendees = $totalGuests;

        return view('events.show', compact('event', 'guests', 'viewMode', 'sort', 'search', 'totalGuests', 'totalAttendees'));
    }

    public function edit(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'event_date' => ['required', 'date'],
            'rsvp_deadline' => ['required', 'date'],
            'location_name' => ['nullable', 'string', 'max:255'],
            'location_address' => ['required', 'string', 'max:500'],
            'location_map_url' => ['nullable', 'url', 'max:1000'],
            'status' => ['required', 'in:active,closed'],
        ]);

        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'event_date' => $validated['event_date'],
            'rsvp_deadline' => $validated['rsvp_deadline'],
            'location_name' => $validated['location_name'] ?? null,
            'location_address' => $validated['location_address'],
            'location_map_url' => $validated['location_map_url'] ?? null,
            'allow_companions' => false,
            'max_companions' => 0,
            'status' => $validated['status'],
        ]);

        return redirect()->route('events.show', $event)->with('success', 'Evento atualizado com sucesso!');
    }

    public function destroy(Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', 'Evento excluído com sucesso.');
    }

    public function export(Event $event): StreamedResponse
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $guests = $event->guests()->orderBy('name', 'asc')->get();
        $filename = 'convidados_'.Str::slug($event->title).'_'.date('Y-m-d_H-i').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($guests) {
            $handle = fopen('php://output', 'w');
            // Add UTF-8 BOM for Microsoft Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['Nome', 'Telefone', 'Data de confirmação']);

            foreach ($guests as $guest) {
                fputcsv($handle, [
                    $guest->name,
                    $guest->phone ?? '-',
                    $guest->confirmed_at ? $guest->confirmed_at->format('d/m/Y H:i') : '-',
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}

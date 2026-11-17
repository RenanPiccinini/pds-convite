<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    public function store(Request $request, Event $event)
    {
        if ($event->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        if ($duplicate = $event->findDuplicateGuest($validated['name'], $validated['phone'] ?? null)) {
            return redirect()->back()->withInput()->with('error', $duplicate['message']);
        }

        $guest = $event->guests()->create([
            'name' => trim($validated['name']),
            'phone' => $validated['phone'] ?? null,
            'confirmed_at' => now(),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Convidado adicionado com sucesso!');
    }

    public function update(Request $request, Guest $guest)
    {
        if ($guest->event->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        if ($duplicate = $guest->event->findDuplicateGuest($validated['name'], $validated['phone'] ?? null, $guest->id)) {
            return redirect()->back()->withInput()->with('error', $duplicate['message']);
        }

        $guest->update([
            'name' => trim($validated['name']),
            'phone' => $validated['phone'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Dados do convidado atualizados!');
    }

    public function destroy(Guest $guest)
    {
        if ($guest->event->user_id !== Auth::id()) {
            abort(403);
        }

        $guest->delete();

        return redirect()->back()->with('success', 'Convidado removido da lista.');
    }
}

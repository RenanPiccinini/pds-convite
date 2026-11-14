<?php

namespace App\Http\Controllers;

use App\Models\Companion;
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
            'companions' => ['nullable', 'array'],
            'companions.*' => ['nullable', 'string', 'max:255'],
        ]);

        $guest = $event->guests()->create([
            'name' => trim($validated['name']),
            'phone' => $validated['phone'] ?? null,
            'confirmed_at' => now(),
            'ip_address' => $request->ip(),
        ]);

        if (! empty($validated['companions'])) {
            foreach ($validated['companions'] as $compName) {
                $compName = trim($compName);
                if (! empty($compName)) {
                    $guest->companions()->create(['name' => $compName]);
                }
            }
        }

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

    public function addCompanion(Request $request, Guest $guest)
    {
        if ($guest->event->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $guest->companions()->create([
            'name' => trim($validated['name']),
        ]);

        return redirect()->back()->with('success', 'Acompanhante adicionado com sucesso!');
    }

    public function destroyCompanion(Companion $companion)
    {
        if ($companion->guest->event->user_id !== Auth::id()) {
            abort(403);
        }

        $companion->delete();

        return redirect()->back()->with('success', 'Acompanhante removido.');
    }
}

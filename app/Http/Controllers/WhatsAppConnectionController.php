<?php

namespace App\Http\Controllers;

use App\Services\EvolutionApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsAppConnectionController extends Controller
{
    protected EvolutionApiService $evolutionService;

    public function __construct(EvolutionApiService $evolutionService)
    {
        $this->evolutionService = $evolutionService;
    }

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $isConfigured = $this->evolutionService->isConfigured();

        // If user is marked as connected, let's verify if the session is still active
        if ($user->isWhatsAppConnected() && $isConfigured) {
            $state = $this->evolutionService->getConnectionState($user->whatsapp_instance);
            if ($state['state'] !== 'open') {
                $user->whatsapp_status = 'disconnected';
                $user->whatsapp_connected_at = null;
                $user->save();
            } elseif (! empty($state['phone']) && empty($user->whatsapp_phone)) {
                $user->whatsapp_phone = $state['phone'];
                $user->save();
            }
        }

        return view('whatsapp.index', [
            'user' => $user,
            'isConfigured' => $isConfigured,
        ]);
    }

    public function getQrCode()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $this->evolutionService->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'Evolution API não configurada. Defina as variáveis no arquivo .env.',
            ], 422);
        }

        $instanceName = $user->getOrCreateWhatsAppInstanceName();
        $result = $this->evolutionService->createOrConnectInstance($instanceName);

        if (! empty($result['status']) && $result['status'] === 'connected') {
            $user->update([
                'whatsapp_status' => 'connected',
                'whatsapp_connected_at' => now(),
                'whatsapp_phone' => $result['phone'] ?? $user->whatsapp_phone,
            ]);

            return response()->json([
                'success' => true,
                'status' => 'connected',
                'phone' => $user->whatsapp_phone,
            ]);
        }

        if (! empty($result['success'])) {
            $user->update(['whatsapp_status' => 'connecting']);

            return response()->json([
                'success' => true,
                'status' => 'connecting',
                'base64' => $result['base64'] ?? null,
                'code' => $result['code'] ?? null,
                'pairingCode' => $result['pairingCode'] ?? null,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Erro ao obter QR Code da Evolution API.',
        ], 500);
    }

    public function status()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (! $user->whatsapp_instance) {
            return response()->json([
                'status' => 'disconnected',
            ]);
        }

        $state = $this->evolutionService->getConnectionState($user->whatsapp_instance);

        if ($state['state'] === 'open') {
            if ($user->whatsapp_status !== 'connected') {
                $user->whatsapp_status = 'connected';
                $user->whatsapp_connected_at = now();
            }

            if (! empty($state['phone'])) {
                $user->whatsapp_phone = $state['phone'];
            }

            $user->save();

            return response()->json([
                'status' => 'connected',
                'phone' => $user->whatsapp_phone,
            ]);
        }

        return response()->json([
            'status' => $user->whatsapp_status === 'connected' ? 'disconnected' : $user->whatsapp_status,
        ]);
    }

    public function disconnect(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->whatsapp_instance) {
            $this->evolutionService->disconnectInstance($user->whatsapp_instance);
        }

        $user->update([
            'whatsapp_status' => 'disconnected',
            'whatsapp_phone' => null,
            'whatsapp_connected_at' => null,
        ]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('whatsapp.index')->with('success', 'WhatsApp desconectado com sucesso.');
    }
}

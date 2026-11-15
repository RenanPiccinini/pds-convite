<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EvolutionApiService
{
    protected ?string $baseUrl;
    protected ?string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.evolution.url');
        $this->apiKey = config('services.evolution.api_key');
    }

    public function isConfigured(): bool
    {
        return ! empty($this->baseUrl) && ! empty($this->apiKey);
    }

    /**
     * Create an instance or get existing instance QR code
     */
    public function createOrConnectInstance(string $instanceName): array
    {
        if (! $this->isConfigured()) {
            return [
                'success' => false,
                'message' => 'Evolution API não configurada no servidor (.env).',
            ];
        }

        try {
            // Check if instance already exists and get its connection state
            $state = $this->getConnectionState($instanceName);
            if ($state['state'] === 'open') {
                return [
                    'success' => true,
                    'status' => 'connected',
                    'state' => 'open',
                    'phone' => $state['phone'] ?? null,
                ];
            }

            // Request connection / QR code
            $connectResponse = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->timeout(10)->get("{$this->baseUrl}/instance/connect/{$instanceName}");

            if ($connectResponse->successful()) {
                $data = $connectResponse->json();
                $base64 = $data['base64'] ?? $data['qrcode']['base64'] ?? null;
                $code = $data['code'] ?? $data['qrcode']['code'] ?? null;
                $pairingCode = $data['pairingCode'] ?? null;

                if ($base64 || $code) {
                    return [
                        'success' => true,
                        'status' => 'connecting',
                        'base64' => $base64,
                        'code' => $code,
                        'pairingCode' => $pairingCode,
                    ];
                }
            }

            // If instance doesn't exist yet, create it
            $createResponse = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post("{$this->baseUrl}/instance/create", [
                'instanceName' => $instanceName,
                'qrcode' => true,
                'integration' => 'WHATSAPP-BAILEYS',
            ]);

            if ($createResponse->successful()) {
                $data = $createResponse->json();
                $base64 = $data['qrcode']['base64'] ?? $data['base64'] ?? null;
                $code = $data['qrcode']['code'] ?? $data['code'] ?? null;
                $pairingCode = $data['qrcode']['pairingCode'] ?? $data['pairingCode'] ?? null;

                return [
                    'success' => true,
                    'status' => 'connecting',
                    'base64' => $base64,
                    'code' => $code,
                    'pairingCode' => $pairingCode,
                ];
            }

            // Retry connect one more time in case it was created without direct QR code in response
            $retry = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->timeout(10)->get("{$this->baseUrl}/instance/connect/{$instanceName}");

            if ($retry->successful()) {
                $data = $retry->json();
                return [
                    'success' => true,
                    'status' => 'connecting',
                    'base64' => $data['base64'] ?? $data['qrcode']['base64'] ?? null,
                    'code' => $data['code'] ?? $data['qrcode']['code'] ?? null,
                    'pairingCode' => $data['pairingCode'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => 'Não foi possível gerar o QR Code da Evolution API. Verifique a URL e a API Key.',
            ];
        } catch (\Throwable $e) {
            Log::error('Evolution API createOrConnect error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro de conexão com a Evolution API: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Get the connection state of the instance
     */
    public function getConnectionState(string $instanceName): array
    {
        if (! $this->isConfigured()) {
            return ['state' => 'disconnected', 'phone' => null];
        }

        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->timeout(6)->get("{$this->baseUrl}/instance/connectionState/{$instanceName}");

            if ($response->successful()) {
                $data = $response->json();
                $state = $data['instance']['state'] ?? $data['state'] ?? 'close';

                $phone = null;
                if ($state === 'open') {
                    // Try to fetch owner phone
                    $phone = $this->fetchInstancePhone($instanceName);
                }

                return [
                    'state' => $state,
                    'phone' => $phone,
                ];
            }

            return ['state' => 'close', 'phone' => null];
        } catch (\Throwable $e) {
            return ['state' => 'close', 'phone' => null];
        }
    }

    /**
     * Fetch connected phone number for an instance
     */
    public function fetchInstancePhone(string $instanceName): ?string
    {
        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->timeout(5)->get("{$this->baseUrl}/instance/fetchInstances", [
                'instanceName' => $instanceName,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (is_array($data)) {
                    $item = isset($data[0]) ? $data[0] : $data;
                    $owner = $item['owner'] ?? $item['instance']['owner'] ?? null;
                    if ($owner) {
                        return str_replace(['@s.whatsapp.net', '@c.us'], '', $owner);
                    }
                }
            }
        } catch (\Throwable $e) {
            // Ignore phone lookup error
        }

        return null;
    }

    /**
     * Disconnect / Logout instance
     */
    public function disconnectInstance(string $instanceName): bool
    {
        if (! $this->isConfigured()) {
            return false;
        }

        try {
            // Try logout
            Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->timeout(6)->delete("{$this->baseUrl}/instance/logout/{$instanceName}");

            // Also try delete to allow clean re-pairing
            Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->timeout(6)->delete("{$this->baseUrl}/instance/delete/{$instanceName}");

            return true;
        } catch (\Throwable $e) {
            Log::warning("Evolution API disconnect error for {$instanceName}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send text message to a WhatsApp number
     */
    public function sendTextMessage(string $instanceName, string $phone, string $message): bool
    {
        if (! $this->isConfigured()) {
            Log::info("Evolution API not configured. Skipped sending message to {$phone}.");
            return false;
        }

        $formattedPhone = $this->sanitizePhone($phone);
        if (empty($formattedPhone)) {
            Log::warning("Invalid phone number for WhatsApp message: {$phone}");
            return false;
        }

        try {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(12)->post("{$this->baseUrl}/message/sendText/{$instanceName}", [
                'number' => $formattedPhone,
                'text' => $message,
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp message sent successfully to {$formattedPhone} via instance {$instanceName}.");
                return true;
            }

            Log::warning("Failed to send WhatsApp message via {$instanceName}: " . $response->body());
            return false;
        } catch (\Throwable $e) {
            Log::error("Evolution API sendTextMessage exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format and sanitize Brazilian or international phone number
     */
    public function sanitizePhone(string $phone): string
    {
        // Strip everything except numbers
        $numbers = preg_replace('/\D/', '', $phone);

        if (empty($numbers)) {
            return '';
        }

        // If Brazilian number without country code (e.g. 51999998888 or 1199998888)
        if (strlen($numbers) === 10 || strlen($numbers) === 11) {
            $numbers = '55' . $numbers;
        }

        return $numbers;
    }
}

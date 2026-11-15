<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WhatsAppConnectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_whatsapp_page(): void
    {
        $response = $this->get('/whatsapp');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_whatsapp_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/whatsapp');
        $response->assertStatus(200);
        $response->assertSee('Conexão WhatsApp');
        $response->assertSee('Vincular WhatsApp ao Sistema');
    }

    public function test_status_endpoint_returns_status(): void
    {
        $user = User::factory()->create([
            'whatsapp_instance' => 'pds_user_test',
            'whatsapp_status' => 'disconnected',
        ]);

        $response = $this->actingAs($user)->getJson('/whatsapp/status');
        $response->assertStatus(200);
        $response->assertJsonStructure(['status']);
    }

    public function test_disconnect_endpoint_resets_user_whatsapp(): void
    {
        $user = User::factory()->create([
            'whatsapp_instance' => 'pds_user_test',
            'whatsapp_status' => 'connected',
            'whatsapp_phone' => '5551999998888',
        ]);

        $response = $this->actingAs($user)->postJson('/whatsapp/disconnect');
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $user->refresh();
        $this->assertEquals('disconnected', $user->whatsapp_status);
        $this->assertNull($user->whatsapp_phone);
    }

    public function test_rsvp_triggers_whatsapp_message_when_organizer_connected(): void
    {
        Http::fake([
            '*/message/sendText/*' => Http::response(['status' => 'success'], 200),
        ]);

        config([
            'services.evolution.url' => 'https://evolution.example.com',
            'services.evolution.api_key' => 'fake-api-key',
        ]);

        $organizer = User::factory()->create([
            'whatsapp_instance' => 'pds_organizer_99',
            'whatsapp_status' => 'connected',
            'whatsapp_phone' => '555194204240',
        ]);

        $event = Event::create([
            'user_id' => $organizer->id,
            'slug' => 'evento-com-notificacao',
            'title' => 'Aniversário VIP',
            'event_date' => now()->addDays(5),
            'rsvp_deadline' => now()->addDays(3),
            'location_address' => 'Rua das Flores, 123',
            'status' => 'active',
            'is_unlimited' => true,
        ]);

        $response = $this->postJson('/convite/evento-com-notificacao/confirmar', [
            'name' => 'Renan Teste',
            'phone' => '(51) 98888-7777',
        ]);

        $response->assertStatus(200);

        Http::assertSent(function ($request) use ($organizer) {
            return str_contains($request->url(), "/message/sendText/{$organizer->whatsapp_instance}")
                && $request['number'] === '5551988887777'
                && str_contains($request['text'], 'Aniversário VIP');
        });
    }
}

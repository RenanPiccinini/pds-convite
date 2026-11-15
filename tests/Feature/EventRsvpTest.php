<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventRsvpTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_welcome_page(): void
    {
        $response = $this->get('/welcome');
        $response->assertStatus(200);
        $response->assertSee('PDS');
        $response->assertSee('CONVITE');
    }

    public function test_home_redirects_to_login_for_guests(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    public function test_home_redirects_to_dashboard_for_authenticated_users(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');
        $response->assertRedirect(route('dashboard'));
    }

    public function test_user_can_create_event(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/events', [
            'title' => 'Confraternização Irigaray 2026',
            'event_date' => now()->addDays(20)->format('Y-m-d H:i:s'),
            'rsvp_deadline' => now()->addDays(10)->format('Y-m-d H:i:s'),
            'location_address' => 'Rua Edu Chaves, 453 - São João, Porto Alegre',
            'allow_companions' => 1,
            'max_companions' => 5,
        ]);

        $this->assertDatabaseHas('events', [
            'user_id' => $user->id,
            'title' => 'Confraternização Irigaray 2026',
            'is_unlimited' => true,
        ]);

        $event = Event::first();
        $response->assertRedirect(route('events.show', $event));
    }

    public function test_events_index_shows_prazo_encerrado_when_deadline_passed_and_no_feedback_buttons(): void
    {
        $user = User::factory()->create();
        $expiredEvent = Event::create([
            'user_id' => $user->id,
            'slug' => 'evento-expirado-list',
            'title' => 'Evento Expirado Lista',
            'event_date' => now()->addDays(5),
            'rsvp_deadline' => now()->subDay(),
            'location_address' => 'Rua Passada, 100',
            'status' => 'active',
            'is_unlimited' => true,
        ]);

        $response = $this->actingAs($user)->get('/events');
        $response->assertStatus(200);
        $response->assertSee('Prazo encerrado');
        $response->assertDontSee('RSVP Encerrado');
        $response->assertDontSee('Enviar feedback');
        $response->assertDontSee('Central de ajuda');

        $showResponse = $this->actingAs($user)->get(route('events.show', $expiredEvent));
        $showResponse->assertStatus(200);
        $showResponse->assertDontSee('Ver Página');
    }

    public function test_can_view_public_rsvp_page(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'slug' => '96c5a861',
            'title' => 'Confraternização Irigaray 2026',
            'event_date' => now()->addDays(20),
            'rsvp_deadline' => now()->addDays(10),
            'location_address' => 'Rua Edu Chaves, 453',
            'allow_companions' => true,
            'max_companions' => 10,
            'status' => 'active',
            'is_unlimited' => true,
        ]);

        $response = $this->get('/convite/96c5a861');
        $response->assertStatus(200);
        $response->assertSee('Confraternização Irigaray 2026');
        $response->assertSee('Prazo para confirmar');
        $response->assertSee('Confirmar presença');

        // Test convide.online alias /pt/{slug}
        $aliasResponse = $this->get('/pt/96c5a861');
        $aliasResponse->assertStatus(200);
        $aliasResponse->assertSee('Confraternização Irigaray 2026');
    }

    public function test_guest_can_confirm_presence(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'slug' => 'evento-teste',
            'title' => 'Festa de Lançamento PDS',
            'event_date' => now()->addDays(5),
            'rsvp_deadline' => now()->addDays(3),
            'location_address' => 'Av. Paulista, 1000',
            'status' => 'active',
            'is_unlimited' => true,
        ]);

        $response = $this->postJson('/convite/evento-teste/confirmar', [
            'name' => 'Jessica Aleixo',
            'phone' => '(11) 99999-8888',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'guest' => [
                'name' => 'Jessica Aleixo',
            ],
        ]);

        $this->assertDatabaseHas('guests', [
            'event_id' => $event->id,
            'name' => 'Jessica Aleixo',
            'phone' => '(11) 99999-8888',
        ]);
    }

    public function test_rsvp_cannot_be_submitted_after_deadline(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'slug' => 'evento-expirado',
            'title' => 'Evento Expirado',
            'event_date' => now()->subDay(),
            'rsvp_deadline' => now()->subDays(2),
            'location_address' => 'Rua Passada, 100',
            'status' => 'active',
            'is_unlimited' => true,
        ]);

        $response = $this->postJson('/convite/evento-expirado/confirmar', [
            'name' => 'Convidado Atrasado',
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseMissing('guests', [
            'name' => 'Convidado Atrasado',
        ]);
    }

    public function test_organizer_can_export_guest_list_csv(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'slug' => 'evento-export',
            'title' => 'Evento Export',
            'event_date' => now()->addDays(5),
            'rsvp_deadline' => now()->addDays(3),
            'location_address' => 'Rua Teste, 100',
            'status' => 'active',
            'is_unlimited' => true,
        ]);

        $guest = $event->guests()->create([
            'name' => 'Jessica Aleixo',
            'phone' => '(11) 99999-8888',
            'confirmed_at' => now(),
        ]);

        $response = $this->actingAs($user)->get("/events/{$event->id}/export");

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $streamedContent = $response->streamedContent();
        $this->assertStringContainsString('Nome,Telefone,"Data de confirmação"', $streamedContent);
        $this->assertStringContainsString('Jessica Aleixo', $streamedContent);
        $this->assertStringContainsString('(11) 99999-8888', $streamedContent);
        $this->assertStringNotContainsString('Tipo', $streamedContent);
        $this->assertStringNotContainsString('Titular Responsável', $streamedContent);
    }
}

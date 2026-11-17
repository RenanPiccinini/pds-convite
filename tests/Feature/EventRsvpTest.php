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
        $response->assertSee('logo-irigaray.png');
        $response->assertSee('pds-logo.png');
        $response->assertSee('https://piccininidigitalsolutions.com.br/');
        $response->assertSee('Desenvolvido por');

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

    public function test_guest_cannot_confirm_twice_in_same_event_with_same_name(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'slug' => 'evento-duplicidade',
            'title' => 'Evento Teste Duplicidade',
            'event_date' => now()->addDays(5),
            'rsvp_deadline' => now()->addDays(3),
            'location_address' => 'Rua Teste, 100',
            'status' => 'active',
            'is_unlimited' => true,
        ]);

        // Primeira confirmação: sucesso
        $first = $this->postJson('/convite/evento-duplicidade/confirmar', [
            'name' => 'Renan Piccinini',
            'phone' => '(51) 99999-1111',
        ]);
        $first->assertStatus(200);
        $first->assertJson(['success' => true]);

        // Segunda confirmação com o mesmo nome no mesmo evento: bloqueada!
        $second = $this->postJson('/convite/evento-duplicidade/confirmar', [
            'name' => 'Renan Piccinini',
            'phone' => '(51) 98888-2222',
        ]);
        $second->assertStatus(422);
        $second->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('Já existe uma confirmação de presença com este nome completo neste evento', $second->json('message'));

        $this->assertEquals(1, $event->guests()->count());
    }

    public function test_guest_cannot_confirm_twice_in_same_event_with_same_phone(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'slug' => 'evento-duplicidade-telefone',
            'title' => 'Evento Teste Duplicidade Telefone',
            'event_date' => now()->addDays(5),
            'rsvp_deadline' => now()->addDays(3),
            'location_address' => 'Rua Teste, 100',
            'status' => 'active',
            'is_unlimited' => true,
        ]);

        // Primeira confirmação
        $first = $this->postJson('/convite/evento-duplicidade-telefone/confirmar', [
            'name' => 'Jessica Aleixo',
            'phone' => '(51) 99876-5432',
        ]);
        $first->assertStatus(200);

        // Segunda confirmação com nome diferente mas mesmo telefone: bloqueada!
        $second = $this->postJson('/convite/evento-duplicidade-telefone/confirmar', [
            'name' => 'Outra Pessoa',
            'phone' => '51998765432',
        ]);
        $second->assertStatus(422);
        $second->assertJson([
            'success' => false,
        ]);
        $this->assertStringContainsString('Já existe uma confirmação de presença com este número de telefone neste evento', $second->json('message'));

        $this->assertEquals(1, $event->guests()->count());
    }

    public function test_guest_cannot_confirm_twice_with_differently_formatted_phone_or_casing(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'slug' => 'evento-formatos',
            'title' => 'Evento Formatos',
            'event_date' => now()->addDays(5),
            'rsvp_deadline' => now()->addDays(3),
            'location_address' => 'Rua Teste, 100',
            'status' => 'active',
            'is_unlimited' => true,
        ]);

        $this->postJson('/convite/evento-formatos/confirmar', [
            'name' => 'Mariana Albuquerque',
            'phone' => '+55 (51) 99123-4567',
        ])->assertStatus(200);

        // Tentativa com letras minúsculas e espaços extras no nome e telefone só números
        $retry = $this->postJson('/convite/evento-formatos/confirmar', [
            'name' => '  mariana albuquerque ',
            'phone' => '51991234567',
        ]);
        $retry->assertStatus(422);
        $retry->assertJson(['success' => false]);

        $this->assertEquals(1, $event->guests()->count());
    }

    public function test_same_guest_can_confirm_in_different_events(): void
    {
        $user = User::factory()->create();

        $eventA = Event::create([
            'user_id' => $user->id,
            'slug' => 'evento-a',
            'title' => 'Evento A',
            'event_date' => now()->addDays(5),
            'rsvp_deadline' => now()->addDays(3),
            'location_address' => 'Rua A, 100',
            'status' => 'active',
            'is_unlimited' => true,
        ]);

        $eventB = Event::create([
            'user_id' => $user->id,
            'slug' => 'evento-b',
            'title' => 'Evento B',
            'event_date' => now()->addDays(10),
            'rsvp_deadline' => now()->addDays(8),
            'location_address' => 'Rua B, 200',
            'status' => 'active',
            'is_unlimited' => true,
        ]);

        // Confirmação no Evento A
        $resA = $this->postJson('/convite/evento-a/confirmar', [
            'name' => 'Renan Piccinini',
            'phone' => '(51) 99999-8888',
        ]);
        $resA->assertStatus(200);
        $resA->assertJson(['success' => true]);

        // Confirmação da MESMA pessoa com MESMO telefone no Evento B: permitido!
        $resB = $this->postJson('/convite/evento-b/confirmar', [
            'name' => 'Renan Piccinini',
            'phone' => '(51) 99999-8888',
        ]);
        $resB->assertStatus(200);
        $resB->assertJson(['success' => true]);

        $this->assertEquals(1, $eventA->guests()->count());
        $this->assertEquals(1, $eventB->guests()->count());
    }

    public function test_organizer_cannot_manually_add_duplicate_guest_in_same_event(): void
    {
        $user = User::factory()->create();
        $event = Event::create([
            'user_id' => $user->id,
            'slug' => 'evento-manual',
            'title' => 'Evento Manual',
            'event_date' => now()->addDays(5),
            'rsvp_deadline' => now()->addDays(3),
            'location_address' => 'Rua Teste, 100',
            'status' => 'active',
            'is_unlimited' => true,
        ]);

        $event->guests()->create([
            'name' => 'Carlos Silva',
            'phone' => '(51) 99999-0000',
            'confirmed_at' => now(),
        ]);

        // Tentativa manual de adicionar com mesmo nome
        $response = $this->actingAs($user)->post("/events/{$event->id}/guests", [
            'name' => 'Carlos Silva',
            'phone' => '(51) 98888-0000',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(1, $event->guests()->count());
    }
}

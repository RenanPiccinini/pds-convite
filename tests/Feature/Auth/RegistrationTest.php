<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_registrar_alias_redirects_to_register(): void
    {
        $response = $this->get('/registrar');

        $response->assertRedirect('/register');
    }

    public function test_cadastrar_alias_redirects_to_register(): void
    {
        $response = $this->get('/cadastrar');

        $response->assertRedirect('/register');
    }

    public function test_cadastro_alias_redirects_to_register(): void
    {
        $response = $this->get('/cadastro');

        $response->assertRedirect('/register');
    }

    public function test_se_cadastrar_alias_redirects_to_register(): void
    {
        $response = $this->get('/se-cadastrar');

        $response->assertRedirect('/register');
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}

<?php

namespace Tests\Feature;

use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(AdminSeeder::class);
    }

    public function test_admin_can_login_and_is_redirected_to_dashboard(): void
    {
        $this->post('/login', [
            'documento' => '10000000001',
            'contrasena' => 'admin123',
        ])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    public function test_login_trims_whitespace_around_the_documento(): void
    {
        $this->post('/login', [
            'documento' => '  10000000001  ',
            'contrasena' => 'admin123',
        ])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    public function test_wrong_password_returns_errors_and_preserves_documento_input(): void
    {
        $this->from(route('login'))->post('/login', [
            'documento' => '10000000001',
            'contrasena' => 'incorrecta',
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['documento' => 'Documento o contraseña incorrectos.'])
            ->assertSessionHasInput('documento', '10000000001');

        $this->assertGuest();
    }

    public function test_login_validates_required_fields(): void
    {
        $this->from(route('login'))->post('/login', [
            'documento' => '',
            'contrasena' => '',
        ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['documento', 'contrasena']);

        $this->assertGuest();
    }
}

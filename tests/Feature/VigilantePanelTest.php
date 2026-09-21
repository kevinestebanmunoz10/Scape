<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ProfesorSeeder;
use Database\Seeders\RectorSeeder;
use Database\Seeders\VigilanteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VigilantePanelTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $this->seed(AdminSeeder::class);

        return User::where('Documento', 10000000001)->firstOrFail();
    }

    private function profesor(): User
    {
        $this->seed(ProfesorSeeder::class);

        return User::where('Documento', 10000000002)->firstOrFail();
    }

    private function rector(): User
    {
        $this->seed(RectorSeeder::class);

        return User::where('Documento', 10000000003)->firstOrFail();
    }

    private function vigilante(): User
    {
        $this->seed(VigilanteSeeder::class);

        return User::where('Documento', 10000000004)->firstOrFail();
    }

    public function test_guest_is_redirected_from_vigilante_dashboard(): void
    {
        $this->get('/vigilante/dashboard')->assertRedirect('/login');
    }

    public function test_vigilante_can_access_vigilante_dashboard(): void
    {
        $this->actingAs($this->vigilante())
            ->get('/vigilante/dashboard')
            ->assertOk();
    }

    public function test_vigilante_dashboard_shows_entry_exit_and_history_actions(): void
    {
        $this->actingAs($this->vigilante())
            ->get('/vigilante/dashboard')
            ->assertOk()
            ->assertSee('ENTRADA')
            ->assertSee('SALIDA')
            ->assertSee('HISTORIAL');
    }

    public function test_vigilante_sidebar_only_shows_profile_and_logout(): void
    {
        $this->actingAs($this->vigilante())
            ->get('/vigilante/dashboard')
            ->assertOk()
            ->assertSee('Mi perfil')
            ->assertSee('Logout')
            ->assertDontSee('sidebar-main')
            ->assertDontSee('Usuarios')
            ->assertDontSee('Accesos')
            ->assertDontSee('Permisos');
    }

    public function test_vigilante_can_access_vigilante_profile(): void
    {
        $this->actingAs($this->vigilante())
            ->get('/vigilante/perfil')
            ->assertOk()
            ->assertSee('Mi perfil')
            ->assertSee('Vigilante');
    }

    public function test_admin_cannot_access_vigilante_dashboard(): void
    {
        $this->actingAs($this->admin())
            ->get('/vigilante/dashboard')
            ->assertRedirect(route('dashboard'));
    }

    public function test_profesor_cannot_access_vigilante_dashboard(): void
    {
        $this->actingAs($this->profesor())
            ->get('/vigilante/dashboard')
            ->assertRedirect(route('profesor.dashboard'));
    }

    public function test_rector_cannot_access_vigilante_dashboard(): void
    {
        $this->actingAs($this->rector())
            ->get('/vigilante/dashboard')
            ->assertRedirect(route('rector.dashboard'));
    }

    public function test_vigilante_cannot_access_admin_dashboard(): void
    {
        $this->actingAs($this->vigilante())
            ->get('/admin/dashboard')
            ->assertRedirect(route('vigilante.dashboard'));
    }

    public function test_login_redirects_vigilante_to_panel(): void
    {
        $this->seed(VigilanteSeeder::class);

        $this->post('/login', [
            'documento' => '10000000004',
            'contrasena' => 'vigilante123',
        ])->assertRedirect(route('vigilante.dashboard'));
    }

    public function test_home_redirects_vigilante_by_role(): void
    {
        $this->actingAs($this->vigilante())
            ->get('/')
            ->assertRedirect(route('vigilante.dashboard'));
    }

    public function test_vigilante_panel_navbar_only_shows_logo_and_username(): void
    {
        $vigilante = $this->vigilante();

        $this->actingAs($vigilante)
            ->get('/vigilante/dashboard')
            ->assertOk()
            ->assertSee($vigilante->Nom_usua)
            ->assertDontSee('nav-link');
    }
}

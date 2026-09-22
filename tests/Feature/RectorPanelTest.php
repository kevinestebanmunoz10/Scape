<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ProfesorSeeder;
use Database\Seeders\RectorSeeder;
use Database\Seeders\VigilanteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RectorPanelTest extends TestCase
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

    public function test_guest_is_redirected_from_rector_dashboard(): void
    {
        $this->get('/rector/dashboard')->assertRedirect('/login');
    }

    public function test_rector_can_access_rector_dashboard(): void
    {
        $this->actingAs($this->rector())
            ->get('/rector/dashboard')
            ->assertOk();
    }

    public function test_rector_can_access_rector_profile(): void
    {
        $this->actingAs($this->rector())
            ->get('/rector/perfil')
            ->assertOk()
            ->assertSee('Mi perfil')
            ->assertSee('Rector');
    }

    public function test_admin_cannot_access_rector_dashboard(): void
    {
        $this->actingAs($this->admin())
            ->get('/rector/dashboard')
            ->assertRedirect(route('dashboard'));
    }

    public function test_profesor_cannot_access_rector_dashboard(): void
    {
        $this->actingAs($this->profesor())
            ->get('/rector/dashboard')
            ->assertRedirect(route('profesor.dashboard'));
    }

    public function test_vigilante_cannot_access_rector_dashboard(): void
    {
        $this->actingAs($this->vigilante())
            ->get('/rector/dashboard')
            ->assertRedirect(route('vigilante.dashboard'));
    }

    public function test_rector_cannot_access_admin_dashboard(): void
    {
        $this->actingAs($this->rector())
            ->get('/admin/dashboard')
            ->assertRedirect(route('rector.dashboard'));
    }

    public function test_rector_cannot_access_profesor_dashboard(): void
    {
        $this->actingAs($this->rector())
            ->get('/profesor/dashboard')
            ->assertRedirect(route('rector.dashboard'));
    }

    public function test_login_redirects_rector_to_panel(): void
    {
        $this->seed(RectorSeeder::class);

        $this->post('/login', [
            'documento' => '10000000003',
            'contrasena' => 'rector123',
            'terminos' => '1',
        ])->assertRedirect(route('rector.dashboard'));
    }

    public function test_home_redirects_rector_by_role(): void
    {
        $this->actingAs($this->rector())
            ->get('/')
            ->assertRedirect(route('rector.dashboard'));
    }

    public function test_rector_panel_navbar_only_shows_logo_and_username(): void
    {
        $rector = $this->rector();

        $this->actingAs($rector)
            ->get('/rector/dashboard')
            ->assertOk()
            ->assertSee($rector->Nom_usua)
            ->assertDontSee('nav-link');
    }

    public function test_rector_sidebar_shows_admin_options(): void
    {
        $this->actingAs($this->rector())
            ->get('/rector/dashboard')
            ->assertOk()
            ->assertSee('Usuarios')
            ->assertSee('Accesos')
            ->assertSee('Equipos')
            ->assertSee('Permisos')
            ->assertSee('Gesti&oacute;n', false)
            ->assertSee('Reportes');
    }
}

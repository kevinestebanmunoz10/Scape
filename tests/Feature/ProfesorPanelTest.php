<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ProfesorSeeder;
use Database\Seeders\RectorSeeder;
use Database\Seeders\VigilanteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfesorPanelTest extends TestCase
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

    public function test_guest_is_redirected_from_profesor_dashboard(): void
    {
        $this->get('/profesor/dashboard')->assertRedirect('/login');
    }

    public function test_login_page_serves_all_roles(): void
    {
        $this->get('/login')->assertOk();

        $this->get('/admin/login')->assertNotFound();
    }

    public function test_profesor_can_access_profesor_dashboard(): void
    {
        $this->actingAs($this->profesor())
            ->get('/profesor/dashboard')
            ->assertOk();
    }

    public function test_admin_cannot_access_profesor_dashboard(): void
    {
        $this->actingAs($this->admin())
            ->get('/profesor/dashboard')
            ->assertRedirect(route('dashboard'));
    }

    public function test_profesor_cannot_access_admin_dashboard(): void
    {
        $this->actingAs($this->profesor())
            ->get('/admin/dashboard')
            ->assertRedirect(route('profesor.dashboard'));
    }

    public function test_rector_cannot_access_profesor_dashboard(): void
    {
        $this->actingAs($this->rector())
            ->get('/profesor/dashboard')
            ->assertRedirect(route('rector.dashboard'));
    }

    public function test_vigilante_cannot_access_profesor_dashboard(): void
    {
        $this->actingAs($this->vigilante())
            ->get('/profesor/dashboard')
            ->assertRedirect(route('vigilante.dashboard'));
    }

    public function test_login_redirects_profesor_to_panel(): void
    {
        $this->seed(ProfesorSeeder::class);

        $this->post('/login', [
            'documento' => '10000000002',
            'contrasena' => 'profesor123',
        ])->assertRedirect(route('profesor.dashboard'));
    }

    public function test_login_redirects_admin_to_panel(): void
    {
        $this->seed(AdminSeeder::class);

        $this->post('/login', [
            'documento' => '10000000001',
            'contrasena' => 'admin123',
        ])->assertRedirect(route('dashboard'));
    }

    public function test_home_redirects_authenticated_users_by_role(): void
    {
        $this->actingAs($this->admin())
            ->get('/')
            ->assertRedirect(route('dashboard'));

        $this->actingAs($this->profesor())
            ->get('/')
            ->assertRedirect(route('profesor.dashboard'));
    }

    public function test_profesor_panel_pages_require_role_middleware(): void
    {
        $profesor = $this->profesor();

        foreach (['toma-lista', 'fichas-grupos', 'reportes-alertas', 'exportar', 'perfil'] as $uri) {
            $this->actingAs($profesor)
                ->get('/profesor/'.$uri)
                ->assertOk();
        }
    }

    public function test_profesor_profile_shows_role_name(): void
    {
        $this->actingAs($this->profesor())
            ->get('/profesor/perfil')
            ->assertOk()
            ->assertSee('Profesor');
    }

    public function test_profesor_panel_navbar_only_shows_logo_and_username(): void
    {
        $profesor = $this->profesor();

        $this->actingAs($profesor)
            ->get('/profesor/dashboard')
            ->assertOk()
            ->assertSee($profesor->Nom_usua)
            ->assertDontSee('nav-link');
    }
}

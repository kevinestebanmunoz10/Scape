<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ProfesorSeeder;
use Database\Seeders\RectorSeeder;
use Database\Seeders\VigilanteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
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

    public function test_guest_is_redirected_from_admin_profile(): void
    {
        $this->get('/admin/perfil')->assertRedirect('/login');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/dashboard')
            ->assertOk();
    }

    public function test_admin_can_access_admin_profile(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/perfil')
            ->assertOk()
            ->assertSee('Mi perfil')
            ->assertSee('Administrador');
    }

    public function test_profesor_cannot_access_admin_profile(): void
    {
        $this->actingAs($this->profesor())
            ->get('/admin/perfil')
            ->assertRedirect(route('profesor.dashboard'));
    }

    public function test_rector_cannot_access_admin_profile(): void
    {
        $this->actingAs($this->rector())
            ->get('/admin/perfil')
            ->assertRedirect(route('rector.dashboard'));
    }

    public function test_vigilante_cannot_access_admin_profile(): void
    {
        $this->actingAs($this->vigilante())
            ->get('/admin/perfil')
            ->assertRedirect(route('vigilante.dashboard'));
    }

    public function test_admin_panel_navbar_only_shows_logo_and_username(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee($admin->Nom_usua)
            ->assertDontSee('nav-link');
    }

    public function test_admin_create_user_form_renders_password_hint(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.usuarios.create'))
            ->assertOk()
            ->assertSee('Mínimo 6 caracteres.')
            ->assertDontSee('M&amp;iacute;nimo', false);
    }

    public function test_public_navbar_keeps_navigation_links(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('nav-link');
    }
}

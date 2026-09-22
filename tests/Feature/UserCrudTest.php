<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ProfesorSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCrudTest extends TestCase
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

    private function nuevoUsuario(): array
    {
        return [
            'Documento' => 10000000099,
            'Nom_usua' => 'Usuario Nuevo',
            'email' => 'usuario.nuevo@scape.edu.co',
            'Telefono' => '3111111111',
            'QR' => 'QR-NUEVO',
            'Contrasena' => 'secreto123',
            'id_rol' => 2,
            'id_Estado' => 1,
            'cod_postal' => 730001,
        ];
    }

    public function test_guest_is_redirected_from_users_index(): void
    {
        $this->get('/admin/usuarios')->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_users_index(): void
    {
        $this->actingAs($this->profesor())
            ->get('/admin/usuarios')
            ->assertRedirect(route('profesor.dashboard'));
    }

    public function test_admin_can_see_users_index(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get('/admin/usuarios')
            ->assertOk()
            ->assertSee('Nuevo usuario')
            ->assertSee($admin->Nom_usua)
            ->assertSee($admin->email);
    }

    public function test_admin_can_search_users_by_name(): void
    {
        $this->seed(ProfesorSeeder::class);
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get('/admin/usuarios?buscar=Profesor')
            ->assertOk()
            ->assertSee('Profesor SCAPE')
            ->assertDontSee($admin->Documento);
    }

    public function test_users_search_shows_a_message_when_there_are_no_matches(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/usuarios?buscar=NoExiste')
            ->assertOk()
            ->assertSee('No se encontraron usuarios');
    }

    public function test_admin_can_create_a_user(): void
    {
        $admin = $this->admin();
        $this->seed(ProfesorSeeder::class);

        $this->actingAs($admin)
            ->post('/admin/usuarios', $this->nuevoUsuario())
            ->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseHas('usuario', [
            'Documento' => 10000000099,
            'Nom_usua' => 'Usuario Nuevo',
            'email' => 'usuario.nuevo@scape.edu.co',
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/usuarios', [])
            ->assertSessionHasErrors([
                'Documento',
                'Nom_usua',
                'email',
                'Telefono',
                'Contrasena',
                'id_rol',
                'id_Estado',
                'cod_postal',
            ]);
    }

    public function test_admin_can_view_a_user(): void
    {
        $this->seed(ProfesorSeeder::class);

        $this->actingAs($this->admin())
            ->get('/admin/usuarios/10000000002')
            ->assertOk()
            ->assertSee('Profesor SCAPE');
    }

    public function test_admin_can_update_a_user(): void
    {
        $this->seed(ProfesorSeeder::class);
        $admin = $this->admin();

        $this->actingAs($admin)
            ->put('/admin/usuarios/10000000002', [
                'Nom_usua' => 'Profesor Actualizado',
                'email' => 'profesor@scape.edu.co',
                'Telefono' => '3000000001',
                'QR' => 'QR-PROFESOR',
                'Contrasena' => '',
                'id_rol' => 2,
                'id_Estado' => 1,
                'cod_postal' => 730001,
            ])
            ->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseHas('usuario', [
            'Documento' => 10000000002,
            'Nom_usua' => 'Profesor Actualizado',
        ]);
    }

    public function test_update_requires_a_unique_email(): void
    {
        $this->seed(ProfesorSeeder::class);
        $admin = $this->admin();

        $this->actingAs($admin)
            ->put('/admin/usuarios/10000000002', [
                'Nom_usua' => 'Profesor SCAPE',
                'email' => $admin->email,
                'Telefono' => '3000000001',
                'id_rol' => 2,
                'id_Estado' => 1,
                'cod_postal' => 730001,
            ])
            ->assertSessionHasErrors('email');
    }

    public function test_admin_can_deactivate_another_user(): void
    {
        $this->seed(ProfesorSeeder::class);
        $admin = $this->admin();

        $this->actingAs($admin)
            ->delete('/admin/usuarios/10000000002')
            ->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseHas('usuario', [
            'Documento' => 10000000002,
            'id_Estado' => 2,
        ]);
    }

    public function test_admin_can_activate_a_user(): void
    {
        $this->seed(ProfesorSeeder::class);
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post('/admin/usuarios/10000000002/activar')
            ->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseHas('usuario', [
            'Documento' => 10000000002,
            'id_Estado' => 1,
        ]);
    }

    public function test_admin_cannot_deactivate_their_own_account(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->delete('/admin/usuarios/'.$admin->Documento)
            ->assertRedirect(route('admin.usuarios.index'));

        $this->assertDatabaseHas('usuario', ['Documento' => $admin->Documento]);
    }
}

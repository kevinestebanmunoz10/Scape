<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\ProfesorSeeder;
use Database\Seeders\RectorSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RectorUsuariosTest extends TestCase
{
    use RefreshDatabase;

    private function rector(): User
    {
        $this->seed(RectorSeeder::class);

        return User::where('Documento', 10000000003)->firstOrFail();
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

    public function test_guest_is_redirected_from_rector_users_index(): void
    {
        $this->get('/rector/usuarios')->assertRedirect('/login');
    }

    public function test_rector_can_see_users_index(): void
    {
        $rector = $this->rector();

        $this->actingAs($rector)
            ->get('/rector/usuarios')
            ->assertOk()
            ->assertSee('Nuevo usuario')
            ->assertSee($rector->Nom_usua)
            ->assertSee($rector->email);
    }

    public function test_rector_can_search_users_by_name(): void
    {
        $this->seed(ProfesorSeeder::class);
        $rector = $this->rector();

        $this->actingAs($rector)
            ->get('/rector/usuarios?buscar=Profesor')
            ->assertOk()
            ->assertSee('Profesor SCAPE')
            ->assertDontSee($rector->Documento);
    }

    public function test_rector_can_create_a_user(): void
    {
        $this->seed(ProfesorSeeder::class);
        $rector = $this->rector();

        $this->actingAs($rector)
            ->post('/rector/usuarios', $this->nuevoUsuario())
            ->assertRedirect(route('rector.usuarios.index'));

        $this->assertDatabaseHas('usuario', [
            'Documento' => 10000000099,
            'Nom_usua' => 'Usuario Nuevo',
            'email' => 'usuario.nuevo@scape.edu.co',
        ]);
    }

    public function test_rector_store_validates_required_fields(): void
    {
        $this->actingAs($this->rector())
            ->post('/rector/usuarios', [])
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

    public function test_rector_can_view_a_user(): void
    {
        $this->seed(ProfesorSeeder::class);

        $this->actingAs($this->rector())
            ->get('/rector/usuarios/10000000002')
            ->assertOk()
            ->assertSee('Profesor SCAPE')
            ->assertDontSee('Eliminar usuario');
    }

    public function test_rector_can_update_a_user(): void
    {
        $this->seed(ProfesorSeeder::class);
        $rector = $this->rector();

        $this->actingAs($rector)
            ->put('/rector/usuarios/10000000002', [
                'Nom_usua' => 'Profesor Actualizado',
                'email' => 'profesor@scape.edu.co',
                'Telefono' => '3000000001',
                'QR' => 'QR-PROFESOR',
                'Contrasena' => '',
                'id_rol' => 2,
                'id_Estado' => 1,
                'cod_postal' => 730001,
            ])
            ->assertRedirect(route('rector.usuarios.index'));

        $this->assertDatabaseHas('usuario', [
            'Documento' => 10000000002,
            'Nom_usua' => 'Profesor Actualizado',
        ]);
    }

    public function test_rector_has_no_delete_route(): void
    {
        $this->seed(ProfesorSeeder::class);

        $this->actingAs($this->rector())
            ->delete('/rector/usuarios/10000000002')
            ->assertMethodNotAllowed();
    }
}

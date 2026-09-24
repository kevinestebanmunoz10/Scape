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
            ->assertDontSee('Nuevo usuario')
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

    public function test_rector_can_view_a_user(): void
    {
        $this->seed(ProfesorSeeder::class);

        $this->actingAs($this->rector())
            ->get('/rector/usuarios/10000000002')
            ->assertOk()
            ->assertSee('Profesor SCAPE')
            ->assertDontSee('Editar');
    }

    public function test_rector_cannot_create_a_user(): void
    {
        $this->seed(ProfesorSeeder::class);

        $this->actingAs($this->rector())
            ->post('/rector/usuarios', [
                'Documento' => 10000000099,
                'Nom_usua' => 'Usuario Nuevo',
                'email' => 'usuario.nuevo@scape.edu.co',
            ])
            ->assertMethodNotAllowed();
    }

    public function test_rector_cannot_update_a_user(): void
    {
        $this->seed(ProfesorSeeder::class);

        $this->actingAs($this->rector())
            ->put('/rector/usuarios/10000000002', ['Nom_usua' => 'Profesor Actualizado'])
            ->assertMethodNotAllowed();
    }

    public function test_rector_has_no_create_or_edit_routes(): void
    {
        $this->seed(ProfesorSeeder::class);

        $this->actingAs($this->rector())
            ->get('/rector/usuarios/create')
            ->assertNotFound();

        $this->actingAs($this->rector())
            ->get('/rector/usuarios/10000000002/edit')
            ->assertNotFound();
    }

    public function test_rector_has_no_delete_route(): void
    {
        $this->seed(ProfesorSeeder::class);

        $this->actingAs($this->rector())
            ->delete('/rector/usuarios/10000000002')
            ->assertMethodNotAllowed();
    }
}

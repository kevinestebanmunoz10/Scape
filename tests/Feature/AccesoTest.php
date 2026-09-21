<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\VigilanteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccesoTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        $this->seed(AdminSeeder::class);

        return User::where('Documento', 10000000001)->firstOrFail();
    }

    private function registrarAccesoUsuario(User $user, $ingreso, $salida = null): void
    {
        DB::table('acceso_usu')->insert([
            'f_ingreso' => $ingreso,
            'f_salida' => $salida,
            'Documento_acces' => $user->Documento,
            'id_Estado_usu' => 1,
        ]);
    }

    private function registrarAccesoVisitante(string $documento, string $nombre, $ingreso, $salida = null): void
    {
        DB::table('visitantes')->insert([
            'Docu_visi' => $documento,
            'Nom_visi' => $nombre,
            'tel_visi' => '3000000000',
            'correo_visi' => 'visitante@example.com',
        ]);

        DB::table('acceso_visi')->insert([
            'f_ingreso' => $ingreso,
            'f_salida' => $salida,
            'Documento_visi' => $documento,
            'id_Estado_visi' => 1,
            'Lugar' => 'Rectoría',
        ]);
    }

    public function test_guest_is_redirected_from_accesses(): void
    {
        $this->get('/admin/accesos')->assertRedirect('/login');
    }

    public function test_admin_can_access_accesses_section(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/accesos')
            ->assertOk()
            ->assertSee('Registro de accesos');
    }

    public function test_non_admin_is_redirected_to_their_panel(): void
    {
        $this->seed(VigilanteSeeder::class);
        $vigilante = User::where('Documento', 10000000004)->firstOrFail();

        $this->actingAs($vigilante)
            ->get('/admin/accesos')
            ->assertRedirect(route('vigilante.dashboard'));
    }

    public function test_accesses_list_shows_people_and_status(): void
    {
        $admin = $this->admin();
        $this->registrarAccesoUsuario($admin, now(), null);
        $this->registrarAccesoVisitante('9001', 'Visita Ejemplo', now()->subHour(), now());

        $this->actingAs($admin)
            ->get('/admin/accesos')
            ->assertOk()
            ->assertSee('Administrador SCAPE')
            ->assertSee('Visita Ejemplo')
            ->assertSee('estado-dentro')
            ->assertSee('estado-fuera');
    }

    public function test_type_filter_limits_results(): void
    {
        $admin = $this->admin();
        $this->registrarAccesoVisitante('9001', 'Visita Ejemplo', now());

        $this->actingAs($admin)
            ->get('/admin/accesos?tipo=usuario')
            ->assertOk()
            ->assertDontSee('Visita Ejemplo');

        $this->actingAs($admin)
            ->get('/admin/accesos?tipo=visitante')
            ->assertOk()
            ->assertSee('Visita Ejemplo');
    }

    public function test_search_filters_by_name_or_document(): void
    {
        $admin = $this->admin();
        $this->registrarAccesoVisitante('9001', 'Visita Ejemplo', now());

        $this->actingAs($admin)
            ->get('/admin/accesos?buscar=Ejemplo')
            ->assertOk()
            ->assertSee('Visita Ejemplo');

        $this->actingAs($admin)
            ->get('/admin/accesos?buscar=NoExiste')
            ->assertOk()
            ->assertDontSee('Visita Ejemplo');
    }

    public function test_date_range_filters_results(): void
    {
        $admin = $this->admin();
        $this->registrarAccesoUsuario($admin, now()->subYear());

        $this->actingAs($admin)
            ->get('/admin/accesos')
            ->assertOk()
            ->assertSee('No hay accesos registrados en este rango');
    }
}

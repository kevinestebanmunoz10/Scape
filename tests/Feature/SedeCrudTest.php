<?php

namespace Tests\Feature;

use App\Models\Matricula;
use App\Models\Sede;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\EstudianteSeeder;
use Database\Seeders\ProfesorSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SedeCrudTest extends TestCase
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

    /**
     * La sede referencia la tabla ciudad y la ciudad al departamento, así que se garantem ambos
     */
    private function crearCiudad(int $codigo = 730001): int
    {
        DB::table('departamento')->updateOrInsert(
            ['Codi_Departa' => 73],
            ['Departamento' => 'Tolima'],
        );

        DB::table('ciudad')->updateOrInsert(
            ['cod_Postal' => $codigo],
            ['Ciudad' => 'Ciudad '.$codigo, 'Codi_Departa' => 73],
        );

        return $codigo;
    }

    private function crearSede(int $nit = 900100001, string $nombre = 'Sede Central', int $codigo = 730001, int $idEstado = 1): Sede
    {
        $this->crearCiudad($codigo);

        // La sede referencia la tabla estado, así que se garantizan sus dos filas
        DB::table('estado')->updateOrInsert(['id_estado' => 1], ['estado' => 1]);
        DB::table('estado')->updateOrInsert(['id_estado' => 2], ['estado' => 0]);

        return Sede::create([
            'Nit' => $nit,
            'nombre' => $nombre,
            'cod_postal' => $codigo,
            'id_Estado' => $idEstado,
        ]);
    }

    private function datosSede(int $nit = 900100009, string $nombre = 'Sede Nueva', int $codigo = 730001): array
    {
        return [
            'Nit' => $nit,
            'nombre' => $nombre,
            'cod_postal' => $codigo,
        ];
    }

    private function crearMatriculaEnSede(int $documento, int $nit): Matricula
    {
        $this->seed(EstudianteSeeder::class);

        return Matricula::create([
            'grado' => '10',
            'fecha_matri' => '2026-01-15',
            'Documento_matri' => $documento,
            'Jornada' => null,
            'Salon' => '101',
            'Nit' => $nit,
        ]);
    }

    public function test_guest_is_redirected_from_sede_index(): void
    {
        $this->get('/admin/sedes')->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_sede_index(): void
    {
        $this->actingAs($this->profesor())
            ->get('/admin/sedes')
            ->assertRedirect(route('profesor.dashboard'));
    }

    public function test_admin_can_see_sede_index(): void
    {
        $admin = $this->admin();
        $this->crearSede(900100001, 'Sede Central');

        $this->actingAs($admin)
            ->get('/admin/sedes')
            ->assertOk()
            ->assertSee('Nueva sede')
            ->assertSee('Sede Central')
            ->assertSee('900100001');
    }

    public function test_index_shows_the_city_and_the_matricula_count_of_each_sede(): void
    {
        $admin = $this->admin();
        $sede = $this->crearSede();
        $this->crearMatriculaEnSede(20000000010, $sede->Nit);

        $this->actingAs($admin)
            ->get('/admin/sedes')
            ->assertOk()
            // El nombre de la ciudad lo define el seeder, así que se comprueba su código postal
            ->assertSee('730001');
    }

    public function test_index_counts_the_matriculas_of_each_sede(): void
    {
        $admin = $this->admin();
        $sede = $this->crearSede();
        $this->crearMatriculaEnSede(20000000010, $sede->Nit);
        $otra = $this->crearSede(900100002, 'Sede Norte');

        $html = $this->actingAs($admin)->get('/admin/sedes')->assertOk()->getContent();

        // Se aísla la fila de cada sede para comprobar su contador de matrículas
        preg_match_all('/<tr>(.*?)<\/tr>/s', $html, $filas);

        $conteo = [];
        foreach ($filas[1] as $fila) {
            if (preg_match('/Sede (?:Central|Norte)/', $fila)) {
                preg_match('/<td>(\d+)<\/td>\s*<td>\s*<span class="badge/s', $fila, $numero);
                $conteo[] = $numero[1] ?? null;
            }
        }

        $this->assertSame(['1', '0'], $conteo);
        $this->assertNotSame($sede->Nit, $otra->Nit);
    }

    public function test_index_searches_by_name(): void
    {
        $admin = $this->admin();
        $this->crearSede(900100001, 'Sede Central');
        $this->crearSede(900100002, 'Sede Norte');

        $this->actingAs($admin)
            ->get('/admin/sedes?buscar=Norte')
            ->assertOk()
            ->assertSee('Sede Norte')
            ->assertDontSee('Sede Central');
    }

    public function test_index_searches_by_nit(): void
    {
        $admin = $this->admin();
        $this->crearSede(900100001, 'Sede Central');
        $this->crearSede(900100002, 'Sede Norte');

        $this->actingAs($admin)
            ->get('/admin/sedes?buscar=900100002')
            ->assertOk()
            ->assertSee('Sede Norte')
            ->assertDontSee('Sede Central');
    }

    public function test_index_filters_by_estado(): void
    {
        $admin = $this->admin();
        $this->crearSede(900100001, 'Sede Activa', 730001, 1);
        $this->crearSede(900100002, 'Sede Inactiva', 730001, 2);

        $this->actingAs($admin)
            ->get('/admin/sedes?estado=2')
            ->assertOk()
            ->assertSee('Sede Inactiva')
            ->assertDontSee('Sede Activa');
    }

    public function test_index_marks_estado_with_a_badge(): void
    {
        $admin = $this->admin();
        $this->crearSede(900100001, 'Sede Activa', 730001, 1);
        $this->crearSede(900100002, 'Sede Inactiva', 730001, 2);

        $this->actingAs($admin)
            ->get('/admin/sedes')
            ->assertOk()
            ->assertSee('Activa')
            ->assertSee('Inactiva');
    }

    public function test_admin_can_see_sede_create_form(): void
    {
        $admin = $this->admin();
        $this->crearCiudad();

        $this->actingAs($admin)
            ->get('/admin/sedes/create')
            ->assertOk()
            ->assertSee('Registrar sede');
    }

    public function test_create_form_offers_city_catalog(): void
    {
        $admin = $this->admin();
        $this->crearCiudad(730001);

        $this->actingAs($admin)
            ->get('/admin/sedes/create')
            ->assertOk()
            ->assertSee('Ciudad 730001');
    }

    public function test_store_creates_a_sede(): void
    {
        $admin = $this->admin();
        $this->crearCiudad();

        $this->actingAs($admin)
            ->post('/admin/sedes', $this->datosSede())
            ->assertRedirect('/admin/sedes')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('sede', [
            'Nit' => 900100009,
            'nombre' => 'Sede Nueva',
        ]);
    }

    public function test_store_defaults_to_active_sede(): void
    {
        $admin = $this->admin();
        $this->crearCiudad();

        $this->actingAs($admin)->post('/admin/sedes', $this->datosSede());

        $this->assertSame(1, (int) Sede::where('Nit', 900100009)->value('id_Estado'));
    }

    public function test_store_rejects_a_duplicate_nit(): void
    {
        $admin = $this->admin();
        $this->crearSede(900100001, 'Sede Central');

        $this->actingAs($admin)
            ->post('/admin/sedes', $this->datosSede(900100001, 'Sede Duplicada'))
            ->assertSessionHasErrors('Nit');

        $this->assertDatabaseMissing('sede', ['Nit' => 900100001, 'nombre' => 'Sede Duplicada']);
    }

    public function test_store_rejects_a_nit_with_wrong_length(): void
    {
        $admin = $this->admin();
        $this->crearCiudad();

        $this->actingAs($admin)
            ->post('/admin/sedes', $this->datosSede(12345))
            ->assertSessionHasErrors('Nit');

        $this->assertDatabaseMissing('sede', ['Nit' => 12345]);
    }

    public function test_store_rejects_a_city_that_is_not_in_the_catalog(): void
    {
        $admin = $this->admin();
        $this->crearCiudad(730001);

        $this->actingAs($admin)
            ->post('/admin/sedes', $this->datosSede(900100009, 'Sede Nueva', 999999))
            ->assertSessionHasErrors('cod_postal');

        $this->assertDatabaseMissing('sede', ['Nit' => 900100009]);
    }

    public function test_admin_can_see_sede_detail(): void
    {
        $admin = $this->admin();
        $sede = $this->crearSede();

        $this->actingAs($admin)
            ->get("/admin/sedes/{$sede->Nit}")
            ->assertOk()
            ->assertSee('Sede Central')
            ->assertSee('Ciudad 730001');
    }

    public function test_sede_detail_lists_its_matriculas(): void
    {
        $admin = $this->admin();
        $sede = $this->crearSede();
        $matricula = $this->crearMatriculaEnSede(20000000010, $sede->Nit);

        $this->actingAs($admin)
            ->get("/admin/sedes/{$sede->Nit}")
            ->assertOk()
            // El escapado se desactiva porque el texto esperado ya contiene entidades HTML
            ->assertSee('Matr&iacute;culas de la sede', false)
            ->assertSee((string) $matricula->numero_matri)
            ->assertSee($matricula->estudiante->Nom_usua);
    }

    public function test_sede_detail_says_when_it_has_no_matriculas(): void
    {
        $admin = $this->admin();
        $sede = $this->crearSede();

        $this->actingAs($admin)
            ->get("/admin/sedes/{$sede->Nit}")
            ->assertOk()
            ->assertSee('Esta sede todav&iacute;a no tiene matr&iacute;culas registradas.', false);
    }

    public function test_sede_belongs_to_its_matriculas(): void
    {
        $sede = $this->crearSede();
        $matricula = $this->crearMatriculaEnSede(20000000010, $sede->Nit);

        $this->assertSame($sede->Nit, $matricula->sede->Nit);
        $this->assertTrue($sede->matriculas->contains($matricula));
    }

    public function test_admin_can_see_sede_edit_form(): void
    {
        $admin = $this->admin();
        $sede = $this->crearSede();

        $this->actingAs($admin)
            ->get("/admin/sedes/{$sede->Nit}/edit")
            ->assertOk()
            ->assertSee('Guardar cambios')
            ->assertSee('Sede Central');
    }

    public function test_update_changes_the_sede(): void
    {
        $admin = $this->admin();
        $sede = $this->crearSede();

        $this->actingAs($admin)
            ->put("/admin/sedes/{$sede->Nit}", [
                'Nit' => $sede->Nit,
                'nombre' => 'Sede Renombrada',
                'cod_postal' => 730001,
            ])
            ->assertRedirect('/admin/sedes')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('sede', ['Nit' => 900100001, 'nombre' => 'Sede Renombrada']);
    }

    public function test_update_keeps_the_current_estado_when_not_sent(): void
    {
        $admin = $this->admin();
        $sede = $this->crearSede(900100001, 'Sede Inactiva', 730001, 2);

        $this->actingAs($admin)->put("/admin/sedes/{$sede->Nit}", [
            'Nit' => $sede->Nit,
            'nombre' => 'Sede Inactiva',
            'cod_postal' => 730001,
        ]);

        $this->assertSame(2, (int) Sede::where('Nit', 900100001)->value('id_Estado'));
    }

    public function test_update_rejects_a_nit_owned_by_another_sede(): void
    {
        $admin = $this->admin();
        $sede = $this->crearSede(900100001, 'Sede Central');
        $this->crearSede(900100002, 'Sede Norte');

        $this->actingAs($admin)
            ->put("/admin/sedes/{$sede->Nit}", [
                'Nit' => 900100002,
                'nombre' => 'Sede Central',
                'cod_postal' => 730001,
            ])
            ->assertSessionHasErrors('Nit');

        $this->assertDatabaseHas('sede', ['Nit' => 900100001, 'nombre' => 'Sede Central']);
    }

    public function test_destroy_deactivates_the_sede_without_deleting_it(): void
    {
        $admin = $this->admin();
        $sede = $this->crearSede();

        $this->actingAs($admin)
            ->delete("/admin/sedes/{$sede->Nit}")
            ->assertRedirect('/admin/sedes')
            ->assertSessionHas('status');

        $this->assertDatabaseHas('sede', ['Nit' => 900100001, 'id_Estado' => 2]);
    }

    public function test_activar_reactivates_the_sede(): void
    {
        $admin = $this->admin();
        $sede = $this->crearSede(900100001, 'Sede Inactiva', 730001, 2);

        $this->actingAs($admin)
            ->post("/admin/sedes/{$sede->Nit}/activar")
            ->assertRedirect('/admin/sedes');

        $this->assertDatabaseHas('sede', ['Nit' => 900100001, 'id_Estado' => 1]);
    }

    public function test_inactive_sedes_are_not_offered_in_the_matricula_form(): void
    {
        $admin = $this->admin();
        $this->crearSede(900100001, 'Sede Activa', 730001, 1);
        $this->crearSede(900100002, 'Sede Inactiva', 730001, 2);

        $this->actingAs($admin)
            ->get('/admin/matriculas/create')
            ->assertOk()
            ->assertSee('Sede Activa')
            ->assertDontSee('Sede Inactiva');
    }

    public function test_gestion_screen_lists_the_three_modules(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get('/admin/gestion')
            ->assertOk()
            // El escapado se desactiva porque los textos esperados ya contienen entidades HTML
            ->assertSee('Gestionar matr&iacute;culas', false)
            ->assertSee('Gestionar sedes', false)
            ->assertSee('Gestionar salones', false);
    }

    public function test_gestion_button_links_to_the_sede_crud(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get('/admin/gestion')
            ->assertOk()
            ->assertSee(route('admin.sedes.index'), false);
    }

    public function test_gestion_button_links_to_the_matricula_crud(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get('/admin/gestion')
            ->assertOk()
            ->assertSee(route('admin.matriculas.index'), false);
    }

    public function test_sidebar_gestion_link_points_to_the_gestion_screen(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->get('/admin/sedes')
            ->assertOk()
            ->assertSee(route('admin.gestion'), false);
    }
}

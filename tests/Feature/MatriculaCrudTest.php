<?php

namespace Tests\Feature;

use App\Models\Acudiente;
use App\Models\EstudianteAcudiente;
use App\Models\Jornada;
use App\Models\Matricula;
use App\Models\Parentesco;
use App\Models\Salon;
use App\Models\User;
use Database\Seeders\AcudienteSeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\EstudianteSeeder;
use Database\Seeders\JornadaSeeder;
use Database\Seeders\ParentescoSeeder;
use Database\Seeders\ProfesorSeeder;
use Database\Seeders\SedeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MatriculaCrudTest extends TestCase
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

    private function estudiante(): User
    {
        $this->seed(EstudianteSeeder::class);

        return User::where('Documento', 20000000010)->firstOrFail();
    }

    private function crearSalon(string $codigo = '101'): Salon
    {
        // El salón referencia la tabla estado, así que se garantiza la existencia del estado activo
        DB::table('estado')->updateOrInsert(['id_estado' => 1], ['estado' => 1]);

        return Salon::create([
            'codigo' => $codigo,
            'capacidad' => 35,
            'id_Estado' => 1,
        ]);
    }

    private function datosMatricula(string $codigo = '101'): array
    {
        // La jornada debe existir en el catálogo para que el payload sea válido
        $this->seed(JornadaSeeder::class);

        return [
            'Documento_matri' => 20000000010,
            'grado' => '10',
            'fecha_matri' => '2026-01-15',
            'Jornada' => 'Mañana',
            'Salon' => $codigo,
            'Nit' => null,
        ];
    }

    private function crearEstadoActivo(): void
    {
        // El parentesco referencia la tabla estado, así que se garantiza la existencia del estado activo
        DB::table('estado')->updateOrInsert(['id_estado' => 1], ['estado' => 1]);
    }

    /**
     * @return string El HTML completo del <select> del campo salón
     */
    private function selectSalon(string $html): string
    {
        preg_match('/<select id="Salon" name="Salon".*?<\/select>/s', $html, $matches);

        return $matches[0] ?? '';
    }

    /**
     * @return string El HTML completo del <select> del campo jornada
     */
    private function selectJornada(string $html): string
    {
        preg_match('/<select id="Jornada" name="Jornada".*?<\/select>/s', $html, $matches);

        return $matches[0] ?? '';
    }

    /**
     * @return string El HTML completo del <select> del campo sede
     */
    private function selectSede(string $html): string
    {
        preg_match('/<select id="Nit" name="Nit".*?<\/select>/s', $html, $matches);

        return $matches[0] ?? '';
    }

    /**
     * @return string El HTML completo del <select> del catálogo de acudientes
     */
    private function selectAcudiente(string $html): string
    {
        preg_match('/<select id="acudienteExistente0".*?<\/select>/s', $html, $matches);

        return $matches[0] ?? '';
    }

    /**
     * @return string El HTML completo del <select> de parentescos de los acudientes existentes
     */
    private function selectAcudienteParentesco(string $html): string
    {
        preg_match('/<select id="parentescoExistente0".*?<\/select>/s', $html, $matches);

        return $matches[0] ?? '';
    }

    public function test_guest_is_redirected_from_matricula_index(): void
    {
        $this->get('/admin/matriculas')->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_matricula_index(): void
    {
        $this->actingAs($this->profesor())
            ->get('/admin/matriculas')
            ->assertRedirect(route('profesor.dashboard'));
    }

    public function test_admin_can_see_matricula_index(): void
    {
        $admin = $this->admin();
        $this->crearSalon();

        $this->actingAs($admin)
            ->get('/admin/matriculas')
            ->assertOk()
            ->assertSee('Nueva matrícula')
            ->assertSee('No hay matrículas registradas.');
    }

    public function test_admin_can_see_matriculas_in_index(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();

        $this->actingAs($admin)->post('/admin/matriculas', $this->datosMatricula());

        $this->actingAs($admin)
            ->get('/admin/matriculas')
            ->assertOk()
            ->assertSee('Mateo Herrera Salazar')
            ->assertSee('101');
    }

    public function test_admin_can_create_matricula(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();

        $this->actingAs($admin)
            ->post('/admin/matriculas', $this->datosMatricula())
            ->assertRedirect(route('admin.matriculas.index'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('matricula', [
            'Documento_matri' => 20000000010,
            'grado' => '10',
            'Salon' => '101',
        ]);
    }

    public function test_salon_field_is_a_select_and_not_a_free_text_input(): void
    {
        $admin = $this->admin();
        $this->crearSalon();

        $this->actingAs($admin)
            ->get('/admin/matriculas/create')
            ->assertOk()
            ->assertSee('<select id="Salon" name="Salon"', false)
            ->assertDontSee('<input type="text" id="Salon"', false);
    }

    public function test_salon_field_only_offers_salones_from_the_catalog(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon('101');
        $this->crearSalon('202');

        $html = $this->actingAs($admin)->get('/admin/matriculas/create')->assertOk()->getContent();
        $select = $this->selectSalon($html);

        $this->assertStringContainsString('<option value="101"', $select);
        $this->assertStringContainsString('<option value="202"', $select);
        $this->assertStringContainsString('capacidad 35', $select);
        // El placeholder más los dos salones del catálogo
        $this->assertSame(3, substr_count($select, '<option'));
    }

    public function test_store_rejects_a_salon_that_is_not_in_the_catalog(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon('101');

        $this->actingAs($admin)
            ->post('/admin/matriculas', $this->datosMatricula('999'))
            ->assertSessionHasErrors('Salon');

        $this->assertDatabaseMissing('matricula', ['Documento_matri' => 20000000010]);
    }

    public function test_store_validates_required_fields(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/matriculas', [])
            ->assertSessionHasErrors(['Documento_matri', 'grado', 'fecha_matri', 'Salon']);
    }

    public function test_store_rejects_an_unknown_student(): void
    {
        $admin = $this->admin();
        $this->crearSalon();

        $data = $this->datosMatricula();
        $data['Documento_matri'] = 99999999999;

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertSessionHasErrors('Documento_matri');

        $this->assertDatabaseMissing('matricula', ['grado' => '10']);
    }

    public function test_store_rejects_a_student_that_already_has_a_matricula(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();

        $this->actingAs($admin)->post('/admin/matriculas', $this->datosMatricula());

        $this->actingAs($admin)
            ->post('/admin/matriculas', $this->datosMatricula('101'))
            ->assertSessionHasErrors('Documento_matri');
    }

    public function test_already_enrolled_students_disappear_from_the_create_form(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();

        // Se deja un solo estudiante disponible para poder comprobar el estado vacío del selector
        User::where('id_rol', 5)->where('Documento', '!=', 20000000010)->delete();

        $this->actingAs($admin)
            ->get('/admin/matriculas/create')
            ->assertOk()
            ->assertSee('Mateo Herrera Salazar');

        $this->actingAs($admin)->post('/admin/matriculas', $this->datosMatricula());

        $this->actingAs($admin)
            ->get('/admin/matriculas/create')
            ->assertOk()
            ->assertDontSee('Mateo Herrera Salazar')
            ->assertSee('No hay estudiantes sin matrícula');
    }

    public function test_inactive_salons_are_not_offered(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon('101');

        // El estado 2 corresponde a "eliminado" y ya lo crea la migración de la tabla estado
        Salon::create(['codigo' => '999', 'capacidad' => 20, 'id_Estado' => 2]);

        $select = $this->selectSalon(
            $this->actingAs($admin)->get('/admin/matriculas/create')->assertOk()->getContent()
        );

        $this->assertStringContainsString('<option value="101"', $select);
        $this->assertStringNotContainsString('<option value="999"', $select);
    }

    public function test_admin_can_search_matriculas_by_student_name(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();

        $this->actingAs($admin)->post('/admin/matriculas', $this->datosMatricula());

        $this->actingAs($admin)
            ->get('/admin/matriculas?buscar=Herrera')
            ->assertOk()
            ->assertSee('Mateo Herrera Salazar');
    }

    public function test_matricula_belongs_to_its_salon_and_student(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $salon = $this->crearSalon('301');

        $this->actingAs($admin)
            ->post('/admin/matriculas', $this->datosMatricula('301'))
            ->assertRedirect(route('admin.matriculas.index'));

        $matricula = Matricula::where('Documento_matri', 20000000010)->firstOrFail();

        $this->assertTrue($matricula->salon->is($salon));
        $this->assertSame('Mateo Herrera Salazar', $matricula->estudiante->Nom_usua);
    }

    public function test_jornada_seeder_inserts_three_records(): void
    {
        $this->seed(JornadaSeeder::class);

        $this->assertSame(3, Jornada::count());
        $this->assertDatabaseHas('jornada', ['jornada' => 'Mañana']);
        $this->assertDatabaseHas('jornada', ['jornada' => 'Tarde']);
        $this->assertDatabaseHas('jornada', ['jornada' => 'Noche']);
    }

    public function test_jornada_field_only_offers_jornadas_from_the_catalog(): void
    {
        $admin = $this->admin();
        $this->seed(JornadaSeeder::class);

        $select = $this->selectJornada(
            $this->actingAs($admin)->get('/admin/matriculas/create')->assertOk()->getContent()
        );

        $this->assertStringContainsString('<option value="Mañana"', $select);
        $this->assertStringContainsString('<option value="Tarde"', $select);
        $this->assertStringContainsString('<option value="Noche"', $select);
        // El placeholder más las tres jornadas del catálogo
        $this->assertSame(4, substr_count($select, '<option'));
    }

    public function test_store_rejects_a_jornada_that_is_not_in_the_catalog(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();
        $this->seed(JornadaSeeder::class);

        $data = $this->datosMatricula();
        $data['Jornada'] = 'Madrugada';

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertSessionHasErrors('Jornada');

        $this->assertDatabaseMissing('matricula', ['Documento_matri' => 20000000010]);
    }

    public function test_store_accepts_a_jornada_from_the_catalog(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();
        $this->seed(JornadaSeeder::class);

        $data = $this->datosMatricula();
        $data['Jornada'] = 'Noche';

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertRedirect(route('admin.matriculas.index'));

        $this->assertDatabaseHas('matricula', [
            'Documento_matri' => 20000000010,
            'Jornada' => 'Noche',
        ]);
    }

    public function test_sede_seeder_inserts_three_records(): void
    {
        $this->seed(SedeSeeder::class);

        $this->assertSame(3, DB::table('sede')->count());
        $this->assertSame(3, DB::table('sede')->pluck('Nit')->unique()->count());
    }

    public function test_sede_field_only_offers_sedes_from_the_catalog(): void
    {
        $admin = $this->admin();
        $this->seed(SedeSeeder::class);

        $select = $this->selectSede(
            $this->actingAs($admin)->get('/admin/matriculas/create')->assertOk()->getContent()
        );

        $this->assertStringContainsString('Sede Principal - Campus Central', $select);
        $this->assertStringContainsString('Sede Norte - Aulas Técnicas', $select);
        $this->assertStringContainsString('Sede Sur - Instalaciones Deportivas', $select);
        // El placeholder más las tres sedes del catálogo
        $this->assertSame(4, substr_count($select, '<option'));
    }

    public function test_store_accepts_a_sede_from_the_catalog(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();
        $this->seed(SedeSeeder::class);

        $data = $this->datosMatricula();
        $data['Nit'] = 900100002;

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertRedirect(route('admin.matriculas.index'));

        $this->assertDatabaseHas('matricula', [
            'Documento_matri' => 20000000010,
            'Nit' => 900100002,
        ]);
    }

    public function test_store_rejects_a_sede_that_does_not_exist(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();
        $this->seed(SedeSeeder::class);

        $data = $this->datosMatricula();
        $data['Nit'] = 999999999;

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertSessionHasErrors('Nit');

        $this->assertDatabaseMissing('matricula', ['Documento_matri' => 20000000010]);
    }

    public function test_parentesco_seeder_inserts_five_records(): void
    {
        $this->crearEstadoActivo();
        $this->seed(ParentescoSeeder::class);

        $this->assertSame(5, Parentesco::count());
        $this->assertDatabaseHas('parentesco', ['parentesco' => 'Padre']);
        $this->assertDatabaseHas('parentesco', ['parentesco' => 'Madre']);
    }

    public function test_parentesco_catalog_hides_inactive_records(): void
    {
        $admin = $this->admin();
        $this->crearEstadoActivo();
        $this->seed(ParentescoSeeder::class);

        // El estado 2 corresponde a "eliminado", por lo que el parentesco queda oculto
        Parentesco::where('parentesco', 'Tío')->update(['Estado_paren' => 2]);

        $select = $this->selectAcudienteParentesco(
            $this->actingAs($admin)->get('/admin/matriculas/create')->assertOk()->getContent()
        );

        $this->assertStringContainsString('Abuelo', $select);
        $this->assertStringNotContainsString('Tío', $select);
    }

    public function test_acudiente_seeder_inserts_three_records(): void
    {
        $this->seed(AcudienteSeeder::class);

        $this->assertSame(3, Acudiente::count());
        $this->assertSame(3, Acudiente::pluck('Documento_acud')->unique()->count());
    }

    public function test_create_form_lists_the_parentesco_catalog(): void
    {
        $admin = $this->admin();
        $this->crearEstadoActivo();
        $this->seed(ParentescoSeeder::class);

        $html = $this->actingAs($admin)->get('/admin/matriculas/create')->assertOk()->getContent();

        // El desplegable de parentescos de los acudientes existentes se genera una vez
        $select = $this->selectAcudienteParentesco($html);

        foreach (['Padre', 'Madre', 'Abuelo', 'Hermano', 'Tío'] as $parentesco) {
            $this->assertStringContainsString($parentesco, $select);
        }

        // El placeholder más los cinco parentescos del catálogo
        $this->assertSame(6, substr_count($select, '<option'));
    }

    public function test_create_form_lists_the_acudiente_catalog(): void
    {
        $admin = $this->admin();
        $this->seed(AcudienteSeeder::class);

        $html = $this->actingAs($admin)->get('/admin/matriculas/create')->assertOk()->getContent();
        $select = $this->selectAcudiente($html);

        $this->assertStringContainsString('<option value="1002003004"', $select);
        $this->assertStringContainsString('Carmen Rosa Salazar', $select);
        // El placeholder más los tres acudientes del catálogo
        $this->assertSame(4, substr_count($select, '<option'));
    }

    public function test_store_creates_a_new_acudiente_and_links_it_to_the_student(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();
        $this->seed(ParentescoSeeder::class);

        $parentesco = Parentesco::where('parentesco', 'Madre')->value('id_parentesco');

        $data = $this->datosMatricula();
        $data['nuevos_acudientes'] = [
            [
                'documento' => '900111222',
                'nombre' => 'Marta Quintero',
                'telefono' => '3112223344',
                'telefono_alt' => '',
                'direccion' => 'Calle 9 #4-12',
                'correo' => 'marta.quintero@correo.edu.co',
                'parentesco' => $parentesco,
            ],
        ];

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertRedirect(route('admin.matriculas.index'));

        // El acudiente queda disponible en el catálogo compartido
        $this->assertDatabaseHas('acudiente', [
            'Documento_acud' => '900111222',
            'nombre' => 'Marta Quintero',
            'tel_acu' => '3112223344',
            'Direcccion_acu' => 'Calle 9 #4-12',
            'Correo_acud' => 'marta.quintero@correo.edu.co',
        ]);

        // Y además queda vinculado al estudiante con su parentesco
        $this->assertDatabaseHas('estudiante_acudiente', [
            'Documento_acud' => '900111222',
            'Documento_estu' => 20000000010,
            'id_parentesco' => $parentesco,
        ]);
    }

    public function test_store_links_an_acudiente_already_in_the_catalog(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();
        $this->seed(ParentescoSeeder::class);
        $this->seed(AcudienteSeeder::class);

        $parentesco = Parentesco::where('parentesco', 'Padre')->value('id_parentesco');

        $data = $this->datosMatricula();
        $data['acudientes_existentes'] = [
            ['documento' => '1002003005', 'parentesco' => $parentesco],
        ];

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertRedirect(route('admin.matriculas.index'));

        $this->assertDatabaseHas('estudiante_acudiente', [
            'Documento_acud' => '1002003005',
            'Documento_estu' => 20000000010,
            'id_parentesco' => $parentesco,
        ]);
    }

    public function test_the_same_acudiente_can_be_shared_between_two_students(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon('101');
        $this->crearSalon('202');
        $this->seed(ParentescoSeeder::class);
        $this->seed(AcudienteSeeder::class);

        $parentesco = Parentesco::where('parentesco', 'Madre')->value('id_parentesco');

        // El segundo estudiante del seeder es el que se matricula después
        $segundo = User::where('id_rol', 5)->where('Documento', '!=', 20000000010)->firstOrFail();

        $primera = $this->datosMatricula('101');
        $primera['acudientes_existentes'] = [['documento' => '1002003004', 'parentesco' => $parentesco]];

        $this->actingAs($admin)->post('/admin/matriculas', $primera);

        $segunda = $this->datosMatricula('202');
        $segunda['Documento_matri'] = (int) $segundo->Documento;
        $segunda['acudientes_existentes'] = [['documento' => '1002003004', 'parentesco' => $parentesco]];

        $this->actingAs($admin)->post('/admin/matriculas', $segunda);

        // El mismo acudiente queda vinculado a los dos estudiantes
        $this->assertSame(2, EstudianteAcudiente::where('Documento_acud', '1002003004')->count());
        $this->assertSame(1, Acudiente::where('Documento_acud', '1002003004')->count());
    }

    public function test_store_accepts_several_acudientes_at_once(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();
        $this->seed(ParentescoSeeder::class);
        $this->seed(AcudienteSeeder::class);

        $madre = Parentesco::where('parentesco', 'Madre')->value('id_parentesco');
        $abuelo = Parentesco::where('parentesco', 'Abuelo')->value('id_parentesco');

        $data = $this->datosMatricula();
        $data['acudientes_existentes'] = [
            ['documento' => '1002003004', 'parentesco' => $madre],
            ['documento' => '1002003006', 'parentesco' => $abuelo],
        ];
        $data['nuevos_acudientes'] = [
            [
                'documento' => '900333444',
                'nombre' => 'Pedro Gómez',
                'telefono' => '3009998877',
                'telefono_alt' => '3019991122',
                'direccion' => '',
                'correo' => 'pedro.gomez@correo.edu.co',
                'parentesco' => $madre,
            ],
        ];

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertRedirect(route('admin.matriculas.index'));

        $this->assertSame(3, EstudianteAcudiente::where('Documento_estu', 20000000010)->count());

        $this->assertDatabaseHas('estudiante_acudiente', [
            'Documento_acud' => '1002003004',
            'id_parentesco' => $madre,
        ]);
        $this->assertDatabaseHas('estudiante_acudiente', [
            'Documento_acud' => '1002003006',
            'id_parentesco' => $abuelo,
        ]);
        $this->assertDatabaseHas('estudiante_acudiente', [
            'Documento_acud' => '900333444',
            'id_parentesco' => $madre,
        ]);
    }

    public function test_store_ignores_the_blank_acudiente_rows_always_rendered_in_the_form(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();

        $data = $this->datosMatricula();

        // El formulario siempre muestra una fila vacía en cada bloque, así que se replican aquí
        $data['acudientes_existentes'] = [['documento' => '', 'parentesco' => '']];
        $data['nuevos_acudientes'] = [
            ['documento' => '', 'nombre' => '', 'telefono' => '', 'telefono_alt' => '', 'direccion' => '', 'correo' => '', 'parentesco' => ''],
        ];

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertRedirect(route('admin.matriculas.index'))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('matricula', ['Documento_matri' => 20000000010]);
        $this->assertSame(0, Acudiente::count());
        $this->assertSame(0, EstudianteAcudiente::count());
    }

    public function test_store_accepts_a_matricula_without_acudientes(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();

        $this->actingAs($admin)
            ->post('/admin/matriculas', $this->datosMatricula())
            ->assertRedirect(route('admin.matriculas.index'));

        $this->assertDatabaseHas('matricula', ['Documento_matri' => 20000000010]);
        $this->assertSame(0, EstudianteAcudiente::count());
    }

    public function test_store_rejects_a_parentesco_that_is_not_in_the_catalog(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();
        $this->seed(AcudienteSeeder::class);

        $data = $this->datosMatricula();
        $data['acudientes_existentes'] = [['documento' => '1002003004', 'parentesco' => 999]];

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertSessionHasErrors('acudientes_existentes.0.parentesco');

        $this->assertDatabaseMissing('matricula', ['Documento_matri' => 20000000010]);
        $this->assertSame(0, EstudianteAcudiente::count());
    }

    public function test_store_rejects_an_acudiente_that_is_not_in_the_catalog(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();
        $this->seed(ParentescoSeeder::class);

        $data = $this->datosMatricula();
        $data['acudientes_existentes'] = [['documento' => '999999999', 'parentesco' => 1]];

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertSessionHasErrors('acudientes_existentes.0.documento');

        $this->assertDatabaseMissing('matricula', ['Documento_matri' => 20000000010]);
    }

    public function test_store_rejects_a_new_acudiente_with_a_document_that_already_exists(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();
        $this->seed(ParentescoSeeder::class);
        $this->seed(AcudienteSeeder::class);

        $data = $this->datosMatricula();
        $data['nuevos_acudientes'] = [
            [
                'documento' => '1002003004',
                'nombre' => 'Otro Acudiente',
                'telefono' => '3000000000',
                'telefono_alt' => '',
                'direccion' => '',
                'correo' => 'otro@correo.edu.co',
                'parentesco' => 1,
            ],
        ];

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertSessionHasErrors('nuevos_acudientes.0.documento');

        $this->assertDatabaseMissing('matricula', ['Documento_matri' => 20000000010]);
        $this->assertSame(3, Acudiente::count());
    }

    public function test_store_rejects_a_new_acudiente_with_a_non_numeric_document(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();
        $this->seed(ParentescoSeeder::class);

        $data = $this->datosMatricula();
        $data['nuevos_acudientes'] = [
            [
                'documento' => 'ABC123',
                'nombre' => 'Acudiente Inválido',
                'telefono' => '3000000000',
                'telefono_alt' => '',
                'direccion' => '',
                'correo' => 'invalido@correo.edu.co',
                'parentesco' => 1,
            ],
        ];

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertSessionHasErrors('nuevos_acudientes.0.documento');
    }

    public function test_store_rejects_a_new_acudiente_with_an_invalid_email(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();
        $this->seed(ParentescoSeeder::class);

        $data = $this->datosMatricula();
        $data['nuevos_acudientes'] = [
            [
                'documento' => '900555666',
                'nombre' => 'Acudiente Sin Correo',
                'telefono' => '3000000000',
                'telefono_alt' => '',
                'direccion' => '',
                'correo' => 'correo-malo',
                'parentesco' => 1,
            ],
        ];

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertSessionHasErrors('nuevos_acudientes.0.correo');
    }

    public function test_store_rejects_the_same_acudiente_twice_in_the_same_list(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();
        $this->seed(ParentescoSeeder::class);
        $this->seed(AcudienteSeeder::class);

        $data = $this->datosMatricula();
        $data['acudientes_existentes'] = [
            ['documento' => '1002003004', 'parentesco' => 1],
            ['documento' => '1002003004', 'parentesco' => 2],
        ];

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertSessionHasErrors('acudientes_existentes.1.documento');
    }

    public function test_store_does_not_create_partial_acudientes_when_the_matricula_fails(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon('101');
        $this->seed(ParentescoSeeder::class);

        $data = $this->datosMatricula('999'); // El salón no existe, así que la matrícula se rechaza
        $data['nuevos_acudientes'] = [
            [
                'documento' => '900777888',
                'nombre' => 'Acudiente Huérfano',
                'telefono' => '3001112233',
                'telefono_alt' => '',
                'direccion' => '',
                'correo' => 'huerfano@correo.edu.co',
                'parentesco' => 1,
            ],
        ];

        $this->actingAs($admin)
            ->post('/admin/matriculas', $data)
            ->assertSessionHasErrors('Salon');

        // La transacción debe deshacer también el acudiente que se iba a crear
        $this->assertDatabaseMissing('acudiente', ['Documento_acud' => '900777888']);
        $this->assertDatabaseMissing('estudiante_acudiente', ['Documento_acud' => '900777888']);
    }

    public function test_student_relation_exposes_the_linked_acudientes_with_their_parentesco(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();
        $this->seed(ParentescoSeeder::class);
        $this->seed(AcudienteSeeder::class);

        $parentesco = Parentesco::where('parentesco', 'Tío')->value('id_parentesco');

        $data = $this->datosMatricula();
        $data['acudientes_existentes'] = [['documento' => '1002003005', 'parentesco' => $parentesco]];

        $this->actingAs($admin)->post('/admin/matriculas', $data);

        $acudientes = User::where('Documento', 20000000010)->firstOrFail()->acudientes;

        $this->assertCount(1, $acudientes);
        $this->assertSame('Jorge Alberto Herrera', $acudientes->first()->nombre);
        $this->assertSame($parentesco, (int) $acudientes->first()->pivot->id_parentesco);
    }

    public function test_acudiente_and_parentesco_factories_build_valid_records(): void
    {
        $this->crearEstadoActivo();

        $acudiente = Acudiente::factory()->create();
        $parentesco = Parentesco::factory()->create();

        $this->assertDatabaseHas('acudiente', ['Documento_acud' => $acudiente->Documento_acud]);
        $this->assertDatabaseHas('parentesco', ['id_parentesco' => $parentesco->id_parentesco]);
    }

    public function test_create_form_shows_the_acudientes_the_student_already_had(): void
    {
        $admin = $this->admin();
        $this->estudiante();
        $this->crearSalon();
        $this->seed(ParentescoSeeder::class);
        $this->seed(AcudienteSeeder::class);

        $parentesco = Parentesco::where('parentesco', 'Madre')->value('id_parentesco');

        // Se vincula un acudiente antes de abrir el formulario de la matrícula
        EstudianteAcudiente::create([
            'Documento_acud' => '1002003004',
            'Documento_estu' => 20000000010,
            'id_parentesco' => $parentesco,
        ]);

        $html = $this->actingAs($admin)->get('/admin/matriculas/create')->assertOk()->getContent();

        // El mapa de vínculos se incrusta en la vista para pintarlos sin recargar
        $this->assertStringContainsString('Carmen Rosa Salazar', $html);
        $this->assertStringContainsString('1002003004', $html);
    }
}

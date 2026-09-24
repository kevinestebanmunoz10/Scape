<?php

namespace Tests\Feature;

use App\Models\Autorizacion;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ProfesorSeeder;
use Database\Seeders\RectorSeeder;
use Database\Seeders\TipoPermisoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PermisosCrudTest extends TestCase
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
        $this->seed(AdminSeeder::class);
        $this->seed(RectorSeeder::class);

        return User::where('Documento', 10000000003)->firstOrFail();
    }

    private function crearEstudiante(int $documento = 20000000001): void
    {
        DB::table('usuario')->insert([
            'Documento' => $documento,
            'Nom_usua' => 'Estudiante Uno',
            'email' => "estudiante{$documento}@test.com",
            'Telefono' => '3000000001',
            'QR' => "QR-E{$documento}",
            'Contrasena' => 'secreto123',
            'id_rol' => 1,
            'id_Estado' => 1,
            'cod_postal' => 730001,
        ]);

        DB::table('matricula')->insert([
            'grado' => '10',
            'fecha_matri' => '2026-01-15',
            'Documento_matri' => $documento,
            'Jornada' => 'Mañana',
            'Salon' => '101',
            'Nit' => null,
        ]);
    }

    private function crearAcudiente(string $documento = '900000001'): void
    {
        DB::table('acudiente')->insert([
            'Documento_acud' => $documento,
            'nombre' => 'Acudiente Uno',
            'tel_acu' => '3000000002',
            'tel_acu2' => null,
            'Direcccion_acu' => null,
            'Correo_acud' => "acudiente{$documento}@test.com",
        ]);
    }

    private function crearTipo(string $tipo = 'Médico'): int
    {
        return DB::table('tipo_permiso')->insertGetId(['tipo' => $tipo]);
    }

    private function registrarPermiso(string $tipo = 'Médico', string $descripcion = 'Cita médica con especialista'): Autorizacion
    {
        $this->seed(RectorSeeder::class);
        $this->crearEstudiante();
        $this->crearAcudiente();
        $idTipo = $this->crearTipo($tipo);

        $idEstuAcud = DB::table('estudiante_acudiente')->insertGetId([
            'Documento_estu' => 20000000001,
            'Documento_acud' => '900000001',
            'id_parentesco' => null,
        ]);

        return Autorizacion::create([
            'Permiso' => $tipo,
            'Descripcion' => $descripcion,
            'Fecha' => '2026-09-24',
            'id_rol' => 3,
            'id_estu_acud' => $idEstuAcud,
            'Documento_auto' => null,
        ]);
    }

    public function test_guest_is_redirected_from_permisos_page(): void
    {
        $this->get('/admin/permisos')->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_permisos_page(): void
    {
        $this->actingAs($this->profesor())
            ->get('/admin/permisos')
            ->assertRedirect(route('profesor.dashboard'));
    }

    public function test_admin_can_see_permisos_page(): void
    {
        $this->seed(TipoPermisoSeeder::class);

        $this->actingAs($this->admin())
            ->get('/admin/permisos')
            ->assertOk()
            ->assertSee('Permisos registrados')
            ->assertSee('Tipos de permiso')
            ->assertSee('No hay permisos de salida registrados.')
            ->assertDontSee('Registrar permiso de salida');
    }

    public function test_guest_is_redirected_from_tipos_permiso_page(): void
    {
        $this->get('/admin/permisos/tipos')->assertRedirect('/login');
    }

    public function test_admin_can_see_tipos_permiso_page(): void
    {
        $this->seed(TipoPermisoSeeder::class);

        $this->actingAs($this->admin())
            ->get(route('admin.permisos.tipos'))
            ->assertOk()
            ->assertSee('Administra los tipos de permiso de salida disponibles.')
            ->assertSee('Médico')
            ->assertSee('Calamidad');
    }

    public function test_admin_can_create_a_tipo_permiso(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.permisos.tipos.store'), ['tipo' => 'Trámite personal'])
            ->assertRedirect(route('admin.permisos.tipos'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('tipo_permiso', ['tipo' => 'Trámite personal']);
    }

    public function test_store_tipo_requires_a_name(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.permisos.tipos.store'), ['tipo' => ''])
            ->assertSessionHasErrors('tipo');
    }

    public function test_admin_can_delete_a_tipo_permiso_not_in_use(): void
    {
        $this->admin();
        $id = $this->crearTipo('Calamidad');

        $this->actingAs($this->admin())
            ->delete(route('admin.permisos.tipos.destroy', $id))
            ->assertRedirect(route('admin.permisos.tipos'))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('tipo_permiso', ['id_tipo_permiso' => $id]);
    }

    public function test_admin_cannot_delete_a_tipo_permiso_in_use(): void
    {
        $this->admin();
        $this->registrarPermiso('Médico');

        $this->actingAs($this->admin())
            ->delete(route('admin.permisos.tipos.destroy', $this->crearTipo('Médico')))
            ->assertRedirect(route('admin.permisos.tipos'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('tipo_permiso', ['tipo' => 'Médico']);
    }

    public function test_rector_can_register_a_permiso_pendiente(): void
    {
        $this->rector();
        $this->crearEstudiante();
        $this->crearAcudiente();
        $idTipo = $this->crearTipo('Médico');

        $this->actingAs($this->rector())
            ->post(route('rector.permisos.store'), [
                'Documento_estu' => 20000000001,
                'Documento_acud' => '900000001',
                'id_tipo_permiso' => $idTipo,
                'Descripcion' => 'Cita médica con especialista.',
            ])
            ->assertRedirect(route('rector.dashboard'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('estudiante_acudiente', [
            'Documento_estu' => 20000000001,
            'Documento_acud' => '900000001',
        ]);
        $this->assertDatabaseHas('autorizacion', [
            'Permiso' => 'Médico',
            'Descripcion' => 'Cita médica con especialista.',
            'id_rol' => 3,
            'Documento_auto' => null,
        ]);
    }

    public function test_admin_cannot_register_a_permiso(): void
    {
        $this->admin();

        $this->actingAs($this->admin())
            ->post('/admin/permisos')
            ->assertStatus(405);
    }

    public function test_register_requiere_datos_completos(): void
    {
        $this->rector();

        $this->actingAs($this->rector())
            ->post(route('rector.permisos.store'), [
                'Documento_estu' => '',
                'Documento_acud' => '',
                'id_tipo_permiso' => '',
                'Descripcion' => '',
            ])
            ->assertSessionHasErrors(['Documento_estu', 'Documento_acud', 'id_tipo_permiso', 'Descripcion']);
    }

    public function test_register_rejects_a_student_without_matricula(): void
    {
        $this->rector();
        $idTipo = $this->crearTipo('Médico');
        DB::table('usuario')->insert([
            'Documento' => 20000000009,
            'Nom_usua' => 'Estudiante Sin Matricula',
            'email' => 'sinsm@test.com',
            'Telefono' => '3000000009',
            'QR' => 'QR-ESM',
            'Contrasena' => 'secreto123',
            'id_rol' => 1,
            'id_Estado' => 1,
            'cod_postal' => 730001,
        ]);

        $this->actingAs($this->rector())
            ->post(route('rector.permisos.store'), [
                'Documento_estu' => 20000000009,
                'Documento_acud' => '900000001',
                'id_tipo_permiso' => $idTipo,
                'Descripcion' => 'Permiso sin matrícula.',
            ])
            ->assertSessionHasErrors('Documento_estu');
    }

    public function test_admin_sees_registered_permisos_in_the_list(): void
    {
        $this->admin();
        $this->registrarPermiso('Médico', 'Cita médica general.');

        $this->actingAs($this->admin())
            ->get('/admin/permisos')
            ->assertOk()
            ->assertSee('Estudiante Uno')
            ->assertSee('Acudiente Uno')
            ->assertSee('Médico')
            ->assertSee('Cita médica general.')
            ->assertSee('Pendiente');
    }

    public function test_registrar_mismo_par_no_duplica_el_vinculo(): void
    {
        $this->rector();
        $this->crearEstudiante();
        $this->crearAcudiente();
        $idTipo = $this->crearTipo('Médico');

        foreach (['Primer permiso.', 'Segundo permiso.'] as $descripcion) {
            $this->actingAs($this->rector())
                ->post(route('rector.permisos.store'), [
                    'Documento_estu' => 20000000001,
                    'Documento_acud' => '900000001',
                    'id_tipo_permiso' => $idTipo,
                    'Descripcion' => $descripcion,
                ]);
        }

        $this->assertSame(1, DB::table('estudiante_acudiente')
            ->where('Documento_estu', 20000000001)
            ->where('Documento_acud', '900000001')
            ->count());
        $this->assertSame(2, DB::table('autorizacion')->count());
    }

    public function test_admin_can_see_permiso_edit_page(): void
    {
        $this->admin();
        $permiso = $this->registrarPermiso();

        $this->actingAs($this->admin())
            ->get(route('admin.permisos.edit', $permiso))
            ->assertOk()
            ->assertSee('Editar permiso')
            ->assertSee('Guardar cambios')
            ->assertSee('Estudiante Uno')
            ->assertSee('Médico')
            ->assertSee('Cita médica con especialista');
    }

    public function test_admin_can_update_a_permiso(): void
    {
        $this->admin();
        $this->registrarPermiso('Médico', 'Descripción original.');
        $idTipoNuevo = $this->crearTipo('Calamidad');
        $permiso = Autorizacion::firstOrFail();

        $this->actingAs($this->admin())
            ->put(route('admin.permisos.update', $permiso), [
                'Documento_estu' => 20000000001,
                'Documento_acud' => '900000001',
                'id_tipo_permiso' => $idTipoNuevo,
                'Descripcion' => 'Descripción actualizada.',
            ])
            ->assertRedirect(route('admin.permisos'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('autorizacion', [
            'id_autorizacion' => $permiso->id_autorizacion,
            'Permiso' => 'Calamidad',
            'Descripcion' => 'Descripción actualizada.',
        ]);
    }

    public function test_admin_can_delete_a_permiso(): void
    {
        $this->admin();
        $permiso = $this->registrarPermiso();

        $this->actingAs($this->admin())
            ->delete(route('admin.permisos.destroy', $permiso))
            ->assertRedirect(route('admin.permisos'))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('autorizacion', ['id_autorizacion' => $permiso->id_autorizacion]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Equipo;
use App\Models\PrestamoEquipo;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ProfesorSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EquipoCrudTest extends TestCase
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

    private function crearMarca(): int
    {
        return DB::table('marca')->insertGetId(['marca' => 'HP']);
    }

    private function crearTipo(): int
    {
        return DB::table('tipo_equipo')->insertGetId(['tipo' => 'Portátil']);
    }

    private function crearEquipo(string $serial = 'SN-TEST-001'): Equipo
    {
        return Equipo::create([
            'serial_equi' => $serial,
            'id_t_equip' => $this->crearTipo(),
            'id_Marca' => $this->crearMarca(),
            'Color' => 'Negro',
        ]);
    }

    private function datosEquipo(): array
    {
        return [
            'serial_equi' => 'SN-NUEVO-001',
            'id_t_equip' => $this->crearTipo(),
            'id_Marca' => $this->crearMarca(),
            'Color' => 'Blanco',
            'imagen' => null,
        ];
    }

    public function test_guest_is_redirected_from_equipment_index(): void
    {
        $this->get('/admin/equipos')->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_equipment_index(): void
    {
        $this->actingAs($this->profesor())
            ->get('/admin/equipos')
            ->assertRedirect(route('profesor.dashboard'));
    }

    public function test_admin_can_see_equipment_index(): void
    {
        $admin = $this->admin();
        $this->crearEquipo();

        $this->actingAs($admin)
            ->get('/admin/equipos')
            ->assertOk()
            ->assertSee('Nuevo equipo')
            ->assertSee('SN-TEST-001');
    }

    public function test_admin_can_search_equipment_by_serial(): void
    {
        $this->admin();
        $this->crearEquipo('SN-HP-999');
        $this->crearEquipo('SN-OTRO-888');

        $this->actingAs($this->admin())
            ->get('/admin/equipos?buscar=SN-HP-999')
            ->assertOk()
            ->assertSee('SN-HP-999')
            ->assertDontSee('SN-OTRO-888');
    }

    public function test_equipment_search_shows_a_message_when_there_are_no_matches(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/equipos?buscar=NoExiste')
            ->assertOk()
            ->assertSee('No se encontraron equipos');
    }

    public function test_admin_can_create_equipment(): void
    {
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post('/admin/equipos', $this->datosEquipo())
            ->assertRedirect(route('admin.equipos.index'));

        $this->assertDatabaseHas('equipo', [
            'serial_equi' => 'SN-NUEVO-001',
            'Color' => 'Blanco',
        ]);
    }

    public function test_admin_can_assign_a_user_to_equipment(): void
    {
        $data = $this->datosEquipo();
        $data['Documento'] = 10000000001;

        $this->actingAs($this->admin())
            ->post('/admin/equipos', $data)
            ->assertRedirect(route('admin.equipos.index'));

        $this->assertDatabaseHas('equipo', [
            'serial_equi' => 'SN-NUEVO-001',
            'Documento' => 10000000001,
        ]);
    }

    public function test_store_rejects_an_unassigned_existing_user(): void
    {
        $data = $this->datosEquipo();
        $data['Documento'] = 99999999999;

        $this->actingAs($this->admin())
            ->post('/admin/equipos', $data)
            ->assertSessionHasErrors('Documento');

        $this->assertDatabaseMissing('equipo', ['serial_equi' => 'SN-NUEVO-001']);
    }

    public function test_admin_can_upload_an_image_when_creating_equipment(): void
    {
        Storage::fake('public');

        $data = $this->datosEquipo();
        $data['imagen'] = UploadedFile::fake()->create('equipo.jpg', 50, 'image/jpeg');

        $this->actingAs($this->admin())
            ->post('/admin/equipos', $data)
            ->assertRedirect(route('admin.equipos.index'));

        $equipo = Equipo::where('serial_equi', 'SN-NUEVO-001')->firstOrFail();
        $this->assertNotNull($equipo->imagen);
        Storage::disk('public')->assertExists($equipo->imagen);
    }

    public function test_store_rejects_a_non_image_file(): void
    {
        Storage::fake('public');

        $data = $this->datosEquipo();
        $data['imagen'] = UploadedFile::fake()->create('documento.pdf', 100);

        $this->actingAs($this->admin())
            ->post('/admin/equipos', $data)
            ->assertSessionHasErrors('imagen');

        $this->assertDatabaseMissing('equipo', ['serial_equi' => 'SN-NUEVO-001']);
    }

    public function test_admin_can_see_the_user_filter_in_create_form(): void
    {
        $this->admin();

        $this->actingAs($this->admin())
            ->get('/admin/equipos/create')
            ->assertOk()
            ->assertSee('Filtrar usuario')
            ->assertSee('Usuario asignado')
            ->assertSee('Administrador SCAPE');
    }

    public function test_admin_can_see_assigned_user_in_index(): void
    {
        $this->admin();
        $equipo = $this->crearEquipo();
        $equipo->update(['Documento' => 10000000001]);

        $this->actingAs($this->admin())
            ->get('/admin/equipos')
            ->assertOk()
            ->assertSee('Administrador SCAPE');
    }

    public function test_admin_can_filter_unassigned_equipment(): void
    {
        $this->admin();
        $asignado = $this->crearEquipo('SN-ASIG-001');
        $asignado->update(['Documento' => 10000000001]);
        $this->crearEquipo('SN-LIBRE-001');

        $this->actingAs($this->admin())
            ->get('/admin/equipos?asignacion=sin')
            ->assertOk()
            ->assertSee('SN-LIBRE-001')
            ->assertDontSee('SN-ASIG-001');
    }

    public function test_admin_can_filter_assigned_equipment(): void
    {
        $this->admin();
        $asignado = $this->crearEquipo('SN-ASIG-001');
        $asignado->update(['Documento' => 10000000001]);
        $this->crearEquipo('SN-LIBRE-001');

        $this->actingAs($this->admin())
            ->get('/admin/equipos?asignacion=con')
            ->assertOk()
            ->assertSee('SN-ASIG-001')
            ->assertDontSee('SN-LIBRE-001');
    }

    public function test_admin_can_remove_the_assigned_user_when_editing(): void
    {
        $this->admin();
        $equipo = $this->crearEquipo();
        $equipo->update(['Documento' => 10000000001]);

        $this->actingAs($this->admin())
            ->put('/admin/equipos/'.$equipo->serial_equi, [
                'id_t_equip' => $equipo->id_t_equip,
                'id_Marca' => $equipo->id_Marca,
                'Color' => $equipo->Color,
                'imagen' => null,
                'Documento' => '',
            ])
            ->assertRedirect(route('admin.equipos.index'));

        $this->assertDatabaseHas('equipo', [
            'serial_equi' => $equipo->serial_equi,
            'Documento' => null,
        ]);
    }

    public function test_store_validates_required_fields(): void
    {
        $this->actingAs($this->admin())
            ->post('/admin/equipos', [])
            ->assertSessionHasErrors(['serial_equi', 'id_t_equip', 'id_Marca']);
    }

    public function test_store_requires_a_unique_serial(): void
    {
        $this->admin();
        $this->crearEquipo('SN-HP-999');

        $data = $this->datosEquipo();
        $data['serial_equi'] = 'SN-HP-999';

        $this->actingAs($this->admin())
            ->post('/admin/equipos', $data)
            ->assertSessionHasErrors('serial_equi');
    }

    public function test_admin_can_view_equipment(): void
    {
        $this->crearEquipo();

        $this->actingAs($this->admin())
            ->get('/admin/equipos/SN-TEST-001')
            ->assertOk()
            ->assertSee('SN-TEST-001');
    }

    public function test_admin_can_update_equipment(): void
    {
        $this->admin();
        $equipo = $this->crearEquipo();

        $this->actingAs($this->admin())
            ->put('/admin/equipos/'.$equipo->serial_equi, [
                'id_t_equip' => $equipo->id_t_equip,
                'id_Marca' => $equipo->id_Marca,
                'Color' => 'Plata',
                'imagen' => null,
                'Documento' => 10000000001,
            ])
            ->assertRedirect(route('admin.equipos.index'));

        $this->assertDatabaseHas('equipo', [
            'serial_equi' => 'SN-TEST-001',
            'Color' => 'Plata',
            'Documento' => 10000000001,
        ]);
    }

    public function test_admin_can_delete_equipment_without_loans(): void
    {
        $this->admin();
        $equipo = $this->crearEquipo();

        $this->actingAs($this->admin())
            ->delete('/admin/equipos/'.$equipo->serial_equi)
            ->assertRedirect(route('admin.equipos.index'));

        $this->assertDatabaseMissing('equipo', ['serial_equi' => $equipo->serial_equi]);
    }

    public function test_admin_cannot_delete_equipment_with_loans(): void
    {
        $this->admin();
        $equipo = $this->crearEquipo();

        PrestamoEquipo::create([
            'f_Prestamo' => now(),
            'Documento_pres' => 10000000001,
            'serial_equi' => $equipo->serial_equi,
            'id_estado' => 1,
        ]);

        $this->actingAs($this->admin())
            ->delete('/admin/equipos/'.$equipo->serial_equi)
            ->assertRedirect(route('admin.equipos.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('equipo', ['serial_equi' => $equipo->serial_equi]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Equipo;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ProfesorSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CatalogosCrudTest extends TestCase
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

    private function crearMarca(string $nombre = 'HP'): int
    {
        return DB::table('marca')->insertGetId(['marca' => $nombre]);
    }

    private function crearTipo(string $nombre = 'Portátil'): int
    {
        return DB::table('tipo_equipo')->insertGetId(['tipo' => $nombre]);
    }

    private function crearEquipoConDatos(int $idMarca, int $idTipo): Equipo
    {
        return Equipo::create([
            'serial_equi' => 'SN-CAT-001',
            'id_t_equip' => $idTipo,
            'id_Marca' => $idMarca,
            'Color' => 'Negro',
        ]);
    }

    public function test_guest_is_redirected_from_catalogos_page(): void
    {
        $this->get('/admin/equipos/catalogos')->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_catalogos_page(): void
    {
        $this->actingAs($this->profesor())
            ->get('/admin/equipos/catalogos')
            ->assertRedirect(route('profesor.dashboard'));
    }

    public function test_admin_can_see_catalogos_page(): void
    {
        $this->admin();
        $this->crearMarca('Lenovo');
        $this->crearTipo('Video beam');

        $this->actingAs($this->admin())
            ->get('/admin/equipos/catalogos')
            ->assertOk()
            ->assertSee('Administra las marcas y los tipos de equipo disponibles.')
            ->assertSee('Lenovo')
            ->assertSee('Video beam');
    }

    public function test_admin_can_create_a_marca(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.equipos.catalogos.marcas.store'), ['marca' => 'Acer'])
            ->assertRedirect(route('admin.equipos.catalogos'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('marca', ['marca' => 'Acer']);
    }

    public function test_store_marca_requires_a_name(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.equipos.catalogos.marcas.store'), ['marca' => ''])
            ->assertSessionHasErrors('marca');
    }

    public function test_admin_can_delete_a_marca_without_equipment(): void
    {
        $this->admin();
        $id = $this->crearMarca();

        $this->actingAs($this->admin())
            ->delete(route('admin.equipos.catalogos.marcas.destroy', $id))
            ->assertRedirect(route('admin.equipos.catalogos'))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('marca', ['id_marca' => $id]);
    }

    public function test_admin_cannot_delete_a_marca_in_use(): void
    {
        $this->admin();
        $idMarca = $this->crearMarca();
        $this->crearEquipoConDatos($idMarca, $this->crearTipo());

        $this->actingAs($this->admin())
            ->delete(route('admin.equipos.catalogos.marcas.destroy', $idMarca))
            ->assertRedirect(route('admin.equipos.catalogos'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('marca', ['id_marca' => $idMarca]);
    }

    public function test_admin_can_create_a_tipo(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.equipos.catalogos.tipos.store'), ['tipo' => 'Tablet'])
            ->assertRedirect(route('admin.equipos.catalogos'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('tipo_equipo', ['tipo' => 'Tablet']);
    }

    public function test_store_tipo_requires_a_name(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.equipos.catalogos.tipos.store'), ['tipo' => ''])
            ->assertSessionHasErrors('tipo');
    }

    public function test_admin_can_delete_a_tipo_without_equipment(): void
    {
        $this->admin();
        $id = $this->crearTipo();

        $this->actingAs($this->admin())
            ->delete(route('admin.equipos.catalogos.tipos.destroy', $id))
            ->assertRedirect(route('admin.equipos.catalogos'))
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('tipo_equipo', ['id_t_equip' => $id]);
    }

    public function test_admin_cannot_delete_a_tipo_in_use(): void
    {
        $this->admin();
        $idTipo = $this->crearTipo();
        $this->crearEquipoConDatos($this->crearMarca(), $idTipo);

        $this->actingAs($this->admin())
            ->delete(route('admin.equipos.catalogos.tipos.destroy', $idTipo))
            ->assertRedirect(route('admin.equipos.catalogos'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('tipo_equipo', ['id_t_equip' => $idTipo]);
    }
}

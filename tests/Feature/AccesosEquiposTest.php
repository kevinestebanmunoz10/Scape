<?php

namespace Tests\Feature;

use App\Models\Equipo;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\ProfesorSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AccesosEquiposTest extends TestCase
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

    private function crearMarca(string $nombre = 'HP'): void
    {
        DB::table('marca')->insert(['marca' => $nombre]);
    }

    private function crearTipo(string $nombre = 'Portátil'): void
    {
        DB::table('tipo_equipo')->insert(['tipo' => $nombre]);
    }

    private function crearAcceso(string $serial = 'SN-ACC-001', ?string $fSalida = null, string $fEntrada = 'now'): void
    {
        $this->crearMarca();
        $this->crearTipo();

        Equipo::create([
            'serial_equi' => $serial,
            'id_t_equip' => DB::table('tipo_equipo')->value('id_t_equip'),
            'id_Marca' => DB::table('marca')->value('id_marca'),
            'Color' => 'Negro',
        ]);

        DB::table('acceso_equi')->insert([
            'serial_equi' => $serial,
            'Documento' => 10000000001,
            'f_entrada' => $fEntrada === 'now' ? now()->format('Y-m-d H:i:s') : $fEntrada,
            'f_salida' => $fSalida,
        ]);
    }

    public function test_guest_is_redirected_from_equipos_access_page(): void
    {
        $this->get('/admin/accesos/equipos')->assertRedirect('/login');
    }

    public function test_non_admin_cannot_access_equipos_page(): void
    {
        $this->actingAs($this->profesor())
            ->get('/admin/accesos/equipos')
            ->assertRedirect(route('profesor.dashboard'));
    }

    public function test_admin_can_see_personas_access_page_with_tabs(): void
    {
        $this->admin();

        $this->actingAs($this->admin())
            ->get(route('admin.accesos'))
            ->assertOk()
            ->assertSee('access-tabs')
            ->assertSee('Accesos de equipos');
    }

    public function test_admin_can_see_equipos_access_page(): void
    {
        $this->admin();
        $this->crearAcceso();

        $this->actingAs($this->admin())
            ->get(route('admin.accesos.equipos'))
            ->assertOk()
            ->assertSee('access-tabs')
            ->assertSee('Registro de accesos de equipos')
            ->assertSee('Equipos únicos')
            ->assertSee('SN-ACC-001')
            ->assertSee('Portátil')
            ->assertSee('HP')
            ->assertSee('Negro')
            ->assertSee('Dentro')
            ->assertSee('Accesos de personas');
    }

    public function test_equipment_out_shows_fuera_state(): void
    {
        $this->admin();
        $this->crearAcceso(fSalida: now()->format('Y-m-d H:i:s'));

        $this->actingAs($this->admin())
            ->get(route('admin.accesos.equipos'))
            ->assertSee('estado-fuera')
            ->assertDontSee('estado-dentro');
    }

    public function test_search_filters_equipment_access_by_serial(): void
    {
        $this->admin();
        $this->crearAcceso('SN-ACC-001');
        $this->crearAcceso('SN-ACC-999');

        $this->actingAs($this->admin())
            ->get(route('admin.accesos.equipos', ['buscar' => 'SN-ACC-999']))
            ->assertSee('SN-ACC-999')
            ->assertDontSee('SN-ACC-001');
    }

    public function test_date_range_filters_equipment_access(): void
    {
        $this->admin();
        $this->crearAcceso('SN-ACC-001', fEntrada: now()->subDays(5)->format('Y-m-d H:i:s'));

        $this->actingAs($this->admin())
            ->get(route('admin.accesos.equipos', ['desde' => now()->format('Y-m-d'), 'hasta' => now()->format('Y-m-d')]))
            ->assertOk()
            ->assertDontSee('SN-ACC-001');
    }
}

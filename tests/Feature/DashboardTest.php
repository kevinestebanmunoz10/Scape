<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_requires_authentication(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/admin/login');
    }

    public function test_dashboard_shows_data_from_the_tables(): void
    {
        $this->seed(AdminSeeder::class);

        DB::table('acceso_usu')->insert([
            'f_ingreso' => now(),
            'f_salida' => null,
            'Documento_acces' => 10000000001,
            'id_Estado_usu' => 1,
        ]);

        DB::table('tipo_equipo')->insert(['id_t_equip' => 1, 'tipo' => 'Portátil']);
        DB::table('marca')->insert(['id_marca' => 1, 'marca' => 'DELL']);
        DB::table('equipo')->insert([
            'serial_equi' => 'SN-4032',
            'id_t_equip' => 1,
            'id_Marca' => 1,
            'Color' => 'Negro',
        ]);
        DB::table('prestamo_equipo')->insert([
            'f_Prestamo' => now(),
            'f_devolucion' => null,
            'Documento_pres' => 10000000001,
            'serial_equi' => 'SN-4032',
            'id_estado' => 1,
        ]);

        $admin = User::where('Documento', 10000000001)->firstOrFail();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk()
            ->assertSee('Usuarios activos')
            ->assertSee('0 inactivos')
            ->assertSee('1 entradas | 0 salidas')
            ->assertSee('Administrador SCAPE')
            ->assertSee('Portátil DELL - SN-4032');
    }

    public function test_dashboard_filters_data_by_date_range(): void
    {
        $this->seed(AdminSeeder::class);

        DB::table('acceso_usu')->insert([
            'f_ingreso' => now()->subYear(),
            'f_salida' => null,
            'Documento_acces' => 10000000001,
            'id_Estado_usu' => 1,
        ]);

        DB::table('acceso_usu')->insert([
            'f_ingreso' => now()->subDay(),
            'f_salida' => null,
            'Documento_acces' => 10000000001,
            'id_Estado_usu' => 1,
        ]);

        $admin = User::where('Documento', 10000000001)->firstOrFail();

        $enRango = $this->actingAs($admin)->get('/admin/dashboard?desde='.now()->subDays(3)->format('Y-m-d').'&hasta='.now()->format('Y-m-d'));
        $enRango->assertOk()->assertSee('1 entradas | 0 salidas');

        $fueraDeRango = $this->actingAs($admin)->get('/admin/dashboard?desde=2000-01-01&hasta=2000-01-31');
        $fueraDeRango->assertOk()->assertSee('0 entradas | 0 salidas');
    }
}

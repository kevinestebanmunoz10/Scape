<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\VigilanteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SalidaTest extends TestCase
{
    use RefreshDatabase;

    private const ADMIN = 10000000001;

    private const VISITANTE = '1098765432';

    private function vigilante(): User
    {
        $this->seed(AdminSeeder::class);
        $this->seed(VigilanteSeeder::class);

        return User::where('Documento', 10000000004)->firstOrFail();
    }

    private function visitante(): void
    {
        DB::table('visitantes')->insert([
            'Docu_visi' => self::VISITANTE,
            'Nom_visi' => 'Laura Gomez',
            'tel_visi' => '3201234567',
            'correo_visi' => 'laura@example.com',
        ]);
    }

    private function abrirIngresoUsuario(): void
    {
        DB::table('acceso_usu')->insert([
            'f_ingreso' => now(),
            'f_salida' => null,
            'Documento_acces' => self::ADMIN,
            'id_Estado_usu' => 1,
        ]);
    }

    public function test_guest_is_redirected_from_exit_form(): void
    {
        $this->get('/vigilante/salida')->assertRedirect('/login');
    }

    public function test_admin_cannot_access_exit_form(): void
    {
        $this->seed(AdminSeeder::class);
        $admin = User::where('Documento', self::ADMIN)->firstOrFail();

        $this->actingAs($admin)
            ->get('/vigilante/salida')
            ->assertRedirect(route('dashboard'));
    }

    public function test_vigilante_can_view_exit_form(): void
    {
        $this->actingAs($this->vigilante())
            ->get('/vigilante/salida')
            ->assertOk()
            ->assertSee('Registrar salida')
            ->assertSee('Buscar');
    }

    public function test_dashboard_exit_card_links_to_exit_form(): void
    {
        $this->actingAs($this->vigilante())
            ->get('/vigilante/dashboard')
            ->assertOk()
            ->assertSee(route('vigilante.salida'));
    }

    public function test_buscar_finds_person_with_open_ingreso(): void
    {
        $vigilante = $this->vigilante();
        $this->abrirIngresoUsuario();

        $this->actingAs($vigilante)
            ->post('/vigilante/salida/buscar', ['tipo' => 'usuario', 'documento' => self::ADMIN])
            ->assertRedirect(route('vigilante.salida'))
            ->assertSessionHas('persona');
    }

    public function test_exit_form_renders_confirmation_card(): void
    {
        $this->actingAs($this->vigilante())
            ->withSession(['persona' => [
                'tipo' => 'usuario',
                'documento' => (string) self::ADMIN,
                'nombre' => 'Administrador SCAPE',
                'ingreso' => now()->toDateTimeString(),
            ]])
            ->get('/vigilante/salida')
            ->assertOk()
            ->assertSee('Administrador SCAPE')
            ->assertSee('Confirmar salida');
    }

    public function test_buscar_warns_when_person_has_no_open_ingreso(): void
    {
        $this->actingAs($this->vigilante())
            ->post('/vigilante/salida/buscar', ['tipo' => 'usuario', 'documento' => self::ADMIN])
            ->assertRedirect(route('vigilante.salida'))
            ->assertSessionHas('error', fn ($mensaje) => str_contains($mensaje, 'no tiene un ingreso abierto'));
    }

    public function test_buscar_returns_error_when_person_not_found(): void
    {
        $this->actingAs($this->vigilante())
            ->post('/vigilante/salida/buscar', ['tipo' => 'usuario', 'documento' => 99999999999])
            ->assertRedirect(route('vigilante.salida'))
            ->assertSessionHas('error');
    }

    public function test_documento_is_required(): void
    {
        $this->actingAs($this->vigilante())
            ->post('/vigilante/salida/buscar', ['tipo' => 'usuario', 'documento' => ''])
            ->assertSessionHasErrors('documento');
    }

    public function test_store_closes_open_usuario_ingreso(): void
    {
        $vigilante = $this->vigilante();
        $this->abrirIngresoUsuario();

        $this->actingAs($vigilante)
            ->post('/vigilante/salida', ['tipo' => 'usuario', 'documento' => self::ADMIN])
            ->assertRedirect(route('vigilante.salida'))
            ->assertSessionHas('status');

        $registro = DB::table('acceso_usu')->where('Documento_acces', self::ADMIN)->first();
        $this->assertNotNull($registro->f_salida);
    }

    public function test_store_closes_open_visitante_ingreso(): void
    {
        $vigilante = $this->vigilante();
        $this->visitante();

        DB::table('acceso_visi')->insert([
            'f_ingreso' => now(),
            'f_salida' => null,
            'Documento_visi' => self::VISITANTE,
            'id_Estado_visi' => 1,
            'Lugar' => 'Rectoria',
        ]);

        $this->actingAs($vigilante)
            ->post('/vigilante/salida', ['tipo' => 'visitante', 'documento' => self::VISITANTE])
            ->assertRedirect(route('vigilante.salida'));

        $registro = DB::table('acceso_visi')->where('Documento_visi', self::VISITANTE)->first();
        $this->assertNotNull($registro->f_salida);
    }

    public function test_store_blocks_when_there_is_no_open_ingreso(): void
    {
        $this->actingAs($this->vigilante())
            ->post('/vigilante/salida', ['tipo' => 'usuario', 'documento' => self::ADMIN])
            ->assertRedirect(route('vigilante.salida'))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('acceso_usu', 0);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Database\Seeders\VigilanteSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class IngresoTest extends TestCase
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

    public function test_guest_is_redirected_from_entry_form(): void
    {
        $this->get('/vigilante/entrada')->assertRedirect('/login');
    }

    public function test_admin_cannot_access_entry_form(): void
    {
        $this->seed(AdminSeeder::class);
        $admin = User::where('Documento', self::ADMIN)->firstOrFail();

        $this->actingAs($admin)
            ->get('/vigilante/entrada')
            ->assertRedirect(route('dashboard'));
    }

    public function test_vigilante_can_view_entry_form(): void
    {
        $this->actingAs($this->vigilante())
            ->get('/vigilante/entrada')
            ->assertOk()
            ->assertSee('Registrar ingreso')
            ->assertSee('Buscar');
    }

    public function test_dashboard_entry_card_links_to_entry_form(): void
    {
        $this->actingAs($this->vigilante())
            ->get('/vigilante/dashboard')
            ->assertOk()
            ->assertSee(route('vigilante.entrada'));
    }

    public function test_buscar_finds_person_and_flashes_confirmation(): void
    {
        $this->actingAs($this->vigilante())
            ->post('/vigilante/entrada/buscar', ['tipo' => 'usuario', 'documento' => self::ADMIN])
            ->assertRedirect(route('vigilante.entrada'))
            ->assertSessionHas('persona');
    }

    public function test_entry_form_renders_confirmation_card(): void
    {
        $this->actingAs($this->vigilante())
            ->withSession(['persona' => [
                'tipo' => 'usuario',
                'documento' => (string) self::ADMIN,
                'nombre' => 'Administrador SCAPE',
            ]])
            ->get('/vigilante/entrada')
            ->assertOk()
            ->assertSee('Administrador SCAPE')
            ->assertSee('Confirmar ingreso');
    }

    public function test_buscar_returns_error_when_person_not_found(): void
    {
        $this->actingAs($this->vigilante())
            ->post('/vigilante/entrada/buscar', ['tipo' => 'usuario', 'documento' => 99999999999])
            ->assertRedirect(route('vigilante.entrada'))
            ->assertSessionHas('error');
    }

    public function test_buscar_warns_when_ingreso_is_open(): void
    {
        $vigilante = $this->vigilante();
        $this->abrirIngresoUsuario();

        $this->actingAs($vigilante)
            ->post('/vigilante/entrada/buscar', ['tipo' => 'usuario', 'documento' => self::ADMIN])
            ->assertRedirect(route('vigilante.entrada'))
            ->assertSessionHas('error', fn ($mensaje) => str_contains($mensaje, 'ingreso abierto'));
    }

    public function test_documento_is_required(): void
    {
        $this->actingAs($this->vigilante())
            ->post('/vigilante/entrada/buscar', ['tipo' => 'usuario', 'documento' => ''])
            ->assertSessionHasErrors('documento');
    }

    public function test_store_registers_usuario_ingreso(): void
    {
        $this->actingAs($this->vigilante())
            ->post('/vigilante/entrada', ['tipo' => 'usuario', 'documento' => self::ADMIN])
            ->assertRedirect(route('vigilante.entrada'))
            ->assertSessionHas('status');

        $this->assertDatabaseHas('acceso_usu', [
            'Documento_acces' => self::ADMIN,
            'f_salida' => null,
        ]);
    }

    public function test_store_registers_estudiante_ingreso(): void
    {
        $this->actingAs($this->vigilante())
            ->post('/vigilante/entrada', ['tipo' => 'estudiante', 'documento' => self::ADMIN])
            ->assertRedirect(route('vigilante.entrada'));

        $this->assertDatabaseHas('acceso_estu', [
            'Documento_estu' => self::ADMIN,
            'f_salida' => null,
        ]);
    }

    public function test_store_registers_visitante_ingreso(): void
    {
        $this->visitante();

        $this->actingAs($this->vigilante())
            ->post('/vigilante/entrada', ['tipo' => 'visitante', 'documento' => self::VISITANTE])
            ->assertRedirect(route('vigilante.entrada'));

        $this->assertDatabaseHas('acceso_visi', [
            'Documento_visi' => self::VISITANTE,
            'f_salida' => null,
        ]);
    }

    public function test_store_blocks_duplicate_open_ingreso(): void
    {
        $vigilante = $this->vigilante();
        $this->abrirIngresoUsuario();

        $this->actingAs($vigilante)
            ->post('/vigilante/entrada', ['tipo' => 'usuario', 'documento' => self::ADMIN])
            ->assertRedirect(route('vigilante.entrada'))
            ->assertSessionHas('error');

        $this->assertDatabaseCount('acceso_usu', 1);
    }
}

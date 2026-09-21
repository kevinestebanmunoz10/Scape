<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_visiting_home_logs_out_an_authenticated_user(): void
    {
        $this->seed(AdminSeeder::class);

        $admin = User::where('Documento', 10000000001)->firstOrFail();

        $response = $this->actingAs($admin)->get('/');

        $response->assertOk()
            ->assertSee('Iniciar sesión')
            ->assertDontSee('Administrador SCAPE');

        $this->assertGuest();
    }

    public function test_guest_can_see_the_login_button_on_home(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Iniciar sesión');
    }
}

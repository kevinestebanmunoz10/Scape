<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_visiting_home_redirects_an_authenticated_user_to_their_panel(): void
    {
        $this->seed(AdminSeeder::class);

        $admin = User::where('Documento', 10000000001)->firstOrFail();

        $this->actingAs($admin)
            ->get('/')
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($admin);
    }

    public function test_guest_can_see_the_login_button_on_home(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Iniciar sesión');
    }
}

<?php

namespace Tests\Feature;

use App\Mail\PasswordResetCode;
use App\Models\User;
use Database\Seeders\AdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_send_code_requires_existing_email(): void
    {
        $this->get('/admin/password/verify')->assertRedirect('/admin/password/reset');

        $response = $this->post('/admin/password/email', ['email' => 'noexiste@gmail.com']);

        $response->assertSessionHasErrors('email');
    }

    public function test_user_can_reset_password_with_eight_digit_code(): void
    {
        Mail::fake();

        $this->seed(AdminSeeder::class);

        $user = User::where('email', 'kevinestebanmunoz10@gmail.com')->first();

        $this->from('/admin/password/reset')
            ->post('/admin/password/email', ['email' => $user->email])
            ->assertRedirect('/admin/password/verify');

        Mail::assertSent(PasswordResetCode::class);

        $this->get('/admin/password/verify')->assertOk();

        $this->post('/admin/password/verify', ['code' => '00000000'])
            ->assertSessionHasErrors('code');

        $code = DB::table('password_reset_tokens')->where('email', $user->email)->value('code');

        $this->post('/admin/password/verify', ['code' => $code])
            ->assertRedirect('/admin/password/new');

        $this->get('/admin/password/new')->assertOk();

        $this->from('/admin/password/new')->post('/admin/password/reset', [
            'password' => 'nueva-clave-123',
            'password_confirmation' => 'nueva-clave-123',
        ])->assertRedirect('/login');

        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);

        $this->assertTrue(
            DB::table('usuario')->where('email', $user->email)
                ->value('Contrasena') !== $user->Contrasena
        );
    }
}

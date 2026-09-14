<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password as PasswordRule;

class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:usuario,email'],
        ]);

        $code = str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($code),
                'code' => $code,
                'expires_at' => now()->addMinutes(60),
                'created_at' => now(),
            ]
        );

        Mail::to($request->email)->send(new PasswordResetCode($code));

        $request->session()->put('email', $request->email);

        return redirect()->route('password.verify.form');
    }

    public function showVerifyForm(Request $request)
    {
        if (! $request->session()->has('email')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-code', ['email' => $request->session()->get('email')]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:8'],
        ]);

        $email = $request->session()->get('email');

        if (! $email) {
            return redirect()->route('password.request');
        }

        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('expires_at', '>', now())
            ->first();

        if (! $record || ! Hash::check($request->code, $record->token)) {
            return back()->withErrors(['code' => 'El código es inválido o ha expirado.']);
        }

        return redirect()->route('password.reset');
    }

    public function showResetForm(Request $request)
    {
        if (! $request->session()->has('email')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $email = $request->session()->get('email');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $request->session()->forget('email');

            return redirect()->route('password.request')->withErrors(['email' => 'El correo no está registrado.']);
        }

        $user->forceFill([
            'Contrasena' => $request->password,
        ])->save();

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        $request->session()->forget('email');

        return redirect()->route('login')->with('status', 'Contraseña actualizada. Inicia sesión con tu nueva contraseña.');
    }
}

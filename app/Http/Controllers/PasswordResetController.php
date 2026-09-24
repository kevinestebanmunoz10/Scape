<?php

// Espacio de nombres al que pertenece este controlador

namespace App\Http\Controllers;

use App\Mail\PasswordResetCode; // Mailable que envía el código de restablecimiento de contraseña
use App\Models\User; // Modelo que representa la tabla de usuarios
use Illuminate\Http\Request; // Clase base para manejar la petición HTTP
use Illuminate\Support\Facades\DB; // Fachada para ejecutar consultas a la base de datos
use Illuminate\Support\Facades\Hash; // Fachada para cifrar y verificar contraseñas
use Illuminate\Support\Facades\Mail; // Fachada para enviar correos electrónicos
use Illuminate\Validation\Rules\Password as PasswordRule; // Regla de validación estándar para contraseñas

class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password'); // Muestra el formulario para solicitar el restablecimiento de contraseña
    }

    public function sendCode(Request $request)
    {
        $request->validate([ // Valida los datos de la petición
            'email' => ['required', 'email', 'exists:usuario,email'], // El correo es obligatorio, válido y debe existir en usuarios
        ]);

        $code = str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT); // Genera un código numérico aleatorio de 8 dígitos

        DB::table('password_reset_tokens')->updateOrInsert( // Actualiza o inserta el registro del token de restablecimiento
            ['email' => $request->email], // Identifica el registro por el correo enviado
            [
                'token' => Hash::make($code), // Guarda el código cifrado como token
                'code' => $code, // Almacena el código en texto plano para el correo
                'expires_at' => now()->addMinutes(60), // Define la expiración del código en 60 minutos
                'created_at' => now(), // Registra la fecha de creación
            ]
        );

        Mail::to($request->email)->send(new PasswordResetCode($code)); // Envía el código de restablecimiento al correo

        $request->session()->put('email', $request->email); // Guarda el correo en la sesión para los siguientes pasos

        return redirect()->route('password.verify.form'); // Redirige al formulario de verificación del código
    }

    public function showVerifyForm(Request $request)
    {
        if (! $request->session()->has('email')) { // Verifica si no hay un correo guardado en la sesión
            return redirect()->route('password.request'); // Redirige al formulario inicial de restablecimiento
        }

        return view('auth.verify-code', ['email' => $request->session()->get('email')]); // Muestra el formulario de verificación con el correo guardado
    }

    public function verifyCode(Request $request)
    {
        $request->validate([ // Valida los datos de la petición
            'code' => ['required', 'digits:8'], // El código es obligatorio y debe tener exactamente 8 dígitos
        ]);

        $email = $request->session()->get('email'); // Obtiene el correo guardado previamente en la sesión

        if (! $email) { // Verifica si no hay correo en la sesión
            return redirect()->route('password.request'); // Redirige al formulario inicial de restablecimiento
        }

        $record = DB::table('password_reset_tokens') // Consulta el registro del token de restablecimiento
            ->where('email', $email) // Filtra por el correo guardado
            ->where('expires_at', '>', now()) // Filtra los registros que aún no han expirado
            ->first(); // Obtiene el primer registro encontrado

        if (! $record || ! Hash::check($request->code, $record->token)) { // Verifica si el registro no existe o el código no coincide
            return back()->withErrors(['code' => 'El código es inválido o ha expirado.']); // Devuelve al formulario con un error de código
        }

        return redirect()->route('password.reset'); // Redirige al formulario para asignar la nueva contraseña
    }

    public function showResetForm(Request $request)
    {
        if (! $request->session()->has('email')) { // Verifica si no hay un correo guardado en la sesión
            return redirect()->route('password.request'); // Redirige al formulario inicial de restablecimiento
        }

        return view('auth.reset-password'); // Muestra el formulario para ingresar la nueva contraseña
    }

    public function store(Request $request)
    {
        $request->validate([ // Valida los datos de la petición
            'password' => ['required', 'confirmed', PasswordRule::min(8)], // La contraseña es obligatoria, debe confirmarse y tener mínimo 8 caracteres
        ]);

        $email = $request->session()->get('email'); // Obtiene el correo guardado previamente en la sesión

        $user = User::where('email', $email)->first(); // Busca el usuario por el correo guardado

        if (! $user) { // Verifica si el usuario no existe
            $request->session()->forget('email'); // Elimina el correo de la sesión

            return redirect()->route('password.request')->withErrors(['email' => 'El correo no está registrado.']); // Redirige con un error de correo no registrado
        }

        $user->forceFill([ // Asigna la nueva contraseña sin ejecutar los eventos del modelo
            'Contrasena' => $request->password, // La contraseña enviada en la petición
        ])->save(); // Guarda el cambio en la base de datos

        DB::table('password_reset_tokens')->where('email', $email)->delete(); // Elimina el token de restablecimiento usado

        $request->session()->forget('email'); // Elimina el correo de la sesión

        return redirect()->route('login')->with('status', 'Contraseña actualizada. Inicia sesión con tu nueva contraseña.'); // Redirige al login con un mensaje de éxito
    }
}

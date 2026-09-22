<?php

use App\Http\Controllers\AccesoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\Rector\UsuariosController;
use App\Http\Controllers\RegistroIngresoController;
use App\Http\Controllers\RegistroSalidaController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route(Auth::user()->panelRoute());
    }

    return view('home');
});

Route::view('/planes', 'planes');

Route::view('/modulos/personas', 'modulos.personas');
Route::view('/modulos/equipos', 'modulos.equipos');
Route::view('/modulos/visitantes', 'modulos.visitantes');
Route::view('/modulos/reportes', 'modulos.reportes');

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route(Auth::user()->panelRoute());
    }

    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $datos = $request->validate([
        // Validación en tiempo real del documento: obligatorio (solo números se garantiza en el input).
        'documento' => ['required', 'string'],
        // Validación en tiempo real de la contraseña: mínimo 6 caracteres, con minúsculas y números.
        'contrasena' => ['required', 'string', 'min:6', 'regex:/[a-z]/', 'regex:/[0-9]/'],
        'terminos' => ['accepted'],
    ], [
        'terminos.accepted' => 'Debes aceptar los términos y condiciones para iniciar sesión.',
    ]);

    $documento = trim($datos['documento']);
    $contrasena = $datos['contrasena'];

    if (! Auth::attempt([
        'Documento' => $documento,
        'password' => $contrasena,
    ])) {
        return back()
            ->withInput($request->only('documento'))
            ->withErrors([
                'documento' => 'Documento o contraseña incorrectos.',
            ]);
    }

    $request->session()->regenerate();

    return redirect()->route(Auth::user()->panelRoute());
});

Route::post('/admin/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();

    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('dashboard');

Route::get('/admin/accesos', [AccesoController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.accesos');

Route::get('/admin/perfil', function () {
    $rol = DB::table('rol')->where('id_rol', Auth::user()->id_rol)->value('rol');

    return view('admin.perfil', ['rol' => $rol]);
})->middleware(['auth', 'admin'])->name('admin.perfil');

// donde se redirige al administrador a su panel
Route::resource('admin/usuarios', UserController::class)
    ->parameters(['usuarios' => 'usuario'])
    ->names('admin.usuarios')
    ->middleware(['auth', 'admin']);

Route::post('admin/usuarios/{usuario}/activar', [UserController::class, 'activar'])
    ->middleware(['auth', 'admin'])
    ->name('admin.usuarios.activar');

Route::resource('admin/equipos', EquipoController::class)
    ->parameters(['equipos' => 'equipo'])
    ->names('admin.equipos')
    ->middleware(['auth', 'admin']);

Route::prefix('profesor')->name('profesor.')->middleware(['auth', 'profesor'])->group(function () {
    Route::view('dashboard', 'profesor.dashboard')->name('dashboard');

    Route::view('toma-lista', 'profesor.toma-lista')->name('toma-lista');

    Route::view('fichas-grupos', 'profesor.fichas-grupos')->name('fichas-grupos');

    Route::view('reportes-alertas', 'profesor.reportes-alertas')->name('reportes-alertas');

    Route::view('exportar', 'profesor.exportar')->name('exportar');

    Route::get('perfil', function () {
        $rol = DB::table('rol')->where('id_rol', Auth::user()->id_rol)->value('rol');

        return view('profesor.perfil', ['rol' => $rol]);
    })->name('perfil');
});

Route::prefix('rector')->name('rector.')->middleware(['auth', 'rector'])->group(function () {
    Route::view('dashboard', 'rector.dashboard')->name('dashboard');

    Route::resource('usuarios', UsuariosController::class)
        ->parameters(['usuarios' => 'usuario'])
        ->names('usuarios')
        ->except('destroy');

    Route::get('perfil', function () {
        $rol = DB::table('rol')->where('id_rol', Auth::user()->id_rol)->value('rol');

        return view('rector.perfil', ['rol' => $rol]);
    })->name('perfil');
});

Route::prefix('vigilante')->name('vigilante.')->middleware(['auth', 'vigilante'])->group(function () {
    Route::view('dashboard', 'vigilante.dashboard')->name('dashboard');

    Route::get('entrada', [RegistroIngresoController::class, 'create'])->name('entrada');

    Route::post('entrada/buscar', [RegistroIngresoController::class, 'buscar'])->name('entrada.buscar');

    Route::post('entrada', [RegistroIngresoController::class, 'store'])->name('entrada.store');

    Route::get('salida', [RegistroSalidaController::class, 'create'])->name('salida');

    Route::post('salida/buscar', [RegistroSalidaController::class, 'buscar'])->name('salida.buscar');

    Route::post('salida', [RegistroSalidaController::class, 'store'])->name('salida.store');

    Route::get('perfil', function () {
        $rol = DB::table('rol')->where('id_rol', Auth::user()->id_rol)->value('rol');

        return view('vigilante.perfil', ['rol' => $rol]);
    })->name('perfil');
});

Route::get('/admin/password/reset', [PasswordResetController::class, 'showForgotForm'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/admin/password/email', [PasswordResetController::class, 'sendCode'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/admin/password/verify', [PasswordResetController::class, 'showVerifyForm'])
    ->middleware('guest')
    ->name('password.verify.form');

Route::post('/admin/password/verify', [PasswordResetController::class, 'verifyCode'])
    ->middleware('guest')
    ->name('password.verify');

Route::get('/admin/password/new', [PasswordResetController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/admin/password/reset', [PasswordResetController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

<?php

use App\Http\Controllers\AccesoController; // Controlador que gestiona el reporte de accesos
use App\Http\Controllers\DashboardController; // Controlador que gestiona el panel del administrador
use App\Http\Controllers\EquipoController; // Controlador que gestiona los equipos
use App\Http\Controllers\GestionController; // Controlador que muestra el menú de gestión
use App\Http\Controllers\MatriculaController; // Controlador que gestiona las matrículas
use App\Http\Controllers\PasswordResetController; // Controlador que gestiona el restablecimiento de contraseña
use App\Http\Controllers\PermisoController; // Controlador del rector para gestionar usuarios
use App\Http\Controllers\Rector\UsuariosController; // Controlador que gestiona el registro de entradas
use App\Http\Controllers\RegistroIngresoController; // Controlador que gestiona el registro de salidas
use App\Http\Controllers\RegistroSalidaController; // Controlador que gestiona los permisos de salida
use App\Http\Controllers\SedeController; // Controlador que gestiona las sedes
use App\Http\Controllers\UserController; // Controlador que gestiona los usuarios
use Illuminate\Http\Request; // Clase base para manejar la petición HTTP
use Illuminate\Support\Facades\Auth; // Fachada para la autenticación de usuarios
use Illuminate\Support\Facades\DB; // Fachada para ejecutar consultas a la base de datos
use Illuminate\Support\Facades\Route; // Fachada para definir las rutas de la aplicación

Route::get('/', function () { // Define la ruta principal de la aplicación
    if (Auth::check()) { // Verifica si el usuario ya está autenticado
        return redirect()->route(Auth::user()->panelRoute()); // Redirige al panel correspondiente según su rol
    }

    return view('home'); // Muestra la página de inicio para usuarios no autenticados
});

Route::view('/planes', 'planes'); // Ruta de vista estática para la página de planes

Route::view('/modulos/personas', 'modulos.personas'); // Ruta de vista estática para el módulo de personas
Route::view('/modulos/equipos', 'modulos.equipos'); // Ruta de vista estática para el módulo de equipos
Route::view('/modulos/visitantes', 'modulos.visitantes'); // Ruta de vista estática para el módulo de visitantes
Route::view('/modulos/reportes', 'modulos.reportes'); // Ruta de vista estática para el módulo de reportes

Route::get('/login', function () { // Define la ruta GET para mostrar el formulario de inicio de sesión
    if (Auth::check()) { // Verifica si el usuario ya está autenticado
        return redirect()->route(Auth::user()->panelRoute()); // Redirige al panel correspondiente según su rol
    }

    return view('auth.login'); // Muestra el formulario de inicio de sesión
})->name('login'); // Asigna el nombre 'login' a esta ruta

Route::post('/login', function (Request $request) { // Define la ruta POST que procesa el inicio de sesión
    $datos = $request->validate([ // Valida los datos enviados en el formulario de login
        // Validación en tiempo real del documento: obligatorio (solo números se garantiza en el input).
        'documento' => ['required', 'string'], // El documento es obligatorio y debe ser texto
        // Validación en tiempo real de la contraseña: mínimo 6 caracteres, con minúsculas y números.
        'contrasena' => ['required', 'string', 'min:6', 'regex:/[a-z]/', 'regex:/[0-9]/'], // La contraseña exige mínimo 6 caracteres, una minúscula y un número
        'terminos' => ['accepted'], // Los términos y condiciones deben estar aceptados
    ], [
        'terminos.accepted' => 'Debes aceptar los términos y condiciones para iniciar sesión.', // Mensaje personalizado si no se aceptan los términos
    ]);

    $documento = trim($datos['documento']); // Limpia los espacios del documento enviado
    $contrasena = $datos['contrasena']; // Obtiene la contraseña enviada

    if (! Auth::attempt([ // Intenta autenticar al usuario con las credenciales enviadas
        'Documento' => $documento, // Usando el documento como identificador
        'password' => $contrasena, // Y la contraseña proporcionada
    ])) { // Verifica si la autenticación falló
        return back() // Devuelve al formulario anterior
            ->withInput($request->only('documento')) // Conserva el documento en el formulario
            ->withErrors([ // Con un mensaje de error
                'documento' => 'Documento o contraseña incorrectos.', // Mensaje de credenciales inválidas
            ]);
    }

    $request->session()->regenerate(); // Regenera el id de sesión para prevenir fijación de sesión

    return redirect()->route(Auth::user()->panelRoute()); // Redirige al panel correspondiente después del login
});

Route::post('/admin/logout', function (Request $request) { // Define la ruta POST para cerrar la sesión del administrador
    Auth::logout(); // Cierra la sesión del usuario autenticado

    $request->session()->invalidate(); // Invalida la sesión actual

    $request->session()->regenerateToken(); // Regenera el token CSRF de la sesión

    return redirect()->route('login'); // Redirige al formulario de inicio de sesión
})->name('logout'); // Asigna el nombre 'logout' a esta ruta

Route::get('/admin/dashboard', [DashboardController::class, 'index']) // Define la ruta del panel de administración
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('dashboard'); // Asigna el nombre 'dashboard' a esta ruta

Route::get('/admin/accesos', [AccesoController::class, 'index']) // Define la ruta del reporte de accesos
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.accesos'); // Asigna el nombre 'admin.accesos' a esta ruta

Route::get('/admin/accesos/equipos', [AccesoController::class, 'equipos']) // Define la ruta del reporte de accesos de equipos
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.accesos.equipos'); // Asigna el nombre 'admin.accesos.equipos' a esta ruta

Route::get('/admin/perfil', function () { // Define la ruta del perfil del administrador
    $rol = DB::table('rol')->where('id_rol', Auth::user()->id_rol)->value('rol'); // Consulta el nombre del rol del usuario autenticado

    return view('admin.perfil', ['rol' => $rol]); // Muestra la vista del perfil con el rol consultado
})->middleware(['auth', 'admin'])->name('admin.perfil'); // Exige autenticación de administrador y asigna el nombre 'admin.perfil'

// donde se redirige al administrador a su panel
Route::resource('admin/usuarios', UserController::class) // Define las rutas CRUD de usuarios del administrador
    ->parameters(['usuarios' => 'usuario']) // Renombra el parámetro de ruta a 'usuario'
    ->names('admin.usuarios') // Prefija los nombres de todas las rutas con 'admin.usuarios'
    ->middleware(['auth', 'admin']); // Exige que el usuario esté autenticado y tenga rol de administrador

Route::post('admin/usuarios/{usuario}/activar', [UserController::class, 'activar']) // Define la ruta para reactivar un usuario
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.usuarios.activar'); // Asigna el nombre 'admin.usuarios.activar' a esta ruta

Route::resource('admin/matriculas', MatriculaController::class) // Define las rutas de alta y consulta de matrículas
    ->parameters(['matriculas' => 'matricula']) // Renombra el parámetro de ruta a 'matricula'
    ->names('admin.matriculas') // Prefija los nombres de todas las rutas con 'admin.matriculas'
    ->only(['index', 'create', 'store']) // Solo se permite listar, crear y guardar matrículas
    ->middleware(['auth', 'admin']); // Exige que el usuario esté autenticado y tenga rol de administrador

Route::get('admin/gestion', [GestionController::class, 'index']) // Define la ruta de la pantalla de gestión con los módulos disponibles
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.gestion'); // Asigna el nombre 'admin.gestion' a esta ruta

Route::resource('admin/sedes', SedeController::class) // Define las rutas CRUD de las sedes del administrador
    ->parameters(['sedes' => 'sede']) // Renombra el parámetro de ruta a 'sede'
    ->names('admin.sedes') // Prefija los nombres de todas las rutas con 'admin.sedes'
    ->middleware(['auth', 'admin']); // Exige que el usuario esté autenticado y tenga rol de administrador

Route::post('admin/sedes/{sede}/activar', [SedeController::class, 'activar']) // Define la ruta para reactivar una sede
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.sedes.activar'); // Asigna el nombre 'admin.sedes.activar' a esta ruta

Route::get('admin/equipos/catalogos', [EquipoController::class, 'catalogos']) // Define la ruta del catálogo de marcas y tipos
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.equipos.catalogos'); // Asigna el nombre 'admin.equipos.catalogos' a esta ruta

Route::post('admin/equipos/catalogos/marcas', [EquipoController::class, 'storeMarca']) // Define la ruta para registrar una marca
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.equipos.catalogos.marcas.store'); // Asigna el nombre de la ruta de registro de marcas

Route::delete('admin/equipos/catalogos/marcas/{marca}', [EquipoController::class, 'destroyMarca']) // Define la ruta para eliminar una marca
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.equipos.catalogos.marcas.destroy'); // Asigna el nombre de la ruta de eliminación de marcas

Route::post('admin/equipos/catalogos/tipos', [EquipoController::class, 'storeTipo']) // Define la ruta para registrar un tipo de equipo
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.equipos.catalogos.tipos.store'); // Asigna el nombre de la ruta de registro de tipos

Route::delete('admin/equipos/catalogos/tipos/{tipo}', [EquipoController::class, 'destroyTipo']) // Define la ruta para eliminar un tipo de equipo
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.equipos.catalogos.tipos.destroy'); // Asigna el nombre de la ruta de eliminación de tipos

Route::get('admin/permisos', [PermisoController::class, 'index']) // Define la ruta de la vista de permisos de salida
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.permisos'); // Asigna el nombre 'admin.permisos' a esta ruta

Route::get('admin/permisos/tipos', [PermisoController::class, 'tipos']) // Define la ruta del catálogo de tipos de permiso
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.permisos.tipos'); // Asigna el nombre 'admin.permisos.tipos' a esta ruta

Route::post('admin/permisos/tipos', [PermisoController::class, 'storeTipo']) // Define la ruta para registrar un tipo de permiso
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.permisos.tipos.store'); // Asigna el nombre de la ruta de registro de tipos de permiso

Route::delete('admin/permisos/tipos/{tipoPermiso}', [PermisoController::class, 'destroyTipo']) // Define la ruta para eliminar un tipo de permiso
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.permisos.tipos.destroy'); // Asigna el nombre de la ruta de eliminación de tipos de permiso

Route::get('admin/permisos/{permiso}/edit', [PermisoController::class, 'edit']) // Define la ruta del formulario de edición de un permiso
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.permisos.edit'); // Asigna el nombre 'admin.permisos.edit' a esta ruta

Route::put('admin/permisos/{permiso}', [PermisoController::class, 'update']) // Define la ruta para actualizar un permiso
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.permisos.update'); // Asigna el nombre 'admin.permisos.update' a esta ruta

Route::delete('admin/permisos/{permiso}', [PermisoController::class, 'destroy']) // Define la ruta para eliminar un permiso
    ->middleware(['auth', 'admin']) // Exige que el usuario esté autenticado y tenga rol de administrador
    ->name('admin.permisos.destroy'); // Asigna el nombre 'admin.permisos.destroy' a esta ruta

Route::resource('admin/equipos', EquipoController::class) // Define las rutas CRUD de equipos del administrador
    ->parameters(['equipos' => 'equipo']) // Renombra el parámetro de ruta a 'equipo'
    ->names('admin.equipos') // Prefija los nombres de todas las rutas con 'admin.equipos'
    ->middleware(['auth', 'admin']); // Exige que el usuario esté autenticado y tenga rol de administrador

Route::prefix('profesor')->name('profesor.')->middleware(['auth', 'profesor'])->group(function () { // Agrupa las rutas del profesor con prefijo, nombres y middleware comunes
    Route::view('dashboard', 'profesor.dashboard')->name('dashboard'); // Ruta de vista del panel del profesor

    Route::view('toma-lista', 'profesor.toma-lista')->name('toma-lista'); // Ruta de vista para tomar lista del profesor

    Route::view('fichas-grupos', 'profesor.fichas-grupos')->name('fichas-grupos'); // Ruta de vista de fichas y grupos del profesor

    Route::view('reportes-alertas', 'profesor.reportes-alertas')->name('reportes-alertas'); // Ruta de vista de reportes y alertas del profesor

    Route::view('exportar', 'profesor.exportar')->name('exportar'); // Ruta de vista para exportar datos del profesor

    Route::get('perfil', function () { // Define la ruta del perfil del profesor
        $rol = DB::table('rol')->where('id_rol', Auth::user()->id_rol)->value('rol'); // Consulta el nombre del rol del usuario autenticado

        return view('profesor.perfil', ['rol' => $rol]); // Muestra la vista del perfil con el rol consultado
    })->name('perfil'); // Asigna el nombre 'perfil' a esta ruta
});

Route::prefix('rector')->name('rector.')->middleware(['auth', 'rector'])->group(function () { // Agrupa las rutas del rector con prefijo, nombres y middleware comunes
    Route::view('dashboard', 'rector.dashboard')->name('dashboard'); // Ruta de vista del panel del rector

    Route::post('permisos', [PermisoController::class, 'store']) // Define la ruta para registrar un permiso de salida
        ->name('permisos.store'); // Asigna el nombre 'rector.permisos.store' a esta ruta

    Route::resource('usuarios', UsuariosController::class) // Define las rutas de consulta de usuarios del rector
        ->parameters(['usuarios' => 'usuario']) // Renombra el parámetro de ruta a 'usuario'
        ->names('usuarios') // Prefija los nombres de todas las rutas con 'usuarios'
        ->only(['index', 'show']); // El rector solo puede consultar usuarios

    Route::get('perfil', function () { // Define la ruta del perfil del rector
        $rol = DB::table('rol')->where('id_rol', Auth::user()->id_rol)->value('rol'); // Consulta el nombre del rol del usuario autenticado

        return view('rector.perfil', ['rol' => $rol]); // Muestra la vista del perfil con el rol consultado
    })->name('perfil'); // Asigna el nombre 'perfil' a esta ruta
});

Route::prefix('vigilante')->name('vigilante.')->middleware(['auth', 'vigilante'])->group(function () { // Agrupa las rutas del vigilante con prefijo, nombres y middleware comunes
    Route::view('dashboard', 'vigilante.dashboard')->name('dashboard'); // Ruta de vista del panel del vigilante

    Route::get('entrada', [RegistroIngresoController::class, 'create'])->name('entrada'); // Ruta para mostrar el formulario de entrada

    Route::post('entrada/buscar', [RegistroIngresoController::class, 'buscar'])->name('entrada.buscar'); // Ruta que busca a la persona para el ingreso

    Route::post('entrada', [RegistroIngresoController::class, 'store'])->name('entrada.store'); // Ruta que guarda el ingreso registrado

    Route::get('salida', [RegistroSalidaController::class, 'create'])->name('salida'); // Ruta para mostrar el formulario de salida

    Route::post('salida/buscar', [RegistroSalidaController::class, 'buscar'])->name('salida.buscar'); // Ruta que busca a la persona para la salida

    Route::post('salida', [RegistroSalidaController::class, 'store'])->name('salida.store'); // Ruta que guarda la salida registrada

    Route::get('perfil', function () { // Define la ruta del perfil del vigilante
        $rol = DB::table('rol')->where('id_rol', Auth::user()->id_rol)->value('rol'); // Consulta el nombre del rol del usuario autenticado

        return view('vigilante.perfil', ['rol' => $rol]); // Muestra la vista del perfil con el rol consultado
    })->name('perfil'); // Asigna el nombre 'perfil' a esta ruta
});

Route::get('/admin/password/reset', [PasswordResetController::class, 'showForgotForm']) // Ruta que muestra el formulario de solicitud de restablecimiento
    ->middleware('guest') // Solo accesible para usuarios no autenticados
    ->name('password.request'); // Asigna el nombre 'password.request' a esta ruta

Route::post('/admin/password/email', [PasswordResetController::class, 'sendCode']) // Ruta que envía el código de restablecimiento
    ->middleware('guest') // Solo accesible para usuarios no autenticados
    ->name('password.email'); // Asigna el nombre 'password.email' a esta ruta

Route::get('/admin/password/verify', [PasswordResetController::class, 'showVerifyForm']) // Ruta que muestra el formulario de verificación del código
    ->middleware('guest') // Solo accesible para usuarios no autenticados
    ->name('password.verify.form'); // Asigna el nombre 'password.verify.form' a esta ruta

Route::post('/admin/password/verify', [PasswordResetController::class, 'verifyCode']) // Ruta que verifica el código enviado
    ->middleware('guest') // Solo accesible para usuarios no autenticados
    ->name('password.verify'); // Asigna el nombre 'password.verify' a esta ruta

Route::get('/admin/password/new', [PasswordResetController::class, 'showResetForm']) // Ruta que muestra el formulario de la nueva contraseña
    ->middleware('guest') // Solo accesible para usuarios no autenticados
    ->name('password.reset'); // Asigna el nombre 'password.reset' a esta ruta

Route::post('/admin/password/reset', [PasswordResetController::class, 'store']) // Ruta que guarda la nueva contraseña
    ->middleware('guest') // Solo accesible para usuarios no autenticados
    ->name('password.store'); // Asigna el nombre 'password.store' a esta ruta

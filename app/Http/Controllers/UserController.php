<?php

// Espacio de nombres al que pertenece este controlador

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest; // Request de validación para crear un usuario
use App\Http\Requests\UpdateUserRequest; // Request de validación para actualizar un usuario
use App\Models\Estado; // Modelo que representa la tabla de estados
use App\Models\Rol; // Modelo que representa la tabla de roles
use App\Models\User; // Modelo que representa la tabla de usuarios
use Illuminate\Http\RedirectResponse; // Tipo de retorno para respuestas de redirección
use Illuminate\Http\Request; // Clase base para manejar la petición HTTP
use Illuminate\Support\Facades\DB; // Fachada para ejecutar consultas a la base de datos
use Illuminate\View\View; // Tipo de retorno para vistas

class UserController extends Controller
{
    // funcion para mostrar lols registros
    public function index(Request $request): View
    {
        // funcion $buscar para que funcione la barra de busqueda
        $buscar = trim((string) $request->query('buscar', '')); // Obtiene y limpia el término de búsqueda de la URL

        // donde se define qué se va a buscar y  en qué orden se va a mostrar
        $usuarios = User::with(['rol', 'estado']) // Consulta los usuarios con su rol y estado relacionados
            ->when($buscar !== '', fn ($query) => $query->where('Nom_usua', 'like', '%'.$buscar.'%')) // Filtra por nombre si hay término de búsqueda
            ->orderByDesc('Documento') // Ordena los resultados por documento de forma descendente
            ->paginate(10) // Pagina los resultados de 10 en 10
            ->withQueryString(); // Conserva los filtros de la URL en los enlaces de paginación

        // donde se dice cual vista es la que se va a mostrar despues de  la consulta
        return view('admin.usuarios.index', compact('usuarios', 'buscar')); // Muestra la vista del listado con los datos consultados
    }

    // funcion para mostrar el formulario de creacion
    public function create(): View
    {
        return view('admin.usuarios.create', $this->formData()); // Muestra el formulario de creación con los datos de apoyo
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated(); // Obtiene únicamente los datos validados de la petición
        $data['QR'] = $data['QR'] ?? 'QR-'.$data['Documento']; // Asigna un QR por defecto basado en el documento si no se envió uno

        User::create($data); // Crea el nuevo usuario en la base de datos

        return redirect() // Devuelve una redirección
            ->route('admin.usuarios.index') // Hacia el listado de usuarios
            ->with('status', 'Usuario creado correctamente.'); // Con un mensaje de éxito en la sesión
    }

    public function show(User $usuario): View
    {
        $usuario->load(['rol', 'estado']); // Carga las relaciones rol y estado del usuario

        return view('admin.usuarios.show', compact('usuario')); // Muestra la vista de detalle del usuario
    }

    public function edit(User $usuario): View
    {
        return view('admin.usuarios.edit', array_merge( // Muestra la vista de edición combinando los datos de apoyo
            $this->formData(), // Con los roles, estados y ciudades disponibles
            ['usuario' => $usuario], // Y el usuario que se va a editar
        ));
    }

    public function update(UpdateUserRequest $request, User $usuario): RedirectResponse
    {
        $data = $request->validated(); // Obtiene únicamente los datos validados de la petición

        if (empty($data['Contrasena'])) { // Verifica si no se envió una contraseña nueva
            unset($data['Contrasena']); // Elimina la contraseña vacía para no sobrescribir la existente
        }

        if (empty($data['QR'])) { // Verifica si no se envió un QR nuevo
            $data['QR'] = 'QR-'.$usuario->Documento; // Asigna el QR por defecto basado en el documento del usuario
        }

        $usuario->update($data); // Actualiza el usuario con los datos procesados

        return redirect() // Devuelve una redirección
            ->route('admin.usuarios.index') // Hacia el listado de usuarios
            ->with('status', 'Usuario actualizado correctamente.'); // Con un mensaje de éxito en la sesión
    }

    public function destroy(User $usuario): RedirectResponse
    {
        if ($usuario->is(auth()->user())) { // Verifica si el usuario a desactivar es el mismo que está autenticado
            return redirect() // Devuelve una redirección
                ->route('admin.usuarios.index') // Hacia el listado de usuarios
                ->with('error', 'No puedes desactivar tu propia cuenta.'); // Con un mensaje de error en la sesión
        }

        $inactivo = Estado::where('estado', 0)->value('id_estado'); // Busca el id del estado inactivo

        $usuario->update(['id_Estado' => $inactivo]); // Cambia el estado del usuario a inactivo

        return redirect() // Devuelve una redirección
            ->route('admin.usuarios.index') // Hacia el listado de usuarios
            ->with('status', 'Usuario desactivado correctamente.'); // Con un mensaje de éxito en la sesión
    }

    public function activar(User $usuario): RedirectResponse
    {
        $activo = Estado::where('estado', 1)->value('id_estado'); // Busca el id del estado activo

        $usuario->update(['id_Estado' => $activo]); // Cambia el estado del usuario a activo

        return redirect() // Devuelve una redirección
            ->route('admin.usuarios.index') // Hacia el listado de usuarios
            ->with('status', 'Usuario activado correctamente.'); // Con un mensaje de éxito en la sesión
    }

    private function formData(): array
    {
        return [
            'roles' => Rol::orderBy('id_rol')->get(), // Obtiene todos los roles ordenados por id
            'estados' => Estado::orderBy('id_estado')->get(), // Obtiene todos los estados ordenados por id
            'ciudades' => DB::table('ciudad')->orderBy('Ciudad')->get(), // Obtiene todas las ciudades ordenadas por nombre
        ];
    }
}

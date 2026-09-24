<?php

// Espacio de nombres al que pertenece este controlador

namespace App\Http\Controllers\Rector;

use App\Http\Controllers\Controller; // Controlador base de la aplicación
use App\Http\Requests\StoreUserRequest; // Request de validación para crear un usuario
use App\Http\Requests\UpdateUserRequest; // Request de validación para actualizar un usuario
use App\Models\Estado; // Modelo que representa la tabla de estados
use App\Models\Rol; // Modelo que representa la tabla de roles
use App\Models\User; // Modelo que representa la tabla de usuarios
use Illuminate\Http\RedirectResponse; // Tipo de retorno para respuestas de redirección
use Illuminate\Http\Request; // Clase base para manejar la petición HTTP
use Illuminate\Support\Facades\DB; // Fachada para ejecutar consultas a la base de datos
use Illuminate\View\View; // Tipo de retorno para vistas

class UsuariosController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', '')); // Obtiene y limpia el término de búsqueda de la URL

        $usuarios = User::with(['rol', 'estado']) // Consulta los usuarios con su rol y estado relacionados
            ->when($buscar !== '', fn ($query) => $query->where('Nom_usua', 'like', '%'.$buscar.'%')) // Filtra por nombre si hay término de búsqueda
            ->orderByDesc('Documento') // Ordena los resultados por documento de forma descendente
            ->paginate(10) // Pagina los resultados de 10 en 10
            ->withQueryString(); // Conserva los filtros de la URL en los enlaces de paginación

        return view('rector.usuarios.index', compact('usuarios', 'buscar')); // Muestra la vista del rector con los datos consultados
    }

    public function create(): View
    {
        return view('rector.usuarios.create', $this->formData()); // Muestra el formulario de creación con los datos de apoyo
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated(); // Obtiene únicamente los datos validados de la petición
        $data['QR'] = $data['QR'] ?? 'QR-'.$data['Documento']; // Asigna un QR por defecto basado en el documento si no se envió uno

        User::create($data); // Crea el nuevo usuario en la base de datos

        return redirect() // Devuelve una redirección
            ->route('rector.usuarios.index') // Hacia el listado de usuarios del rector
            ->with('status', 'Usuario creado correctamente.'); // Con un mensaje de éxito en la sesión
    }

    public function show(User $usuario): View
    {
        $usuario->load(['rol', 'estado']); // Carga las relaciones rol y estado del usuario

        return view('rector.usuarios.show', compact('usuario')); // Muestra la vista de detalle del usuario
    }

    public function edit(User $usuario): View
    {
        return view('rector.usuarios.edit', array_merge( // Muestra la vista de edición combinando los datos de apoyo
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
            ->route('rector.usuarios.index') // Hacia el listado de usuarios del rector
            ->with('status', 'Usuario actualizado correctamente.'); // Con un mensaje de éxito en la sesión
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

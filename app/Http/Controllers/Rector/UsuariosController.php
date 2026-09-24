<?php

// Espacio de nombres al que pertenece este controlador

namespace App\Http\Controllers\Rector;

use App\Http\Controllers\Controller; // Controlador base de la aplicación
use App\Models\User; // Modelo que representa la tabla de usuarios
use Illuminate\Http\Request; // Clase base para manejar la petición HTTP
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

    public function show(User $usuario): View
    {
        $usuario->load(['rol', 'estado']); // Carga las relaciones rol y estado del usuario

        return view('rector.usuarios.show', compact('usuario')); // Muestra la vista de detalle del usuario
    }
}

<?php

// Espacio de nombres al que pertenece este controlador

namespace App\Http\Controllers;

use App\Http\Requests\StoreSedeRequest; // Request de validación para crear una sede
use App\Http\Requests\UpdateSedeRequest; // Request de validación para actualizar una sede
use App\Models\Ciudad; // Modelo que representa la tabla de ciudades
use App\Models\Estado; // Modelo que representa la tabla de estados
use App\Models\Sede; // Modelo que representa la tabla de sedes
use Illuminate\Http\RedirectResponse; // Tipo de retorno para respuestas de redirección
use Illuminate\Http\Request; // Clase base para manejar la petición HTTP
use Illuminate\View\View; // Tipo de retorno para vistas

// Controlador que gestiona el CRUD completo de las sedes de la institución
class SedeController extends Controller
{
    // funcion para mostrar los registros
    public function index(Request $request): View
    {
        // funcion $buscar para que funcione la barra de busqueda
        $buscar = trim((string) $request->query('buscar', '')); // Obtiene y limpia el término de búsqueda de la URL

        // Filtra el listado por estado cuando el administrador lo selecciona
        $estado = (string) $request->query('estado', ''); // Obtiene el filtro de estado ('' = todos)

        // donde se define qué se va a buscar y en qué orden se va a mostrar
        $sedes = Sede::with(['estado', 'ciudad']) // Consulta las sedes con su estado y ciudad relacionados
            ->withCount('matriculas') // Cuenta cuántas matrículas tiene cada sede
            ->when($buscar !== '', fn ($query) => $query->where(function ($query) use ($buscar) { // Filtra si hay término de búsqueda
                $query->where('nombre', 'like', '%'.$buscar.'%') // Buscando por el nombre de la sede
                    ->orWhere('Nit', 'like', '%'.$buscar.'%'); // O por el NIT de la sede
            })) // Cierra el filtro agrupado para no romper la precedencia de los AND
            ->when(in_array($estado, ['1', '2'], true), fn ($query) => $query->where('id_Estado', (int) $estado)) // Aplica el filtro de estado solo si es válido
            ->orderBy('nombre') // Ordena los resultados por nombre de la sede
            ->paginate(10) // Pagina los resultados de 10 en 10
            ->withQueryString(); // Conserva los filtros de la URL en los enlaces de paginación

        // donde se dice cual vista es la que se va a mostrar despues de la consulta
        return view('admin.sedes.index', compact('sedes', 'buscar', 'estado')); // Muestra la vista del listado con los datos consultados
    }

    // funcion para mostrar el formulario de creacion
    public function create(): View
    {
        return view('admin.sedes.create', $this->formData()); // Muestra el formulario de creación con los datos de apoyo
    }

    // funcion para guardar la sede creada
    public function store(StoreSedeRequest $request): RedirectResponse
    {
        $data = $request->validated(); // Obtiene únicamente los datos validados de la petición

        $data['id_Estado'] = $data['id_Estado'] ?? 1; // Si no se envió estado, la sede nace activa

        Sede::create($data); // Crea la nueva sede en la base de datos

        return redirect() // Devuelve una redirección
            ->route('admin.sedes.index') // Hacia el listado de sedes
            ->with('status', 'Sede registrada correctamente.'); // Con un mensaje de éxito en la sesión
    }

    // funcion para mostrar los detalles de una sede
    public function show(Sede $sede): View
    {
        // Carga el estado y las matrículas asociadas para mostrar el detalle
        $sede->load(['estado', 'ciudad', 'matriculas.estudiante']); // Carga las relaciones necesarias del registro

        return view('admin.sedes.show', compact('sede')); // Muestra la vista de detalle con la sede consultada
    }

    // funcion para mostrar el formulario de edicion
    public function edit(Sede $sede): View
    {
        return view('admin.sedes.edit', array_merge($this->formData(), compact('sede'))); // Muestra el formulario de edición con los datos de apoyo
    }

    // funcion para actualizar la sede editada
    public function update(UpdateSedeRequest $request, Sede $sede): RedirectResponse
    {
        $data = $request->validated(); // Obtiene únicamente los datos validados de la petición

        $data['id_Estado'] = $data['id_Estado'] ?? $sede->id_Estado; // Si no se envió estado, conserva el estado actual

        $sede->update($data); // Actualiza la sede con los datos procesados

        return redirect() // Devuelve una redirección
            ->route('admin.sedes.index') // Hacia el listado de sedes
            ->with('status', 'Sede actualizada correctamente.'); // Con un mensaje de éxito en la sesión
    }

    // funcion para desactivar la sede (borrado lógico)
    public function destroy(Sede $sede): RedirectResponse
    {
        $sede->update(['id_Estado' => 2]); // Marca la sede como inactiva sin borrar el historial de matrículas

        return redirect() // Devuelve una redirección
            ->route('admin.sedes.index') // Hacia el listado de sedes
            ->with('status', 'Sede desactivada correctamente.'); // Con un mensaje de éxito en la sesión
    }

    // funcion para reactivar la sede desactivada
    public function activar(Sede $sede): RedirectResponse
    {
        $sede->update(['id_Estado' => 1]); // Devuelve la sede al estado activo

        return redirect() // Devuelve una redirección
            ->route('admin.sedes.index') // Hacia el listado de sedes
            ->with('status', 'Sede activada correctamente.'); // Con un mensaje de éxito en la sesión
    }

    // Datos de apoyo compartidos por los formularios de creación y edición
    private function formData(): array
    {
        return [
            'estados' => Estado::orderBy('id_estado')->get(), // Obtiene todos los estados disponibles
            'ciudades' => Ciudad::orderBy('Ciudad')->get(), // Obtiene el catálogo de ciudades ordenado por nombre
        ];
    }
}

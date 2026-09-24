<?php

// Espacio de nombres al que pertenece este controlador

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipoRequest; // Request de validación para crear un equipo
use App\Http\Requests\UpdateEquipoRequest; // Request de validación para actualizar un equipo
use App\Models\Equipo; // Modelo que representa la tabla de equipos
use App\Models\Marca; // Modelo que representa la tabla de marcas
use App\Models\TipoEquipo; // Modelo que representa la tabla de tipos de equipo
use App\Models\User; // Modelo que representa la tabla de usuarios
use Illuminate\Http\RedirectResponse; // Tipo de retorno para respuestas de redirección
use Illuminate\Http\Request; // Clase base para manejar la petición HTTP
use Illuminate\View\View; // Tipo de retorno para vistas

class EquipoController extends Controller
{
    public function index(Request $request): View
    {
        $buscar = trim((string) $request->query('buscar', '')); // Obtiene y limpia el término de búsqueda de la URL
        $asignacion = (string) $request->query('asignacion', ''); // Obtiene el filtro de asignación desde la URL

        $equipos = Equipo::with(['tipo', 'marca', 'usuario']) // Consulta los equipos con su tipo, marca y usuario relacionados
            ->when($buscar !== '', function ($query) use ($buscar) { // Aplica los filtros de búsqueda si hay término
                $query->where('serial_equi', 'like', '%'.$buscar.'%') // Filtra por el serial del equipo
                    ->orWhere('Color', 'like', '%'.$buscar.'%') // O por el color del equipo
                    ->orWhereHas('usuario', function ($query) use ($buscar) { // O por los datos del usuario asignado
                        $query->where('Nom_usua', 'like', '%'.$buscar.'%') // Filtrando por el nombre del usuario
                            ->orWhere('Documento', 'like', '%'.$buscar.'%'); // O por el documento del usuario
                    });
            })
            ->when($asignacion === 'sin', fn ($query) => $query->whereNull('Documento')) // Filtra los equipos sin asignar
            ->when($asignacion === 'con', fn ($query) => $query->whereNotNull('Documento')) // Filtra los equipos asignados
            ->orderBy('serial_equi') // Ordena los equipos por serial
            ->paginate(10) // Pagina los resultados de 10 en 10
            ->withQueryString(); // Conserva los filtros de la URL en los enlaces de paginación

        return view('admin.equipos.index', compact('equipos', 'buscar', 'asignacion')); // Muestra la vista del listado con los datos consultados
    }

    public function create(): View
    {
        return view('admin.equipos.create', $this->formData()); // Muestra el formulario de creación con los datos de apoyo
    }

    public function store(StoreEquipoRequest $request): RedirectResponse
    {
        $data = $request->validated(); // Obtiene únicamente los datos validados de la petición

        if ($request->hasFile('imagen')) { // Verifica si se envió un archivo de imagen
            $data['imagen'] = $request->file('imagen')->store('equipos', 'public'); // Almacena la imagen en el disco público y guarda su ruta
        }

        Equipo::create($data); // Crea el nuevo equipo en la base de datos

        return redirect() // Devuelve una redirección
            ->route('admin.equipos.index') // Hacia el listado de equipos
            ->with('status', 'Equipo registrado correctamente.'); // Con un mensaje de éxito en la sesión
    }

    public function show(Equipo $equipo): View
    {
        $equipo->load(['tipo', 'marca', 'usuario']); // Carga las relaciones tipo, marca y usuario del equipo

        return view('admin.equipos.show', compact('equipo')); // Muestra la vista de detalle del equipo
    }

    public function edit(Equipo $equipo): View
    {
        return view('admin.equipos.edit', array_merge( // Muestra la vista de edición combinando los datos de apoyo
            $this->formData(), // Con las marcas, tipos y usuarios disponibles
            ['equipo' => $equipo], // Y el equipo que se va a editar
        ));
    }

    public function update(UpdateEquipoRequest $request, Equipo $equipo): RedirectResponse
    {
        $data = $request->validated(); // Obtiene únicamente los datos validados de la petición

        if ($request->hasFile('imagen')) { // Verifica si se envió un archivo de imagen
            $data['imagen'] = $request->file('imagen')->store('equipos', 'public'); // Almacena la nueva imagen y guarda su ruta
        } else { // Caso en que no se envió imagen
            unset($data['imagen']); // Elimina el campo imagen para no sobrescribir la existente
        }

        $equipo->update($data); // Actualiza el equipo con los datos procesados

        return redirect() // Devuelve una redirección
            ->route('admin.equipos.index') // Hacia el listado de equipos
            ->with('status', 'Equipo actualizado correctamente.'); // Con un mensaje de éxito en la sesión
    }

    public function destroy(Equipo $equipo): RedirectResponse
    {
        if ($equipo->prestamos()->exists()) { // Verifica si el equipo tiene préstamos registrados
            return redirect() // Devuelve una redirección
                ->route('admin.equipos.index') // Hacia el listado de equipos
                ->with('error', 'No puedes eliminar un equipo con pr&eacute;stamos registrados.'); // Con un mensaje de error en la sesión
        }

        $equipo->delete(); // Elimina el equipo de la base de datos

        return redirect() // Devuelve una redirección
            ->route('admin.equipos.index') // Hacia el listado de equipos
            ->with('status', 'Equipo eliminado correctamente.'); // Con un mensaje de éxito en la sesión
    }

    public function catalogos(): View
    {
        return view('admin.equipos.catalogos', [ // Muestra la vista de catálogos
            'marcas' => Marca::orderBy('marca')->get(), // Todas las marcas ordenadas por nombre
            'tipos' => TipoEquipo::orderBy('tipo')->get(), // Todos los tipos de equipo ordenados por nombre
        ]);
    }

    public function storeMarca(Request $request): RedirectResponse
    {
        $request->validate(['marca' => ['required', 'string', 'max:50']]); // Valida el nombre de la nueva marca

        Marca::create(['marca' => $request->marca]); // Registra la marca en la base de datos

        return redirect() // Devuelve una redirección
            ->route('admin.equipos.catalogos') // Hacia el catálogo de marcas y tipos
            ->with('status', 'Marca agregada correctamente.'); // Con un mensaje de éxito en la sesión
    }

    public function destroyMarca(Marca $marca): RedirectResponse
    {
        if ($marca->equipos()->exists()) { // Verifica si hay equipos que usan esta marca
            return redirect() // Devuelve una redirección
                ->route('admin.equipos.catalogos') // Hacia el catálogo
                ->with('error', 'No puedes eliminar una marca que est&aacute; en uso.'); // Con un mensaje de error en la sesión
        }

        $marca->delete(); // Elimina la marca de la base de datos

        return redirect() // Devuelve una redirección
            ->route('admin.equipos.catalogos') // Hacia el catálogo
            ->with('status', 'Marca eliminada correctamente.'); // Con un mensaje de éxito en la sesión
    }

    public function storeTipo(Request $request): RedirectResponse
    {
        $request->validate(['tipo' => ['required', 'string', 'max:50']]); // Valida el nombre del nuevo tipo de equipo

        TipoEquipo::create(['tipo' => $request->tipo]); // Registra el tipo en la base de datos

        return redirect() // Devuelve una redirección
            ->route('admin.equipos.catalogos') // Hacia el catálogo de marcas y tipos
            ->with('status', 'Tipo de equipo agregado correctamente.'); // Con un mensaje de éxito en la sesión
    }

    public function destroyTipo(TipoEquipo $tipo): RedirectResponse
    {
        if ($tipo->equipos()->exists()) { // Verifica si hay equipos que usan este tipo
            return redirect() // Devuelve una redirección
                ->route('admin.equipos.catalogos') // Hacia el catálogo
                ->with('error', 'No puedes eliminar un tipo de equipo que est&aacute; en uso.'); // Con un mensaje de error en la sesión
        }

        $tipo->delete(); // Elimina el tipo de la base de datos

        return redirect() // Devuelve una redirección
            ->route('admin.equipos.catalogos') // Hacia el catálogo
            ->with('status', 'Tipo de equipo eliminado correctamente.'); // Con un mensaje de éxito en la sesión
    }

    private function formData(): array
    {
        return [
            'marcas' => Marca::orderBy('id_marca')->get(), // Obtiene todas las marcas ordenadas por id
            'tipos' => TipoEquipo::orderBy('id_t_equip')->get(), // Obtiene todos los tipos de equipo ordenados por id
            'usuarios' => User::orderBy('Nom_usua')->get(), // Obtiene todos los usuarios ordenados por nombre
        ];
    }
}

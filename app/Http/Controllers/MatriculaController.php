<?php

// Espacio de nombres al que pertenece este controlador

namespace App\Http\Controllers;

use App\Http\Requests\StoreMatriculaRequest; // Request de validación para crear una matrícula
use App\Models\Acudiente; // Modelo que representa el catálogo de acudientes
use App\Models\EstudianteAcudiente; // Modelo que representa el vínculo entre estudiante y acudiente
use App\Models\Jornada; // Modelo que representa el catálogo de jornadas
use App\Models\Matricula; // Modelo que representa la tabla de matrículas
use App\Models\Parentesco; // Modelo que representa el catálogo de parentescos
use App\Models\Salon; // Modelo que representa la tabla de salones
use App\Models\Sede; // Modelo que representa la tabla de sedes
use App\Models\User; // Modelo que representa la tabla de usuarios
use Illuminate\Http\RedirectResponse; // Tipo de retorno para respuestas de redirección
use Illuminate\Http\Request; // Clase base para manejar la petición HTTP
use Illuminate\Support\Facades\DB; // Fachada para ejecutar la transacción que agrupa el guardado
use Illuminate\View\View; // Tipo de retorno para vistas

class MatriculaController extends Controller
{
    // funcion para mostrar los registros
    public function index(Request $request): View
    {
        // funcion $buscar para que funcione la barra de busqueda
        $buscar = trim((string) $request->query('buscar', '')); // Obtiene y limpia el término de búsqueda de la URL

        // donde se define qué se va a buscar y en qué orden se va a mostrar
        $matriculas = Matricula::with(['estudiante', 'salon']) // Consulta las matrículas con su estudiante y salón relacionados
            ->when($buscar !== '', fn ($query) => $query->where(function ($query) use ($buscar) { // Filtra si hay término de búsqueda
                $query->where('grado', 'like', '%'.$buscar.'%') // Buscando por el grado del estudiante
                    ->orWhere('Salon', 'like', '%'.$buscar.'%') // O por el código del salón
                    ->orWhereHas('estudiante', fn ($query) => $query->where('Nom_usua', 'like', '%'.$buscar.'%')); // O por el nombre del estudiante
            })) // Cierra el filtro agrupado para no romper la precedencia de los AND
            ->orderByDesc('numero_matri') // Ordena los resultados por número de matrícula de forma descendente
            ->paginate(10) // Pagina los resultados de 10 en 10
            ->withQueryString(); // Conserva los filtros de la URL en los enlaces de paginación

        // donde se dice cual vista es la que se va a mostrar despues de la consulta
        return view('admin.matriculas.index', compact('matriculas', 'buscar')); // Muestra la vista del listado con los datos consultados
    }

    // funcion para mostrar el formulario de creacion
    public function create(): View
    {
        return view('admin.matriculas.create', $this->formData()); // Muestra el formulario de creación con los datos de apoyo
    }

    // funcion para guardar la matricula creada
    public function store(StoreMatriculaRequest $request): RedirectResponse
    {
        $data = $request->safe()->only([
            'Documento_matri', 'grado', 'fecha_matri', 'Jornada', 'Salon', 'Nit',
        ]); // Descarta los campos de acudientes, que se procesan aparte

        $documento = (int) $data['Documento_matri']; // Documento del estudiante que se matricula

        // Toda la operación se ejecuta en una transacción para no dejar vínculos a medias
        DB::transaction(function () use ($data, $documento, $request): void {
            Matricula::create($data); // Crea la nueva matrícula en la base de datos

            $this->vincularAcudientes($request, $documento); // Crea y vincula los acudientes indicados
        });

        return redirect() // Devuelve una redirección
            ->route('admin.matriculas.index') // Hacia el listado de matrículas
            ->with('status', 'Matrícula registrada correctamente.'); // Con un mensaje de éxito en la sesión
    }

    /**
     * Crea los acudientes nuevos indicados y los vincula al estudiante junto con los del catálogo.
     *
     * @param  StoreMatriculaRequest  $request  Petición ya validada con los datos de los acudientes
     * @param  int  $documento  Documento del estudiante al que se le asignan los acudientes
     */
    private function vincularAcudientes(StoreMatriculaRequest $request, int $documento): void
    {
        // Crea cada acudiente nuevo del formulario y guarda su documento para vincularlo
        foreach ($request->input('nuevos_acudientes', []) as $nuevo) {
            $documentoAcudiente = (string) $nuevo['documento']; // Documento con el que se creará el acudiente

            // Se omite la creación si el documento ya quedó registrado en una iteración anterior
            if (Acudiente::where('Documento_acud', $documentoAcudiente)->exists()) {
                continue;
            }

            Acudiente::create([
                'Documento_acud' => $documentoAcudiente, // Documento del nuevo acudiente
                'nombre' => $nuevo['nombre'], // Nombre completo del nuevo acudiente
                'tel_acu' => $nuevo['telefono'], // Teléfono principal del nuevo acudiente
                'tel_acu2' => $nuevo['telefono_alt'] ?? null, // Teléfono alterno del nuevo acudiente
                'Direcccion_acu' => $nuevo['direccion'] ?? null, // Dirección del nuevo acudiente
                'Correo_acud' => $nuevo['correo'], // Correo electrónico del nuevo acudiente
            ]); // Registra el acudiente en el catálogo compartido

            $this->crearVinculo($documento, $documentoAcudiente, (int) $nuevo['parentesco']); // Vincula el acudiente recién creado con el estudiante
        }

        // Vincula los acudientes que ya estaban en el catálogo
        foreach ($request->input('acudientes_existentes', []) as $existente) {
            $this->crearVinculo($documento, (string) $existente['documento'], (int) $existente['parentesco']); // Registra el vínculo del estudiante con el acudiente del catálogo
        }
    }

    /**
     * Registra el vínculo estudiante-acudiente evitando duplicados para el mismo estudiante.
     *
     * @param  int  $documento  Documento del estudiante
     * @param  string  $documentoAcudiente  Documento del acudiente
     * @param  int  $parentesco  Identificador del parentesco entre ambos
     */
    private function crearVinculo(int $documento, string $documentoAcudiente, int $parentesco): void
    {
        EstudianteAcudiente::firstOrCreate([ // Crea el vínculo solo si el estudiante aún no tiene a ese acudiente
            'Documento_estu' => $documento, // Documento del estudiante del vínculo
            'Documento_acud' => $documentoAcudiente, // Documento del acudiente del vínculo
        ], [
            'id_parentesco' => $parentesco, // Parentesco que se asigna en el vínculo
        ]); // Guarda la relación en la tabla estudiante_acudiente
    }

    private function formData(): array
    {
        return [
            'salones' => Salon::with('estado') // Obtiene los salones con su estado para descartar los inactivos
                ->whereHas('estado', fn ($query) => $query->where('estado', 1)) // Filtra solo los salones activos
                ->orderBy('codigo') // Ordena los salones por su código
                ->get(), // Ejecuta la consulta y trae los resultados
            'jornadas' => Jornada::orderBy('id_jornada')->get(), // Obtiene todas las jornadas del catálogo ordenadas por id
            'estudiantes' => User::whereHas('rol', fn ($query) => $query->where('rol', 'Estudiante')) // Filtra los usuarios cuyo rol es estudiante
                ->whereDoesntHave('matricula') // Excluye los estudiantes que ya tienen matrícula registrada
                ->with('acudientes') // Carga los acudientes ya vinculados al estudiante
                ->orderBy('Nom_usua') // Ordena los estudiantes por su nombre
                ->get(), // Ejecuta la consulta y trae los resultados
            'sedes' => Sede::activas() // Obtiene las sedes activas para el selector de sede
                ->orderBy('nombre') // Ordena las sedes por su nombre
                ->get(), // Ejecuta la consulta y trae los resultados
            'acudientes' => Acudiente::orderBy('nombre')->get(), // Obtiene el catálogo completo de acudientes reutilizables
            'parentescos' => Parentesco::activos()->orderBy('parentesco')->get(), // Obtiene solo los parentescos activos del catálogo
            'acudientesPorEstudiante' => $this->acudientesPorEstudiante(), // Mapa de vínculos ya existentes por estudiante
        ];
    }

    /**
     * Arma el mapa documento del estudiante => lista de sus acudientes, para que el formulario
     * pueda avisar cuáles están vinculados sin recargar la página.
     *
     * @return array<string, array<int, array{documento: string, nombre: string, parentesco: string|null}>>
     */
    private function acudientesPorEstudiante(): array
    {
        // Obtiene todos los vínculos con sus datos ya resueltos
        $vinculos = EstudianteAcudiente::with(['acudiente', 'parentesco'])->get(); // Carga los vínculos del estudiante junto al acudiente y el parentesco

        $mapa = []; // Diccionario que agrupa los vínculos por documento del estudiante

        foreach ($vinculos as $vinculo) {
            // Se omiten los vínculos cuyo acudiente o parentesco ya fueron eliminados
            if (! $vinculo->acudiente) {
                continue;
            }

            $mapa[(string) $vinculo->Documento_estu][] = [ // Añade el acudiente a la lista del estudiante
                'documento' => (string) $vinculo->acudiente->Documento_acud, // Documento del acudiente vinculado
                'nombre' => $vinculo->acudiente->nombre, // Nombre del acudiente vinculado
                'parentesco' => $vinculo->parentesco?->parentesco, // Parentesco con el que fue vinculado
            ];
        }

        return $mapa; // Devuelve el mapa listo para incrustar en la vista
    }
}

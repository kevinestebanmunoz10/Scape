<?php

namespace App\Http\Controllers;

use App\Models\Autorizacion; // Modelo que representa las autorizaciones de salida
use App\Models\TipoPermiso; // Modelo que representa los tipos de permiso
use Illuminate\Http\RedirectResponse; // Tipo de retorno para respuestas de redirección
use Illuminate\Http\Request; // Clase base para manejar la petición HTTP
use Illuminate\Support\Collection; // Fachada para ejecutar consultas a la base de datos
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

// Clase de respuesta para las vistas

class PermisoController extends Controller
{
    public function index(): View
    {
        $permisos = DB::table('autorizacion') // Consulta la tabla de autorizaciones
            ->leftJoin('estudiante_acudiente', 'autorizacion.id_estu_acud', '=', 'estudiante_acudiente.id_estu_acud') // Une la relación estudiante-acudiente
            ->leftJoin('usuario', 'estudiante_acudiente.Documento_estu', '=', 'usuario.Documento') // Une al usuario como estudiante
            ->leftJoin('acudiente', 'estudiante_acudiente.Documento_acud', '=', 'acudiente.Documento_acud') // Une al acudiente
            ->leftJoin('usuario as autor', 'autorizacion.Documento_auto', '=', 'autor.Documento') // Une al usuario que autorizó el permiso
            ->select( // Selecciona los campos que se van a mostrar
                'autorizacion.id_autorizacion', // El identificador de la autorización
                'autorizacion.Permiso as tipo', // El tipo de permiso
                'autorizacion.Descripcion as descripcion', // La descripción del permiso
                'autorizacion.Fecha as fecha', // La fecha del permiso
                'usuario.Nom_usua as estudiante', // El nombre del estudiante
                'usuario.Documento as documento_estudiante', // El documento del estudiante
                'acudiente.nombre as acudiente', // El nombre del acudiente
                'autor.Nom_usua as autorizado_por', // El nombre de quien autorizó
            )
            ->orderByDesc('autorizacion.id_autorizacion') // Ordena los permisos por el más reciente primero
            ->paginate(10); // Pagina los resultados de diez en diez

        return view('admin.permisos', [ // Muestra la vista de permisos con los datos
            'permisos' => $permisos, // Los permisos registrados y paginados
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validarDatos($request); // Valida los datos enviados en el formulario del permiso

        $tipo = TipoPermiso::findOrFail($datos['id_tipo_permiso']); // Busca el tipo de permiso seleccionado

        $idEstuAcud = $this->resolverVinculo($datos['Documento_estu'], $datos['Documento_acud']); // Obtiene o crea la relación estudiante-acudiente

        Autorizacion::create([ // Registra el permiso de salida en la base de datos
            'Permiso' => $tipo->tipo, // El nombre del tipo de permiso
            'Descripcion' => $datos['Descripcion'], // La descripción del permiso
            'Fecha' => now()->toDateString(), // La fecha actual
            'id_rol' => $request->user()->id_rol, // El rol del usuario que registra el permiso
            'id_estu_acud' => $idEstuAcud, // La relación estudiante-acudiente
            'Documento_auto' => null, // El permiso queda pendiente de autorización
        ]);

        return redirect() // Devuelve una redirección
            ->route('rector.dashboard') // Hacia el panel del rector
            ->with('status', 'Permiso de salida registrado correctamente.'); // Con un mensaje de éxito en la sesión
    }

    public function edit(Autorizacion $permiso): View
    {
        $vinculo = DB::table('estudiante_acudiente')->find($permiso->id_estu_acud); // Busca la relación estudiante-acudiente del permiso

        $tipo = TipoPermiso::where('tipo', $permiso->Permiso)->first(); // Busca el tipo de permiso por su nombre

        return view('admin.permisos_edit', [ // Muestra la vista de edición del permiso
            'permiso' => $permiso, // El permiso que se está editando
            'estudiantes' => $this->estudiantes(), // Los estudiantes disponibles
            'acudientes' => $this->acudientes(), // Los acudientes disponibles
            'acudienteEstudiantes' => $this->estudiantesPorAcudiente(), // Los estudiantes vinculados a cada acudiente
            'tipos' => $this->obtenerTipos(), // Los tipos de permiso disponibles
            'documentoEstu' => $vinculo?->Documento_estu, // El documento del estudiante a seleccionar
            'documentoAcud' => $vinculo?->Documento_acud, // El documento del acudiente a seleccionar
            'idTipo' => $tipo?->id_tipo_permiso, // El tipo de permiso a seleccionar
            'descripcion' => $permiso->Descripcion, // La descripción actual del permiso
        ]);
    }

    public function update(Request $request, Autorizacion $permiso): RedirectResponse
    {
        $datos = $this->validarDatos($request); // Valida los datos enviados en el formulario de edición

        $tipo = TipoPermiso::findOrFail($datos['id_tipo_permiso']); // Busca el tipo de permiso seleccionado

        $idEstuAcud = $this->resolverVinculo($datos['Documento_estu'], $datos['Documento_acud']); // Obtiene o crea la relación estudiante-acudiente

        $permiso->update([ // Actualiza los datos del permiso
            'Permiso' => $tipo->tipo, // El nombre del tipo de permiso
            'Descripcion' => $datos['Descripcion'], // La descripción del permiso
            'id_estu_acud' => $idEstuAcud, // La relación estudiante-acudiente
        ]);

        return redirect() // Devuelve una redirección
            ->route('admin.permisos') // Hacia la vista de permisos
            ->with('status', 'Permiso actualizado correctamente.'); // Con un mensaje de éxito en la sesión
    }

    public function destroy(Autorizacion $permiso): RedirectResponse
    {
        $permiso->delete(); // Elimina el permiso de salida de la base de datos

        return redirect() // Devuelve una redirección
            ->route('admin.permisos') // Hacia la vista de permisos
            ->with('status', 'Permiso eliminado correctamente.'); // Con un mensaje de éxito en la sesión
    }

    public function tipos(): View
    {
        $tipos = TipoPermiso::orderBy('tipo')->get(); // Obtiene todos los tipos de permiso ordenados por nombre

        return view('admin.permisos_tipos', [ // Muestra la vista del catálogo de tipos de permiso
            'tipos' => $tipos, // Los tipos de permiso disponibles
        ]);
    }

    public function storeTipo(Request $request): RedirectResponse
    {
        $request->validate(['tipo' => ['required', 'string', 'max:50']]); // Valida el nombre del nuevo tipo de permiso

        TipoPermiso::create(['tipo' => $request->tipo]); // Registra el tipo de permiso en la base de datos

        return redirect() // Devuelve una redirección
            ->route('admin.permisos.tipos') // Hacia la vista de tipos de permiso
            ->with('status', 'Tipo de permiso agregado correctamente.'); // Con un mensaje de éxito en la sesión
    }

    public function destroyTipo(TipoPermiso $tipoPermiso): RedirectResponse
    {
        if (DB::table('autorizacion')->where('Permiso', $tipoPermiso->tipo)->exists()) { // Verifica si el tipo está en uso por algún permiso
            return redirect() // Devuelve una redirección
                ->route('admin.permisos.tipos') // Hacia la vista de tipos de permiso
                ->with('error', 'No puedes eliminar un tipo de permiso que est&aacute; en uso.'); // Con un mensaje de error en la sesión
        }

        $tipoPermiso->delete(); // Elimina el tipo de permiso de la base de datos

        return redirect() // Devuelve una redirección
            ->route('admin.permisos.tipos') // Hacia la vista de tipos de permiso
            ->with('status', 'Tipo de permiso eliminado correctamente.'); // Con un mensaje de éxito en la sesión
    }

    private function validarDatos(Request $request): array
    {
        return $request->validate([ // Valida los datos enviados en el formulario del permiso
            'Documento_estu' => ['required', 'exists:usuario,Documento', 'exists:matricula,Documento_matri'], // El estudiante debe existir y estar matriculado
            'Documento_acud' => ['required', 'exists:acudiente,Documento_acud'], // El acudiente debe existir
            'id_tipo_permiso' => ['required', 'exists:tipo_permiso,id_tipo_permiso'], // El tipo de permiso debe existir en el catálogo
            'Descripcion' => ['required', 'string', 'max:2000'], // La descripción es obligatoria con máximo de 2000 caracteres
        ]);
    }

    private function resolverVinculo(int|string $documentoEstu, string $documentoAcud): int
    {
        $vinculo = DB::table('estudiante_acudiente') // Consulta la relación estudiante-acudiente
            ->where('Documento_estu', $documentoEstu) // Filtra por el documento del estudiante
            ->where('Documento_acud', $documentoAcud) // Filtra por el documento del acudiente
            ->first(); // Retorna la relación existente si la hay

        if ($vinculo) { // Verifica si la relación ya existe
            return $vinculo->id_estu_acud; // Reutiliza el identificador de la relación existente
        }

        return DB::table('estudiante_acudiente')->insertGetId([ // Crea la relación estudiante-acudiente
            'Documento_estu' => $documentoEstu, // El documento del estudiante
            'Documento_acud' => $documentoAcud, // El documento del acudiente
            'id_parentesco' => null, // Sin parentesco definido
        ]);
    }

    private function estudiantes(): Collection
    {
        return DB::table('usuario') // Consulta los usuarios matriculados
            ->join('matricula', 'usuario.Documento', '=', 'matricula.Documento_matri') // Une con la tabla de matrícula para identificar estudiantes
            ->select('usuario.Documento', 'usuario.Nom_usua') // Selecciona el documento y el nombre del estudiante
            ->distinct() // Evita duplicados si el estudiante tiene varias matrículas
            ->orderBy('usuario.Nom_usua') // Ordena los estudiantes por nombre
            ->get(); // Ejecuta la consulta y devuelve los resultados
    }

    private function acudientes(): Collection
    {
        return DB::table('acudiente') // Consulta la tabla de acudientes
            ->orderBy('nombre') // Ordena los acudientes por nombre
            ->get(); // Ejecuta la consulta y devuelve los resultados
    }

    private function obtenerTipos(): Collection
    {
        return TipoPermiso::orderBy('tipo')->get(); // Obtiene todos los tipos de permiso ordenados por nombre
    }

    private function estudiantesPorAcudiente(): Collection
    {
        return DB::table('estudiante_acudiente') // Consulta las relaciones estudiante-acudiente
            ->select('Documento_acud', 'Documento_estu') // Selecciona los documentos de ambos
            ->get() // Ejecuta la consulta
            ->groupBy('Documento_acud') // Agrupa las relaciones por acudiente
            ->map(fn ($relaciones) => $relaciones->pluck('Documento_estu')->all()); // Extrae los documentos de los estudiantes por acudiente
    }
}

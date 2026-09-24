<?php

// Espacio de nombres al que pertenece este controlador

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Clase base para manejar la petición HTTP
use Illuminate\Pagination\LengthAwarePaginator; // Paginador manual para colecciones
use Illuminate\Support\Collection; // Colección de datos para trabajar con arrays
use Illuminate\Support\Facades\DB; // Fachada para ejecutar consultas a la base de datos
use Illuminate\View\View; // Clase de respuesta para las vistas

class AccesoController extends Controller
{
    public function index(Request $request)
    {
        $desde = ($request->date('desde') ?? now()->startOfMonth())->startOfDay(); // Fecha inicial del filtro, por defecto el primer día del mes al inicio del día
        $hasta = $request->date('hasta') ?? now(); // Fecha final del filtro, por defecto la fecha actual

        if ($desde->greaterThan($hasta)) { // Verifica si la fecha inicial es mayor que la final
            [$desde, $hasta] = [$hasta->copy()->startOfDay(), $desde->copy()]; // Intercambia los valores para corregir el rango
        }

        $hastaFin = $hasta->copy()->endOfDay(); // Convierte la fecha final al último instante del día
        $tipo = $request->string('tipo', 'todos')->toString(); // Obtiene el tipo de acceso filtrado, por defecto todos
        $buscar = trim($request->string('buscar')->toString()); // Obtiene y limpia el término de búsqueda

        $accesos = collect(); // Crea una colección vacía para acumular los accesos

        if (in_array($tipo, ['todos', 'usuario'], true)) { // Verifica si se deben incluir los accesos de usuarios
            $accesos = $accesos->merge($this->accesosDeUsuarios($desde, $hastaFin)); // Agrega los accesos de usuarios a la colección
        }

        if (in_array($tipo, ['todos', 'estudiante'], true)) { // Verifica si se deben incluir los accesos de estudiantes
            $accesos = $accesos->merge($this->accesosDeEstudiantes($desde, $hastaFin)); // Agrega los accesos de estudiantes a la colección
        }

        if (in_array($tipo, ['todos', 'visitante'], true)) { // Verifica si se deben incluir los accesos de visitantes
            $accesos = $accesos->merge($this->accesosDeVisitantes($desde, $hastaFin)); // Agrega los accesos de visitantes a la colección
        }

        if ($buscar !== '') { // Verifica si hay un término de búsqueda
            $termino = mb_strtolower($buscar); // Convierte el término a minúsculas para comparar sin distinción de mayúsculas

            $accesos = $accesos->filter(fn ($acceso) => str_contains(mb_strtolower((string) $acceso->nombre), $termino) // Filtra los accesos cuyo nombre contiene el término
                || str_contains((string) $acceso->documento, $termino)); // O cuyo documento contiene el término
        }

        $accesos = $accesos->sortByDesc('f_ingreso')->values(); // Ordena los accesos por fecha de ingreso descendente y reindexa

        $totalAccesos = $accesos->count(); // Cuenta el total de accesos
        $entradas = $accesos->whereNull('f_salida')->count(); // Cuenta los accesos que aún están dentro (sin salida)
        $salidas = $totalAccesos - $entradas; // Calcula el total de salidas restando las entradas
        $visitantes = $accesos->where('tipo', 'Visitante')->count(); // Cuenta cuántos accesos son de visitantes

        $porPagina = 10; // Define el número de accesos por página
        $pagina = max(1, (int) $request->input('page', 1)); // Obtiene la página actual garantizando un mínimo de 1

        $accesosPaginados = new LengthAwarePaginator( // Crea un paginador manual con los accesos
            $accesos->forPage($pagina, $porPagina)->values(), // Los accesos correspondientes a la página actual
            $totalAccesos, // El total de elementos para el paginador
            $porPagina, // La cantidad por página
            $pagina, // La página actual
            ['path' => $request->url(), 'query' => $request->query()], // Conserva la URL y los filtros al paginar
        );

        return view('admin.accesos', [ // Muestra la vista de accesos con los datos
            'accesos' => $accesosPaginados, // Los accesos paginados
            'desde' => $desde, // La fecha inicial del filtro
            'hasta' => $hasta, // La fecha final del filtro
            'tipo' => $tipo, // El tipo de acceso filtrado
            'buscar' => $buscar, // El término de búsqueda
            'totalAccesos' => $totalAccesos, // El total de accesos calculado
            'entradas' => $entradas, // El número de entradas (dentro)
            'salidas' => $salidas, // El número de salidas
            'visitantes' => $visitantes, // El número de visitantes
        ]);
    }

    public function equipos(Request $request): View
    {
        $desde = ($request->date('desde') ?? now()->startOfMonth())->startOfDay(); // Fecha inicial del filtro, por defecto el primer día del mes al inicio del día
        $hasta = $request->date('hasta') ?? now(); // Fecha final del filtro, por defecto la fecha actual

        if ($desde->greaterThan($hasta)) { // Verifica si la fecha inicial es mayor que la final
            [$desde, $hasta] = [$hasta->copy()->startOfDay(), $desde->copy()]; // Intercambia los valores para corregir el rango
        }

        $hastaFin = $hasta->copy()->endOfDay(); // Convierte la fecha final al último instante del día
        $buscar = trim($request->string('buscar')->toString()); // Obtiene y limpia el término de búsqueda

        $consulta = DB::table('acceso_equi') // Consulta la tabla de accesos de equipos
            ->leftJoin('equipo', 'acceso_equi.serial_equi', '=', 'equipo.serial_equi') // Une la tabla de equipos por serial
            ->leftJoin('usuario', 'acceso_equi.Documento', '=', 'usuario.Documento') // Une la tabla de usuarios por documento
            ->leftJoin('marca', 'equipo.id_Marca', '=', 'marca.id_marca') // Une la tabla de marcas por identificador
            ->leftJoin('tipo_equipo', 'equipo.id_t_equip', '=', 'tipo_equipo.id_t_equip') // Une la tabla de tipos de equipo por identificador
            ->whereBetween('acceso_equi.f_entrada', [$desde, $hastaFin]); // Filtra los ingresos dentro del rango de fechas

        if ($buscar !== '') { // Verifica si hay un término de búsqueda
            $consulta->where('acceso_equi.serial_equi', 'like', '%'.$buscar.'%'); // Filtra los accesos cuyo serial contiene el término
        }

        $totalAccesos = (clone $consulta)->count(); // Cuenta el total de accesos de equipos
        $dentro = (clone $consulta)->whereNull('acceso_equi.f_salida')->count(); // Cuenta los equipos que aún están dentro (sin salida)
        $salidas = $totalAccesos - $dentro; // Calcula el total de salidas restando las que están dentro
        $equiposUnicos = (clone $consulta)->distinct()->count('acceso_equi.serial_equi'); // Cuenta los equipos distintos que han accedido

        $equipos = $consulta->select( // Selecciona los campos que se van a mostrar
            'acceso_equi.serial_equi as serial', // El serial del equipo
            'acceso_equi.f_entrada as f_entrada', // La fecha de entrada del equipo
            'acceso_equi.f_salida as f_salida', // La fecha de salida del equipo
            'tipo_equipo.tipo as tipo', // El tipo de equipo
            'marca.marca as marca', // La marca del equipo
            'equipo.Color as color', // El color del equipo
            'usuario.Nom_usua as nombre', // El nombre del responsable del acceso
        )
            ->orderByDesc('acceso_equi.f_entrada') // Ordena los accesos por fecha de entrada descendente
            ->paginate(10) // Pagina los resultados de a diez por página
            ->withQueryString(); // Conserva los filtros de la URL al paginar

        return view('admin.accesos_equipos', [ // Muestra la vista de accesos de equipos con los datos
            'equipos' => $equipos, // Los accesos de equipos paginados
            'desde' => $desde, // La fecha inicial del filtro
            'hasta' => $hasta, // La fecha final del filtro
            'buscar' => $buscar, // El término de búsqueda
            'totalAccesos' => $totalAccesos, // El total de accesos calculado
            'dentro' => $dentro, // El número de equipos dentro
            'salidas' => $salidas, // El número de equipos fuera
            'equiposUnicos' => $equiposUnicos, // El número de equipos distintos
        ]);
    }

    /**
     * @return Collection<int, object>
     */
    private function accesosDeUsuarios($desde, $hastaFin): Collection
    {
        return DB::table('acceso_usu') // Consulta la tabla de accesos de usuarios
            ->leftJoin('usuario', 'acceso_usu.Documento_acces', '=', 'usuario.Documento') // Une la tabla de usuarios por documento
            ->whereBetween('acceso_usu.f_ingreso', [$desde, $hastaFin]) // Filtra los ingresos dentro del rango de fechas
            ->select( // Selecciona los campos que se van a mostrar
                'usuario.Nom_usua as nombre', // El nombre del usuario
                'acceso_usu.Documento_acces as documento', // El documento del acceso
                'acceso_usu.f_ingreso as f_ingreso', // La fecha de ingreso
                'acceso_usu.f_salida as f_salida', // La fecha de salida
                DB::raw("'Usuario' as tipo"), // Etiqueta fija de tipo de acceso
            )
            ->get(); // Ejecuta la consulta y devuelve los resultados
    }

    /**
     * @return Collection<int, object>
     */
    private function accesosDeEstudiantes($desde, $hastaFin): Collection
    {
        return DB::table('acceso_estu') // Consulta la tabla de accesos de estudiantes
            ->leftJoin('usuario', 'acceso_estu.Documento_estu', '=', 'usuario.Documento') // Une la tabla de usuarios por documento
            ->whereBetween('acceso_estu.f_ingreso', [$desde, $hastaFin]) // Filtra los ingresos dentro del rango de fechas
            ->select( // Selecciona los campos que se van a mostrar
                'usuario.Nom_usua as nombre', // El nombre del usuario
                'acceso_estu.Documento_estu as documento', // El documento del acceso
                'acceso_estu.f_ingreso as f_ingreso', // La fecha de ingreso
                'acceso_estu.f_salida as f_salida', // La fecha de salida
                DB::raw("'Estudiante' as tipo"), // Etiqueta fija de tipo de acceso
            )
            ->get(); // Ejecuta la consulta y devuelve los resultados
    }

    /**
     * @return Collection<int, object>
     */
    private function accesosDeVisitantes($desde, $hastaFin): Collection
    {
        return DB::table('acceso_visi') // Consulta la tabla de accesos de visitantes
            ->leftJoin('visitantes', 'acceso_visi.Documento_visi', '=', 'visitantes.Docu_visi') // Une la tabla de visitantes por documento
            ->whereBetween('acceso_visi.f_ingreso', [$desde, $hastaFin]) // Filtra los ingresos dentro del rango de fechas
            ->select( // Selecciona los campos que se van a mostrar
                'visitantes.Nom_visi as nombre', // El nombre del visitante
                'acceso_visi.Documento_visi as documento', // El documento del acceso
                'acceso_visi.f_ingreso as f_ingreso', // La fecha de ingreso
                'acceso_visi.f_salida as f_salida', // La fecha de salida
                DB::raw("'Visitante' as tipo"), // Etiqueta fija de tipo de acceso
            )
            ->get(); // Ejecuta la consulta y devuelve los resultados
    }
}

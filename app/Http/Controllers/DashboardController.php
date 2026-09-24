<?php

// Espacio de nombres al que pertenece este controlador

namespace App\Http\Controllers;

use App\Models\PrestamoEquipo; // Modelo que representa la tabla de préstamos de equipos
use App\Models\User; // Modelo que representa la tabla de usuarios
use Illuminate\Http\Request; // Clase base para manejar la petición HTTP
use Illuminate\Support\Facades\DB; // Fachada para ejecutar consultas a la base de datos

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $desde = ($request->date('desde') ?? now()->startOfMonth())->startOfDay(); // Fecha inicial del filtro, por defecto el primer día del mes al inicio del día
        $hasta = $request->date('hasta') ?? now(); // Fecha final del filtro, por defecto la fecha actual

        if ($desde->greaterThan($hasta)) { // Verifica si la fecha inicial es mayor que la final
            [$desde, $hasta] = [$hasta->copy()->startOfDay(), $desde->copy()]; // Intercambia los valores para corregir el rango
        }

        $hastaFin = $hasta->copy()->endOfDay(); // Convierte la fecha final al último instante del día

        $totalUsuarios = User::count(); // Cuenta el total de usuarios registrados
        $usuariosActivos = User::whereHas('estado', fn ($q) => $q->where('estado', 1))->count(); // Cuenta los usuarios con estado activo
        $usuariosInactivos = $totalUsuarios - $usuariosActivos; // Calcula los usuarios inactivos restando los activos

        $accesosRango = 0; // Inicializa el contador de accesos en el rango
        $entradas = 0; // Inicializa el contador de entradas (dentro)

        foreach (['acceso_usu', 'acceso_estu', 'acceso_visi'] as $tabla) { // Recorre las tres tablas de accesos
            $filas = DB::table($tabla) // Consulta cada tabla de accesos
                ->whereBetween('f_ingreso', [$desde, $hastaFin]) // Filtra los ingresos dentro del rango de fechas
                ->get(['f_salida']); // Obtiene únicamente la columna de fecha de salida

            $accesosRango += $filas->count(); // Acumula el total de accesos de la tabla
            $entradas += $filas->whereNull('f_salida')->count(); // Acumula las entradas que aún no tienen salida
        }

        $salidas = $accesosRango - $entradas; // Calcula el total de salidas en el rango

        $prestamosActivos = PrestamoEquipo::whereNull('f_devolucion')->count(); // Cuenta los préstamos que aún no se han devuelto
        $prestamosVencidos = PrestamoEquipo::whereNull('f_devolucion') // Consulta los préstamos sin devolver
            ->whereDate('f_Prestamo', '<', today()) // Filtra los prestados antes del día de hoy
            ->count(); // Cuenta los préstamos vencidos

        $visitantesRango = DB::table('acceso_visi') // Consulta la tabla de accesos de visitantes
            ->whereBetween('f_ingreso', [$desde, $hastaFin]) // Filtra los ingresos dentro del rango de fechas
            ->count(DB::raw('DISTINCT Documento_visi')); // Cuenta los visitantes únicos por documento

        $accesosU = DB::table('acceso_usu') // Consulta la tabla de accesos de usuarios
            ->leftJoin('usuario', 'acceso_usu.Documento_acces', '=', 'usuario.Documento') // Une la tabla de usuarios por documento
            ->whereBetween('acceso_usu.f_ingreso', [$desde, $hastaFin]) // Filtra los ingresos dentro del rango de fechas
            ->select('usuario.Nom_usua as nombre', 'usuario.Documento as documento', 'acceso_usu.f_ingreso as fecha', DB::raw("'Usuario' as tipo")) // Selecciona los datos a mostrar
            ->get() // Ejecuta la consulta
            ->all(); // Convierte los resultados en un array

        $accesosE = DB::table('acceso_estu') // Consulta la tabla de accesos de estudiantes
            ->leftJoin('usuario', 'acceso_estu.Documento_estu', '=', 'usuario.Documento') // Une la tabla de usuarios por documento
            ->whereBetween('acceso_estu.f_ingreso', [$desde, $hastaFin]) // Filtra los ingresos dentro del rango de fechas
            ->select('usuario.Nom_usua as nombre', 'usuario.Documento as documento', 'acceso_estu.f_ingreso as fecha', DB::raw("'Estudiante' as tipo")) // Selecciona los datos a mostrar
            ->get() // Ejecuta la consulta
            ->all(); // Convierte los resultados en un array

        $accesosV = DB::table('acceso_visi') // Consulta la tabla de accesos de visitantes
            ->leftJoin('visitantes', 'acceso_visi.Documento_visi', '=', 'visitantes.Docu_visi') // Une la tabla de visitantes por documento
            ->whereBetween('acceso_visi.f_ingreso', [$desde, $hastaFin]) // Filtra los ingresos dentro del rango de fechas
            ->select('visitantes.Nom_visi as nombre', 'acceso_visi.Documento_visi as documento', 'acceso_visi.f_ingreso as fecha', DB::raw("'Visitante' as tipo")) // Selecciona los datos a mostrar
            ->get() // Ejecuta la consulta
            ->all(); // Convierte los resultados en un array

        $ultimosAccesos = collect(array_merge($accesosU, $accesosE, $accesosV)) // Une los accesos de las tres tablas en una colección
            ->filter(fn ($a) => $a->fecha !== null) // Descarta los accesos sin fecha de ingreso
            ->sortByDesc('fecha') // Ordena por fecha de ingreso descendente
            ->take(5) // Toma únicamente los 5 más recientes
            ->values(); // Reindexa la colección

        $prestamosPendientes = PrestamoEquipo::whereNull('f_devolucion') // Consulta los préstamos sin devolver
            ->whereBetween('f_Prestamo', [$desde, $hastaFin]) // Filtra los prestados dentro del rango de fechas
            ->with('equipo.tipo', 'equipo.marca', 'usuario') // Carga las relaciones del equipo y del usuario
            ->orderByDesc('f_Prestamo') // Ordena por fecha de préstamo descendente
            ->get(); // Ejecuta la consulta

        return view('admin.dashboard', [ // Muestra la vista del panel administrativo con los datos
            'desde' => $desde, // La fecha inicial del filtro
            'hasta' => $hasta, // La fecha final del filtro
            'totalUsuarios' => $totalUsuarios, // El total de usuarios registrados
            'usuariosActivos' => $usuariosActivos, // Los usuarios activos
            'usuariosInactivos' => $usuariosInactivos, // Los usuarios inactivos
            'accesosRango' => $accesosRango, // El total de accesos en el rango
            'entradas' => $entradas, // Las entradas dentro del rango
            'salidas' => $salidas, // Las salidas dentro del rango
            'prestamosActivos' => $prestamosActivos, // Los préstamos sin devolver
            'prestamosVencidos' => $prestamosVencidos, // Los préstamos vencidos
            'visitantesRango' => $visitantesRango, // Los visitantes únicos en el rango
            'ultimosAccesos' => $ultimosAccesos, // Los accesos más recientes
            'prestamosPendientes' => $prestamosPendientes, // Los préstamos pendientes del rango
        ]);
    }
}

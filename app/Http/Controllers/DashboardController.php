<?php

namespace App\Http\Controllers;

use App\Models\PrestamoEquipo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $desde = ($request->date('desde') ?? now()->startOfMonth())->startOfDay();
        $hasta = $request->date('hasta') ?? now();

        if ($desde->greaterThan($hasta)) {
            [$desde, $hasta] = [$hasta->copy()->startOfDay(), $desde->copy()];
        }

        $hastaFin = $hasta->copy()->endOfDay();

        $totalUsuarios = User::count();
        $usuariosActivos = User::whereHas('estado', fn ($q) => $q->where('estado', 1))->count();
        $usuariosInactivos = $totalUsuarios - $usuariosActivos;

        $accesosRango = 0;
        $entradas = 0;

        foreach (['acceso_usu', 'acceso_estu', 'acceso_visi'] as $tabla) {
            $filas = DB::table($tabla)
                ->whereBetween('f_ingreso', [$desde, $hastaFin])
                ->get(['f_salida']);

            $accesosRango += $filas->count();
            $entradas += $filas->whereNull('f_salida')->count();
        }

        $salidas = $accesosRango - $entradas;

        $prestamosActivos = PrestamoEquipo::whereNull('f_devolucion')->count();
        $prestamosVencidos = PrestamoEquipo::whereNull('f_devolucion')
            ->whereDate('f_Prestamo', '<', today())
            ->count();

        $visitantesRango = DB::table('acceso_visi')
            ->whereBetween('f_ingreso', [$desde, $hastaFin])
            ->count(DB::raw('DISTINCT Documento_visi'));

        $accesosU = DB::table('acceso_usu')
            ->leftJoin('usuario', 'acceso_usu.Documento_acces', '=', 'usuario.Documento')
            ->whereBetween('acceso_usu.f_ingreso', [$desde, $hastaFin])
            ->select('usuario.Nom_usua as nombre', 'usuario.Documento as documento', 'acceso_usu.f_ingreso as fecha', DB::raw("'Usuario' as tipo"))
            ->get()
            ->all();

        $accesosE = DB::table('acceso_estu')
            ->leftJoin('usuario', 'acceso_estu.Documento_estu', '=', 'usuario.Documento')
            ->whereBetween('acceso_estu.f_ingreso', [$desde, $hastaFin])
            ->select('usuario.Nom_usua as nombre', 'usuario.Documento as documento', 'acceso_estu.f_ingreso as fecha', DB::raw("'Estudiante' as tipo"))
            ->get()
            ->all();

        $accesosV = DB::table('acceso_visi')
            ->leftJoin('visitantes', 'acceso_visi.Documento_visi', '=', 'visitantes.Docu_visi')
            ->whereBetween('acceso_visi.f_ingreso', [$desde, $hastaFin])
            ->select('visitantes.Nom_visi as nombre', 'acceso_visi.Documento_visi as documento', 'acceso_visi.f_ingreso as fecha', DB::raw("'Visitante' as tipo"))
            ->get()
            ->all();

        $ultimosAccesos = collect(array_merge($accesosU, $accesosE, $accesosV))
            ->filter(fn ($a) => $a->fecha !== null)
            ->sortByDesc('fecha')
            ->take(5)
            ->values();

        $prestamosPendientes = PrestamoEquipo::whereNull('f_devolucion')
            ->whereBetween('f_Prestamo', [$desde, $hastaFin])
            ->with('equipo.tipo', 'equipo.marca', 'usuario')
            ->orderByDesc('f_Prestamo')
            ->get();

        return view('admin.dashboard', [
            'desde' => $desde,
            'hasta' => $hasta,
            'totalUsuarios' => $totalUsuarios,
            'usuariosActivos' => $usuariosActivos,
            'usuariosInactivos' => $usuariosInactivos,
            'accesosRango' => $accesosRango,
            'entradas' => $entradas,
            'salidas' => $salidas,
            'prestamosActivos' => $prestamosActivos,
            'prestamosVencidos' => $prestamosVencidos,
            'visitantesRango' => $visitantesRango,
            'ultimosAccesos' => $ultimosAccesos,
            'prestamosPendientes' => $prestamosPendientes,
        ]);
    }
}

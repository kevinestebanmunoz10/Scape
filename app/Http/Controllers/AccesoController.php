<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AccesoController extends Controller
{
    public function index(Request $request)
    {
        $desde = ($request->date('desde') ?? now()->startOfMonth())->startOfDay();
        $hasta = $request->date('hasta') ?? now();

        if ($desde->greaterThan($hasta)) {
            [$desde, $hasta] = [$hasta->copy()->startOfDay(), $desde->copy()];
        }

        $hastaFin = $hasta->copy()->endOfDay();
        $tipo = $request->string('tipo', 'todos')->toString();
        $buscar = trim($request->string('buscar')->toString());

        $accesos = collect();

        if (in_array($tipo, ['todos', 'usuario'], true)) {
            $accesos = $accesos->merge($this->accesosDeUsuarios($desde, $hastaFin));
        }

        if (in_array($tipo, ['todos', 'estudiante'], true)) {
            $accesos = $accesos->merge($this->accesosDeEstudiantes($desde, $hastaFin));
        }

        if (in_array($tipo, ['todos', 'visitante'], true)) {
            $accesos = $accesos->merge($this->accesosDeVisitantes($desde, $hastaFin));
        }

        if ($buscar !== '') {
            $termino = mb_strtolower($buscar);

            $accesos = $accesos->filter(fn ($acceso) => str_contains(mb_strtolower((string) $acceso->nombre), $termino)
                || str_contains((string) $acceso->documento, $termino));
        }

        $accesos = $accesos->sortByDesc('f_ingreso')->values();

        $totalAccesos = $accesos->count();
        $entradas = $accesos->whereNull('f_salida')->count();
        $salidas = $totalAccesos - $entradas;
        $visitantes = $accesos->where('tipo', 'Visitante')->count();

        $porPagina = 10;
        $pagina = max(1, (int) $request->input('page', 1));

        $accesosPaginados = new LengthAwarePaginator(
            $accesos->forPage($pagina, $porPagina)->values(),
            $totalAccesos,
            $porPagina,
            $pagina,
            ['path' => $request->url(), 'query' => $request->query()],
        );

        return view('admin.accesos', [
            'accesos' => $accesosPaginados,
            'desde' => $desde,
            'hasta' => $hasta,
            'tipo' => $tipo,
            'buscar' => $buscar,
            'totalAccesos' => $totalAccesos,
            'entradas' => $entradas,
            'salidas' => $salidas,
            'visitantes' => $visitantes,
        ]);
    }

    /**
     * @return Collection<int, object>
     */
    private function accesosDeUsuarios($desde, $hastaFin): Collection
    {
        return DB::table('acceso_usu')
            ->leftJoin('usuario', 'acceso_usu.Documento_acces', '=', 'usuario.Documento')
            ->whereBetween('acceso_usu.f_ingreso', [$desde, $hastaFin])
            ->select(
                'usuario.Nom_usua as nombre',
                'acceso_usu.Documento_acces as documento',
                'acceso_usu.f_ingreso as f_ingreso',
                'acceso_usu.f_salida as f_salida',
                DB::raw("'Usuario' as tipo"),
            )
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    private function accesosDeEstudiantes($desde, $hastaFin): Collection
    {
        return DB::table('acceso_estu')
            ->leftJoin('usuario', 'acceso_estu.Documento_estu', '=', 'usuario.Documento')
            ->whereBetween('acceso_estu.f_ingreso', [$desde, $hastaFin])
            ->select(
                'usuario.Nom_usua as nombre',
                'acceso_estu.Documento_estu as documento',
                'acceso_estu.f_ingreso as f_ingreso',
                'acceso_estu.f_salida as f_salida',
                DB::raw("'Estudiante' as tipo"),
            )
            ->get();
    }

    /**
     * @return Collection<int, object>
     */
    private function accesosDeVisitantes($desde, $hastaFin): Collection
    {
        return DB::table('acceso_visi')
            ->leftJoin('visitantes', 'acceso_visi.Documento_visi', '=', 'visitantes.Docu_visi')
            ->whereBetween('acceso_visi.f_ingreso', [$desde, $hastaFin])
            ->select(
                'visitantes.Nom_visi as nombre',
                'acceso_visi.Documento_visi as documento',
                'acceso_visi.f_ingreso as f_ingreso',
                'acceso_visi.f_salida as f_salida',
                DB::raw("'Visitante' as tipo"),
            )
            ->get();
    }
}

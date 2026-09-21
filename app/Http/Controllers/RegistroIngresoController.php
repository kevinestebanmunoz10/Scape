<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistroIngresoController extends Controller
{
    public function create()
    {
        return view('vigilante.entrada');
    }

    public function buscar(Request $request)
    {
        $datos = $this->validar($request);

        $persona = $this->buscarPersona($datos['tipo'], $datos['documento']);

        if (! $persona) {
            return redirect()->route('vigilante.entrada')
                ->withInput()
                ->with('error', 'No se encontró ninguna persona con ese documento.');
        }

        if ($this->ingresoAbierto($datos['tipo'], $persona->documento)) {
            return redirect()->route('vigilante.entrada')
                ->withInput()
                ->with('error', $persona->nombre.' ya tiene un ingreso abierto (Dentro). Debe registrar su salida primero.');
        }

        return redirect()->route('vigilante.entrada')
            ->withInput()
            ->with('persona', [
                'tipo' => $datos['tipo'],
                'documento' => (string) $persona->documento,
                'nombre' => $persona->nombre,
            ]);
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);

        $persona = $this->buscarPersona($datos['tipo'], $datos['documento']);

        if (! $persona) {
            return redirect()->route('vigilante.entrada')
                ->with('error', 'No se encontró ninguna persona con ese documento.');
        }

        if ($this->ingresoAbierto($datos['tipo'], $persona->documento)) {
            return redirect()->route('vigilante.entrada')
                ->with('error', $persona->nombre.' ya tiene un ingreso abierto (Dentro). Debe registrar su salida primero.');
        }

        $ingreso = now();

        if ($datos['tipo'] === 'estudiante') {
            DB::table('acceso_estu')->insert([
                'f_ingreso' => $ingreso,
                'f_salida' => null,
                'Documento_estu' => $persona->documento,
                'id_autorizacion' => null,
            ]);
        } elseif ($datos['tipo'] === 'visitante') {
            DB::table('acceso_visi')->insert([
                'f_ingreso' => $ingreso,
                'f_salida' => null,
                'Documento_visi' => $persona->documento,
                'id_Estado_visi' => 1,
                'Lugar' => null,
            ]);
        } else {
            DB::table('acceso_usu')->insert([
                'f_ingreso' => $ingreso,
                'f_salida' => null,
                'Documento_acces' => $persona->documento,
                'id_Estado_usu' => 1,
            ]);
        }

        return redirect()->route('vigilante.entrada')
            ->with('status', 'Ingreso registrado para '.$persona->nombre.' a las '.$ingreso->format('h:i a').'.');
    }

    /**
     * @return array{tipo: string, documento: string}
     */
    private function validar(Request $request): array
    {
        $reglaDocumento = $request->input('tipo') === 'visitante'
            ? ['required', 'string', 'max:20']
            : ['required', 'numeric'];

        return $request->validate([
            'tipo' => ['required', 'in:usuario,estudiante,visitante'],
            'documento' => $reglaDocumento,
        ]);
    }

    private function buscarPersona(string $tipo, string $documento): ?object
    {
        if ($tipo === 'visitante') {
            $visitante = DB::table('visitantes')->where('Docu_visi', $documento)->first();

            return $visitante
                ? (object) ['nombre' => $visitante->Nom_visi, 'documento' => $visitante->Docu_visi]
                : null;
        }

        $usuario = DB::table('usuario')->where('Documento', $documento)->first();

        return $usuario
            ? (object) ['nombre' => $usuario->Nom_usua, 'documento' => $usuario->Documento]
            : null;
    }

    private function ingresoAbierto(string $tipo, string|int $documento): bool
    {
        [$tabla, $columna] = match ($tipo) {
            'estudiante' => ['acceso_estu', 'Documento_estu'],
            'visitante' => ['acceso_visi', 'Documento_visi'],
            default => ['acceso_usu', 'Documento_acces'],
        };

        return DB::table($tabla)->where($columna, $documento)->whereNull('f_salida')->exists();
    }
}

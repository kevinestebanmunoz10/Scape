<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistroSalidaController extends Controller
{
    public function create()
    {
        return view('vigilante.salida');
    }

    public function buscar(Request $request)
    {
        $datos = $this->validar($request);

        $persona = $this->buscarPersona($datos['tipo'], $datos['documento']);

        if (! $persona) {
            return redirect()->route('vigilante.salida')
                ->withInput()
                ->with('error', 'No se encontró ninguna persona con ese documento.');
        }

        $ingreso = $this->ingresoPendiente($datos['tipo'], $persona->documento);

        if (! $ingreso) {
            return redirect()->route('vigilante.salida')
                ->withInput()
                ->with('error', $persona->nombre.' no tiene un ingreso abierto (ya está Fuera).');
        }

        return redirect()->route('vigilante.salida')
            ->withInput()
            ->with('persona', [
                'tipo' => $datos['tipo'],
                'documento' => (string) $persona->documento,
                'nombre' => $persona->nombre,
                'ingreso' => $ingreso->f_ingreso,
            ]);
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request);

        $persona = $this->buscarPersona($datos['tipo'], $datos['documento']);

        if (! $persona) {
            return redirect()->route('vigilante.salida')
                ->with('error', 'No se encontró ninguna persona con ese documento.');
        }

        $ingreso = $this->ingresoPendiente($datos['tipo'], $persona->documento);

        if (! $ingreso) {
            return redirect()->route('vigilante.salida')
                ->with('error', $persona->nombre.' no tiene un ingreso abierto (ya está Fuera).');
        }

        $acceso = $this->datosDeAcceso($datos['tipo']);
        $salida = now();

        DB::table($acceso['tabla'])
            ->where($acceso['llave'], $ingreso->{$acceso['llave']})
            ->update(['f_salida' => $salida]);

        return redirect()->route('vigilante.salida')
            ->with('status', 'Salida registrada para '.$persona->nombre.' a las '.$salida->format('h:i a').'.');
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

    private function ingresoPendiente(string $tipo, string|int $documento): ?object
    {
        $acceso = $this->datosDeAcceso($tipo);

        return DB::table($acceso['tabla'])
            ->where($acceso['columna'], $documento)
            ->whereNull('f_salida')
            ->orderByDesc('f_ingreso')
            ->first();
    }

    /**
     * @return array{tabla: string, columna: string, llave: string}
     */
    private function datosDeAcceso(string $tipo): array
    {
        return match ($tipo) {
            'estudiante' => ['tabla' => 'acceso_estu', 'columna' => 'Documento_estu', 'llave' => 'id_acces_estu'],
            'visitante' => ['tabla' => 'acceso_visi', 'columna' => 'Documento_visi', 'llave' => 'id_acces_visi'],
            default => ['tabla' => 'acceso_usu', 'columna' => 'Documento_acces', 'llave' => 'id_acces_usu'],
        };
    }
}

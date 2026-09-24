<?php

// Espacio de nombres al que pertenece este controlador

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Clase base para manejar la petición HTTP
use Illuminate\Support\Facades\DB; // Fachada para ejecutar consultas a la base de datos

class RegistroIngresoController extends Controller
{
    public function create()
    {
        return view('vigilante.entrada'); // Muestra el formulario de registro de entrada del vigilante
    }

    public function buscar(Request $request)
    {
        $datos = $this->validar($request); // Valida los datos enviados en la petición

        $persona = $this->buscarPersona($datos['tipo'], $datos['documento']); // Busca a la persona según su tipo y documento

        if (! $persona) { // Verifica si no se encontró a la persona
            return redirect()->route('vigilante.entrada') // Devuelve una redirección al formulario de entrada
                ->withInput() // Conserva los datos previos enviados en el formulario
                ->with('error', 'No se encontró ninguna persona con ese documento.'); // Con un mensaje de error en la sesión
        }

        if ($this->ingresoAbierto($datos['tipo'], $persona->documento)) { // Verifica si la persona ya tiene un ingreso abierto
            return redirect()->route('vigilante.entrada') // Devuelve una redirección al formulario de entrada
                ->withInput() // Conserva los datos previos enviados en el formulario
                ->with('error', $persona->nombre.' ya tiene un ingreso abierto (Dentro). Debe registrar su salida primero.'); // Con un mensaje informando que debe salir primero
        }

        return redirect()->route('vigilante.entrada') // Devuelve una redirección al formulario de entrada
            ->withInput() // Conserva los datos previos enviados en el formulario
            ->with('persona', [ // Guarda en la sesión los datos de la persona
                'tipo' => $datos['tipo'], // El tipo de persona
                'documento' => (string) $persona->documento, // El documento como cadena de texto
                'nombre' => $persona->nombre, // El nombre de la persona
            ]);
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request); // Valida los datos enviados en la petición

        $persona = $this->buscarPersona($datos['tipo'], $datos['documento']); // Busca a la persona según su tipo y documento

        if (! $persona) { // Verifica si no se encontró a la persona
            return redirect()->route('vigilante.entrada') // Devuelve una redirección al formulario de entrada
                ->with('error', 'No se encontró ninguna persona con ese documento.'); // Con un mensaje de error en la sesión
        }

        if ($this->ingresoAbierto($datos['tipo'], $persona->documento)) { // Verifica si la persona ya tiene un ingreso abierto
            return redirect()->route('vigilante.entrada') // Devuelve una redirección al formulario de entrada
                ->with('error', $persona->nombre.' ya tiene un ingreso abierto (Dentro). Debe registrar su salida primero.'); // Con un mensaje informando que debe salir primero
        }

        $ingreso = now(); // Captura la fecha y hora actual del ingreso

        if ($datos['tipo'] === 'estudiante') { // Verifica si el tipo de persona es estudiante
            DB::table('acceso_estu')->insert([ // Inserta un registro de acceso en la tabla de estudiantes
                'f_ingreso' => $ingreso, // Fecha y hora de ingreso
                'f_salida' => null, // Sin fecha de salida
                'Documento_estu' => $persona->documento, // Documento del estudiante
                'id_autorizacion' => null, // Sin autorización asociada
            ]);
        } elseif ($datos['tipo'] === 'visitante') { // Verifica si el tipo de persona es visitante
            DB::table('acceso_visi')->insert([ // Inserta un registro de acceso en la tabla de visitantes
                'f_ingreso' => $ingreso, // Fecha y hora de ingreso
                'f_salida' => null, // Sin fecha de salida
                'Documento_visi' => $persona->documento, // Documento del visitante
                'id_Estado_visi' => 1, // Estado activo del visitante
                'Lugar' => null, // Sin lugar asignado
            ]);
        } else { // Caso por defecto: tipo usuario
            DB::table('acceso_usu')->insert([ // Inserta un registro de acceso en la tabla de usuarios
                'f_ingreso' => $ingreso, // Fecha y hora de ingreso
                'f_salida' => null, // Sin fecha de salida
                'Documento_acces' => $persona->documento, // Documento del usuario
                'id_Estado_usu' => 1, // Estado activo del usuario
            ]);
        }

        return redirect()->route('vigilante.entrada') // Devuelve una redirección al formulario de entrada
            ->with('status', 'Ingreso registrado para '.$persona->nombre.' a las '.$ingreso->format('h:i a').'.'); // Con un mensaje de éxito en la sesión
    }

    /**
     * @return array{tipo: string, documento: string}
     */
    private function validar(Request $request): array
    {
        $reglaDocumento = $request->input('tipo') === 'visitante' // Define la regla de validación del documento según el tipo de persona
            ? ['required', 'string', 'max:20'] // Para visitantes acepta texto de máximo 20 caracteres
            : ['required', 'numeric']; // Para usuarios y estudiantes exige un valor numérico

        return $request->validate([
            'tipo' => ['required', 'in:usuario,estudiante,visitante'], // El tipo debe ser usuario, estudiante o visitante
            'documento' => $reglaDocumento, // Aplica la regla de documento definida según el tipo
        ]);
    }

    private function buscarPersona(string $tipo, string $documento): ?object
    {
        if ($tipo === 'visitante') { // Verifica si el tipo de persona es visitante
            $visitante = DB::table('visitantes')->where('Docu_visi', $documento)->first(); // Busca el visitante por documento en la base de datos

            return $visitante // Retorna el resultado de la búsqueda
                ? (object) ['nombre' => $visitante->Nom_visi, 'documento' => $visitante->Docu_visi] // Si existe, devuelve su nombre y documento
                : null; // Si no existe, devuelve nulo
        }

        $usuario = DB::table('usuario')->where('Documento', $documento)->first(); // Busca el usuario por documento en la base de datos

        return $usuario // Retorna el resultado de la búsqueda
            ? (object) ['nombre' => $usuario->Nom_usua, 'documento' => $usuario->Documento] // Si existe, devuelve su nombre y documento
            : null; // Si no existe, devuelve nulo
    }

    private function ingresoAbierto(string $tipo, string|int $documento): bool
    {
        [$tabla, $columna] = match ($tipo) { // Define la tabla y columna del acceso según el tipo de persona
            'estudiante' => ['acceso_estu', 'Documento_estu'], // Configuración para estudiantes
            'visitante' => ['acceso_visi', 'Documento_visi'], // Configuración para visitantes
            default => ['acceso_usu', 'Documento_acces'], // Configuración para usuarios
        };

        return DB::table($tabla)->where($columna, $documento)->whereNull('f_salida')->exists(); // Verifica si existe un ingreso sin salida
    }
}

<?php

// Espacio de nombres al que pertenece este controlador

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Clase base para manejar la petición HTTP
use Illuminate\Support\Facades\DB; // Fachada para ejecutar consultas a la base de datos

class RegistroSalidaController extends Controller
{
    public function create()
    {
        return view('vigilante.salida'); // Muestra el formulario de registro de salida del vigilante
    }

    public function buscar(Request $request)
    {
        $datos = $this->validar($request); // Valida los datos enviados en la petición

        $persona = $this->buscarPersona($datos['tipo'], $datos['documento']); // Busca a la persona según su tipo y documento

        if (! $persona) { // Verifica si no se encontró a la persona
            return redirect()->route('vigilante.salida') // Devuelve una redirección al formulario de salida
                ->withInput() // Conserva los datos previos enviados en el formulario
                ->with('error', 'No se encontró ninguna persona con ese documento.'); // Con un mensaje de error en la sesión
        }

        $ingreso = $this->ingresoPendiente($datos['tipo'], $persona->documento); // Busca si la persona tiene un ingreso abierto

        if (! $ingreso) { // Verifica si la persona no tiene un ingreso abierto
            return redirect()->route('vigilante.salida') // Devuelve una redirección al formulario de salida
                ->withInput() // Conserva los datos previos enviados en el formulario
                ->with('error', $persona->nombre.' no tiene un ingreso abierto (ya está Fuera).'); // Con un mensaje de error informando que ya está fuera
        }

        return redirect()->route('vigilante.salida') // Devuelve una redirección al formulario de salida
            ->withInput() // Conserva los datos previos enviados en el formulario
            ->with('persona', [ // Guarda en la sesión los datos de la persona
                'tipo' => $datos['tipo'], // El tipo de persona
                'documento' => (string) $persona->documento, // El documento como cadena de texto
                'nombre' => $persona->nombre, // El nombre de la persona
                'ingreso' => $ingreso->f_ingreso, // La fecha del ingreso abierto
            ]);
    }

    public function store(Request $request)
    {
        $datos = $this->validar($request); // Valida los datos enviados en la petición

        $persona = $this->buscarPersona($datos['tipo'], $datos['documento']); // Busca a la persona según su tipo y documento

        if (! $persona) { // Verifica si no se encontró a la persona
            return redirect()->route('vigilante.salida') // Devuelve una redirección al formulario de salida
                ->with('error', 'No se encontró ninguna persona con ese documento.'); // Con un mensaje de error en la sesión
        }

        $ingreso = $this->ingresoPendiente($datos['tipo'], $persona->documento); // Busca si la persona tiene un ingreso abierto

        if (! $ingreso) { // Verifica si la persona no tiene un ingreso abierto
            return redirect()->route('vigilante.salida') // Devuelve una redirección al formulario de salida
                ->with('error', $persona->nombre.' no tiene un ingreso abierto (ya está Fuera).'); // Con un mensaje de error informando que ya está fuera
        }

        $acceso = $this->datosDeAcceso($datos['tipo']); // Obtiene la tabla, columna y llave del acceso según el tipo de persona
        $salida = now(); // Captura la fecha y hora actual de la salida

        DB::table($acceso['tabla']) // Sobre la tabla de accesos correspondiente
            ->where($acceso['llave'], $ingreso->{$acceso['llave']}) // Localiza el registro por el id del ingreso abierto
            ->update(['f_salida' => $salida]); // Registra la fecha y hora de salida

        return redirect()->route('vigilante.salida') // Devuelve una redirección al formulario de salida
            ->with('status', 'Salida registrada para '.$persona->nombre.' a las '.$salida->format('h:i a').'.'); // Con un mensaje de éxito en la sesión
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

    private function ingresoPendiente(string $tipo, string|int $documento): ?object
    {
        $acceso = $this->datosDeAcceso($tipo); // Obtiene la tabla y columnas del acceso según el tipo de persona

        return DB::table($acceso['tabla']) // Consulta la tabla de accesos correspondiente
            ->where($acceso['columna'], $documento) // Filtra por el documento de la persona
            ->whereNull('f_salida') // Filtra los registros que aún no tienen salida
            ->orderByDesc('f_ingreso') // Ordena por fecha de ingreso descendente
            ->first(); // Retorna el registro más reciente
    }

    /**
     * @return array{tabla: string, columna: string, llave: string}
     */
    private function datosDeAcceso(string $tipo): array
    {
        return match ($tipo) { // Define los datos de acceso según el tipo de persona
            'estudiante' => ['tabla' => 'acceso_estu', 'columna' => 'Documento_estu', 'llave' => 'id_acces_estu'], // Configuración para estudiantes
            'visitante' => ['tabla' => 'acceso_visi', 'columna' => 'Documento_visi', 'llave' => 'id_acces_visi'], // Configuración para visitantes
            default => ['tabla' => 'acceso_usu', 'columna' => 'Documento_acces', 'llave' => 'id_acces_usu'], // Configuración para usuarios
        };
    }
}

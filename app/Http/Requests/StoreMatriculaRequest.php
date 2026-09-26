<?php

// Declaración del espacio de nombres donde vive esta clase

namespace App\Http\Requests;

// Import de la clase base para solicitudes de formulario
use Illuminate\Foundation\Http\FormRequest;
// Import de la clase que permite construir reglas de validación dinámicas
use Illuminate\Validation\Rule;

// Solicitud de formulario para validar la creación de una matrícula
class StoreMatriculaRequest extends FormRequest
{
    // Define si el usuario está autorizado para realizar esta solicitud
    public function authorize(): bool
    {
        return true; // Permite la solicitud a cualquier usuario autenticado
    }

    // Define las reglas de validación de los campos
    public function rules(): array
    {
        return [
            'Documento_matri' => ['required', 'integer', 'exists:usuario,Documento', 'unique:matricula,Documento_matri'], // Estudiante obligatorio, existente y sin matrícula previa
            'grado' => ['required', 'string', 'max:20'], // Grado obligatorio, texto y máximo 20 caracteres
            'fecha_matri' => ['required', 'date'], // Fecha de matrícula obligatoria y con formato de fecha
            'Jornada' => ['nullable', 'string', 'max:20', Rule::exists('jornada', 'jornada')], // Jornada opcional y debe existir en el catálogo de jornadas
            'Salon' => ['required', 'string', 'max:50', Rule::exists('salones', 'codigo')], // Salón obligatorio y debe existir en el catálogo de salones
            'Nit' => ['nullable', 'integer', 'exists:sede,Nit'], // Sede opcional y existente en la tabla sede
            'acudientes_existentes' => ['nullable', 'array'], // Lista de acudientes del catálogo que se vinculan al estudiante
            'acudientes_existentes.*.documento' => ['required', 'string', 'max:20', 'distinct', 'exists:acudiente,Documento_acud'], // Documento existente, sin repetir dentro de la misma lista
            'acudientes_existentes.*.parentesco' => ['required', 'integer', 'exists:parentesco,id_parentesco'], // Parentesco obligatorio y existente en el catálogo de parentescos
            'nuevos_acudientes' => ['nullable', 'array'], // Lista de acudientes que se crean en el mismo formulario
            'nuevos_acudientes.*.documento' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/', 'distinct', 'unique:acudiente,Documento_acud'], // Documento nuevo: solo dígitos, sin repetir y todavía no registrado
            'nuevos_acudientes.*.nombre' => ['required', 'string', 'max:100'], // Nombre completo del nuevo acudiente
            'nuevos_acudientes.*.telefono' => ['required', 'string', 'max:20'], // Teléfono principal del nuevo acudiente
            'nuevos_acudientes.*.telefono_alt' => ['nullable', 'string', 'max:20'], // Teléfono alterno del nuevo acudiente
            'nuevos_acudientes.*.direccion' => ['nullable', 'string', 'max:150'], // Dirección del nuevo acudiente
            'nuevos_acudientes.*.correo' => ['required', 'string', 'email', 'max:100'], // Correo válido y obligatorio del nuevo acudiente
            'nuevos_acudientes.*.parentesco' => ['required', 'integer', 'exists:parentesco,id_parentesco'], // Parentesco obligatorio y existente en el catálogo de parentescos
        ];
    }

    // Define los mensajes de error personalizados de los campos
    public function messages(): array
    {
        return [
            'Documento_matri.unique' => 'El estudiante seleccionado ya tiene una matrícula registrada.', // Aviso cuando el estudiante ya está matriculado
            'Salon.exists' => 'El salón seleccionado no existe en el catálogo de salones.', // Aviso cuando el salón no viene del catálogo
            'Jornada.exists' => 'La jornada seleccionada no existe en el catálogo de jornadas.', // Aviso cuando la jornada no viene del catálogo
            'acudientes_existentes.*.documento.exists' => 'El acudiente seleccionado no existe en el catálogo de acudientes.', // Aviso cuando el acudiente elegido no viene del catálogo
            'acudientes_existentes.*.documento.distinct' => 'El mismo acudiente no se puede agregar dos veces.', // Aviso cuando se repite un acudiente del catálogo
            'acudientes_existentes.*.parentesco.exists' => 'El parentesco seleccionado no existe en el catálogo de parentescos.', // Aviso cuando el parentesco no viene del catálogo
            'nuevos_acudientes.*.documento.regex' => 'El documento del acudiente solo puede contener números.', // Aviso cuando el documento tiene caracteres no numéricos
            'nuevos_acudientes.*.documento.distinct' => 'El mismo documento no se puede Repetir dos veces.', // Aviso cuando se repite un documento nuevo
            'nuevos_acudientes.*.documento.unique' => 'Ya existe un acudiente registrado con ese documento.', // Aviso cuando el documento nuevo ya está en el catálogo
            'nuevos_acudientes.*.correo.email' => 'El correo del acudiente no tiene un formato válido.', // Aviso cuando el correo está mal escrito
            'nuevos_acudientes.*.parentesco.exists' => 'El parentesco seleccionado no existe en el catálogo de parentescos.', // Aviso cuando el parentesco no viene del catálogo
        ];
    }

    /**
     * Descarta las filas de acudientes que llegaron completamente en blanco, ya que
     * el formulario siempre muestra una fila inicial de ejemplo en cada bloque.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'acudientes_existentes' => $this->descartarFilasVacias('acudientes_existentes'), // Limpia el bloque de acudientes del catálogo
            'nuevos_acudientes' => $this->descartarFilasVacias('nuevos_acudientes'), // Limpia el bloque de acudientes nuevos
        ]); // Sustituye los datos originales por las versiones ya depuradas
    }

    /**
     * Devuelve únicamente las filas del bloque indicado que tienen al menos un valor escrito.
     *
     * @param  string  $clave  Nombre del campo que contiene las filas repetibles
     * @return array<int, array<string, mixed>>
     */
    private function descartarFilasVacias(string $clave): array
    {
        $filas = $this->input($clave, []); // Obtiene las filas enviadas en el bloque

        // Cuando el bloque no se envió se devuelve un arreglo vacío
        if (! is_array($filas)) {
            return [];
        }

        // Filtra las filas que tienen al menos un campo con contenido
        return array_values(array_filter($filas, function ($fila) {
            // Se ignoran las filas que no son arreglos
            if (! is_array($fila)) {
                return false;
            }

            // Conserva la fila si alguno de sus valores no está vacío
            foreach ($fila as $valor) {
                if (is_string($valor) && trim($valor) !== '') {
                    return true;
                }
            }

            return false; // La fila estaba completamente en blanco
        }));
    }
}

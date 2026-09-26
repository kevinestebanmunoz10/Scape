<?php

// Declaración del espacio de nombres donde vive esta clase

namespace App\Http\Requests;

// Import de la clase base para solicitudes de formulario
use Illuminate\Foundation\Http\FormRequest;
// Import de la clase que permite construir reglas de validación dinámicas
use Illuminate\Validation\Rule;

// Solicitud de formulario para validar la creación de una sede
class StoreSedeRequest extends FormRequest
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
            'Nit' => ['required', 'integer', 'digits:9', 'unique:sede,Nit'], // NIT obligatorio, numérico de 9 dígitos y sin sedes repetidas
            'nombre' => ['required', 'string', 'max:150'], // Nombre obligatorio y máximo 150 caracteres
            'cod_postal' => ['required', 'integer', 'exists:ciudad,cod_Postal'], // Ciudad obligatoria y existente en el catálogo de ciudades
            'id_Estado' => ['nullable', 'integer', Rule::exists('estado', 'id_estado')], // Estado opcional y debe existir en la tabla estado
        ];
    }

    // Define los mensajes de error personalizados de los campos
    public function messages(): array
    {
        return [
            'Nit.unique' => 'Ya existe una sede registrada con ese NIT.', // Aviso cuando el NIT ya está usado por otra sede
            'Nit.digits' => 'El NIT debe tener exactamente 9 dígitos.', // Aviso cuando el NIT no tiene la longitud esperada
            'cod_postal.exists' => 'La ciudad seleccionada no existe en el catálogo de ciudades.', // Aviso cuando la ciudad no viene del catálogo
        ];
    }
}

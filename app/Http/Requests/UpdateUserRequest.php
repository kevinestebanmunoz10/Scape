<?php

// Declaración del espacio de nombres donde vive esta clase

namespace App\Http\Requests;

// Import de la clase base para solicitudes de formulario
use Illuminate\Foundation\Http\FormRequest;
// Import de la regla de validación Rule
use Illuminate\Validation\Rule;

// Solicitud de formulario para validar la actualización de un usuario
class UpdateUserRequest extends FormRequest
{
    // Define si el usuario está autorizado para realizar esta solicitud
    public function authorize(): bool
    {
        return true; // Permite la solicitud a cualquier usuario autenticado
    }

    // Define las reglas de validación de los campos
    public function rules(): array
    {
        $documento = $this->route('usuario')->getKey(); // Obtiene el documento del usuario que se está editando

        return [
            'Nom_usua' => ['required', 'string', 'max:100'], // Nombre obligatorio, texto y máximo 100 caracteres
            'email' => ['required', 'email', 'max:100', Rule::unique('usuario', 'email')->ignore($documento, 'Documento')], // Correo obligatorio, válido y único, ignorando al usuario actual
            'Telefono' => ['required', 'string', 'max:20'], // Teléfono obligatorio, texto y máximo 20 caracteres
            'QR' => ['nullable', 'string', 'max:255'], // QR opcional, texto y máximo 255 caracteres
            'Contrasena' => ['nullable', 'string', 'min:6', 'max:255'], // Contraseña opcional, mínimo 6 y máximo 255 caracteres
            'id_rol' => ['required', 'integer', 'exists:rol,id_rol'], // Rol obligatorio, entero y existente en la tabla rol
            'id_Estado' => ['required', 'integer', 'exists:estado,id_estado'], // Estado obligatorio, entero y existente en la tabla estado
            'cod_postal' => ['required', 'integer', 'exists:ciudad,cod_Postal'], // Código postal obligatorio, entero y existente en la tabla ciudad
        ];
    }
}

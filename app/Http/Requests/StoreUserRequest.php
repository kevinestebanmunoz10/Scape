<?php

// Declaración del espacio de nombres donde vive esta clase

namespace App\Http\Requests;

// Import de la clase base para solicitudes de formulario
use Illuminate\Foundation\Http\FormRequest;

// Solicitud de formulario para validar la creación de un usuario
class StoreUserRequest extends FormRequest
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
            'Documento' => ['required', 'integer', 'min:1', 'unique:usuario,Documento'], // Documento obligatorio, entero, mínimo 1 y único en la tabla usuario
            'Nom_usua' => ['required', 'string', 'max:100'], // Nombre obligatorio, texto y máximo 100 caracteres
            'email' => ['required', 'email', 'max:100', 'unique:usuario,email'], // Correo obligatorio, válido y único en la tabla usuario
            'Telefono' => ['required', 'string', 'max:20'], // Teléfono obligatorio, texto y máximo 20 caracteres
            'QR' => ['nullable', 'string', 'max:255'], // QR opcional, texto y máximo 255 caracteres
            'Contrasena' => ['required', 'string', 'min:6', 'max:255'], // Contraseña obligatoria, mínimo 6 y máximo 255 caracteres
            'Contrasena_confirmation' => ['required_with:Contrasena', 'same:Contrasena'], // Confirmación obligatoria si hay contraseña y debe coincidir con ella
            'id_rol' => ['required', 'integer', 'exists:rol,id_rol'], // Rol obligatorio, entero y existente en la tabla rol
            'id_Estado' => ['required', 'integer', 'exists:estado,id_estado'], // Estado obligatorio, entero y existente en la tabla estado
            'cod_postal' => ['required', 'integer', 'exists:ciudad,cod_Postal'], // Código postal obligatorio, entero y existente en la tabla ciudad
        ];
    }
}

<?php

// Declaración del espacio de nombres donde vive esta clase

namespace App\Http\Requests;

// Import de la clase base para solicitudes de formulario
use Illuminate\Foundation\Http\FormRequest;

// Solicitud de formulario para validar la creación de un equipo
class StoreEquipoRequest extends FormRequest
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
            'serial_equi' => ['required', 'string', 'max:50', 'unique:equipo,serial_equi'], // Número de serie obligatorio, texto, máximo 50 caracteres y único en la tabla equipo
            'id_t_equip' => ['required', 'integer', 'exists:tipo_equipo,id_t_equip'], // Tipo de equipo obligatorio y existente en la tabla tipo_equipo
            'id_Marca' => ['required', 'integer', 'exists:marca,id_marca'], // Marca obligatoria y existente en la tabla marca
            'Color' => ['nullable', 'string', 'max:30'], // Color opcional, texto y máximo 30 caracteres
            'imagen' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'], // Imagen opcional, archivo de imagen con formatos permitidos y máximo 2048 KB
            'Documento' => ['nullable', 'integer', 'exists:usuario,Documento'], // Documento del usuario opcional y existente en la tabla usuario
        ];
    }
}

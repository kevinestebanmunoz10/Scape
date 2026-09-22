<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'serial_equi' => ['required', 'string', 'max:50', 'unique:equipo,serial_equi'],
            'id_t_equip' => ['required', 'integer', 'exists:tipo_equipo,id_t_equip'],
            'id_Marca' => ['required', 'integer', 'exists:marca,id_marca'],
            'Color' => ['nullable', 'string', 'max:30'],
            'imagen' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'Documento' => ['nullable', 'integer', 'exists:usuario,Documento'],
        ];
    }
}

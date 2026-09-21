<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $documento = $this->route('usuario')->getKey();

        return [
            'Nom_usua' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('usuario', 'email')->ignore($documento, 'Documento')],
            'Telefono' => ['required', 'string', 'max:20'],
            'QR' => ['nullable', 'string', 'max:255'],
            'Contrasena' => ['nullable', 'string', 'min:6', 'max:255'],
            'id_rol' => ['required', 'integer', 'exists:rol,id_rol'],
            'id_Estado' => ['required', 'integer', 'exists:estado,id_estado'],
            'cod_postal' => ['required', 'integer', 'exists:ciudad,cod_Postal'],
        ];
    }
}

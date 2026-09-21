<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'Documento' => ['required', 'integer', 'min:1', 'unique:usuario,Documento'],
            'Nom_usua' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:usuario,email'],
            'Telefono' => ['required', 'string', 'max:20'],
            'QR' => ['nullable', 'string', 'max:255'],
            'Contrasena' => ['required', 'string', 'min:6', 'max:255'],
            'id_rol' => ['required', 'integer', 'exists:rol,id_rol'],
            'id_Estado' => ['required', 'integer', 'exists:estado,id_estado'],
            'cod_postal' => ['required', 'integer', 'exists:ciudad,cod_Postal'],
        ];
    }
}

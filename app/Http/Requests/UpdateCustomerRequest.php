<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'lowercase', 'email'],
            'store_id' => ['required'],
            'rut' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El Nombre del Cliente es requerido',
            'email.unique' => 'El Correo ya existe',
            'email.lowercase' => 'El Correo debe estar en minúsculas',
            'email.required' => 'El Correo es requerido',
            'email.email' => 'El Correo debe ser válido',
            'rut.unique' => 'El Rut ya existe',
            'rut.required' => 'El Rut es requerido',
            'phone.required' => 'El Teléfono es requerido',
            'address.required' => 'La Dirección es requerida',
            'store_id.required' => 'La Tienda es requerida',
        ];
    }
}

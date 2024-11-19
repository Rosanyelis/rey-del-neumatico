<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'email' => ['lowercase', 'email', 'unique:customers,email'],
            'rut' => ['required', 'string', 'max:255', 'unique:customers,rut'],
            'phone' => ['string', 'max:255'],
            'address' => ['string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El Nombre del Cliente es requerido',
            'email.unique' => 'El Correo ya existe',
            'email.lowercase' => 'El Correo debe estar en minúsculas',
            'email.email' => 'El Correo debe ser válido',
            'rut.unique' => 'El Rut ya existe',
            'rut.required' => 'El Rut es requerido',
        ];
    }
}

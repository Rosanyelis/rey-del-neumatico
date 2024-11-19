<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreRequest extends FormRequest
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
            'name' => 'required',
            'code' => 'required',
            'logo' => 'nullable|image|max:2048',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'address2' => 'nullable',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'postal_code' => 'required',
            'currency_code' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El Nombre de la Tienda es requerido',
            'code.required' => 'El Código de la Tienda es requerido',
            'email.required' => 'El Correo Electrônico es requerido',
            'phone.required' => 'El Telémfono es requerido',
            'address.required' => 'La Dirección es requerida',
            'city.required' => 'La Ciudad es requerida',
            'state.required' => 'El Estado es requerido',
            'country.required' => 'El País es requerido',
            'postal_code.required' => 'El Código Postal es requerido',
            'currency_code.required' => 'El Código de Moneda es requerido',
        ];
    }
}

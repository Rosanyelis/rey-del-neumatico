<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
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
            'supplier' => 'required',
            'received' => 'required',
            'type_purchase' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'supplier.required' => 'El campo Proveedor es obligatorio',
            'received.required' => 'El campo ¿Recibido? es obligatorio',
            'type_purchase.required' => 'El campo Tipo de Compra es obligatorio',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExpenseRequest extends FormRequest
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
            'name' => ['required'],
            'amount' => ['required'],
            'file' => ['max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'La Referencia es requerida',
            'amount.required' => 'El Monto es requerido',
            'file.mimes' => 'El archivo debe ser una imagen, un video, un archivo PDF, un archivo de Word o de Excel',
        ];
    }
}

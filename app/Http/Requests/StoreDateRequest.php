<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDateRequest extends FormRequest
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
            'asunto' => 'required|integer|exists:services,id',
            'fecha' => 'required|date_format:d-m-Y',
            'hora' => 'required|date_format:H:i',
            'user_id' => 'required|integer|exists:users,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'asunto.required' => 'El servicio es requerido',
            'asunto.exists' => 'El servicio seleccionado no existe',
            'fecha.required' => 'La fecha es requerida',
            'fecha.date_format' => 'La fecha debe estar en formato d-m-Y',
            'hora.required' => 'La hora es requerida',
            'hora.date_format' => 'La hora debe estar en formato H:i',
            'user_id.required' => 'El usuario es requerido',
            'user_id.exists' => 'El usuario no existe',
        ];
    }
}

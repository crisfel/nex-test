<?php

namespace App\Http\Requests\Contact;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id' => 'required|numeric',
            'name' => 'string|max:255',
            'email' => 'email|max:255',
            'phone' => 'nullable|string|max:50',
            'is_primary' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'El ID del contacto es obligatorio.',
            'id.numeric' => 'El ID debe ser numérico.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'is_primary.boolean' => 'El campo primario debe ser verdadero o falso.',
        ];
    }

    public function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY));
    }
}

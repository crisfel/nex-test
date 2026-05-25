<?php

namespace App\Http\Requests\Client;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'status' => 'required|in:activo,inactivo,prospecto',
            'description' => 'nullable|string|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del cliente es obligatorio.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser activo, inactivo o prospecto.',
        ];
    }

    public function failedValidation(Validator $validator): never
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY));
    }
}

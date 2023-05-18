<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ValidateEmailRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|string|max:255|unique:users',
        ];
    }

    /**
     * Metodo para personalizar mensajes de validacion
     *
     * @return void
     */
    public function messages()
    {
        return[
            'email.email' => 'aldj'
        ];
    }
}

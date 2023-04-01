<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class setPassword extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'password' => 'required|string|min:6|max:20|regex:/^.*([a-zA-Z][0-9]).*$/',
            'confirm_password' => 'required|same:password'
        ];
    }

    /**
     * Get validation errors messages
     * 
     * @return array
     */
    public function messages(): array
    {
        return [
            'password.required' => 'Este campo é obrigatório.',
            'password.string' => 'Este campo não atende aos requisitos mínimos.',
            'password.min' => 'Este campo não atende aos requisitos mínimos.',
            'password.max' => 'Este campo não atende aos requisitos mínimos.',
            'password.regex' => 'Este campo não atende aos requisitos mínimos.',
            'confirm_password.required' => 'Este campo é obrigatório.',
            'confirm_password.same' => 'As senhas estão diferentes.',
        ];
    }
}

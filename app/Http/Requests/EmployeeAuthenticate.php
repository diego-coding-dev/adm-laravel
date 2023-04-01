<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeAuthenticate extends FormRequest
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
            'email' => 'required|string|email|max:100|min:10',
            'password' => 'required|string|min:6|max:20|regex:/^.*([a-zA-Z][0-9]).*$/',
        ];
    }

    /**
     * Get validation errors messages
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Este campo é obrigatório.',
            'email.string' => 'Este campo não atende aos requisitos mínimos.',
            'email.email' => 'Este campo não atende aos requisitos mínimos.',
            'email.max' => 'Este campo não atende aos requisitos mínimos.',
            'email.min' => 'Este campo não atende aos requisitos mínimos.',
            'password.required' => 'Este campo é obrigatório.',
            'password.string' => 'Este campo não atende aos requisitos mínimos.',
            'password.min' => 'Este campo não atende aos requisitos mínimos.',
            'password.max' => 'Este campo não atende aos requisitos mínimos.',
            'password.regex' => 'Este campo não atende aos requisitos mínimos.',
        ];
    }
}

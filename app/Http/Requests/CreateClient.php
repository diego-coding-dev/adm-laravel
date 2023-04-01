<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateClient extends FormRequest
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
            'name' => 'required|string|max:220|min:5',
            'email' => 'required|string|email|max:100|min:10'
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
            'name.required' => 'Este campo é obrigatório.',
            'name.string' => 'Este campo não atende aos requisitos mínimos.',
            'name.max' => 'Este campo não atende aos requisitos mínimos.',
            'name.min' => 'Este campo não atende aos requisitos mínimos.',
            'email.required' => 'Este campo é obrigatório.',
            'email.string' => 'Este campo não atende aos requisitos mínimos.',
            'email.email' => 'Este campo não atende aos requisitos mínimos.',
            'email.max' => 'Este campo não atende aos requisitos mínimos.',
            'email.min' => 'Este campo não atende aos requisitos mínimos.',
        ];
    }
}

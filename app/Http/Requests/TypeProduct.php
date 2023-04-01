<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TypeProduct extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array {
        return [
            'description' => 'required|string|max:100|min:3'
        ];
    }

    /**
     * Get validation errors messages
     * 
     * @return array
     */
    public function messages(): array {
        return [
            'description.required' => 'Este campo é obrigatório.',
            'description.max' => 'Este campo não atende aos requisitos mínimos.',
            'description.min' => 'Este campo não atende aos requisitos mínimos.',
            'description.string' => 'Este campo não atende aos requisitos mínimos.'
        ];
    }

}

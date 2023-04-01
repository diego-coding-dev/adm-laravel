<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdate extends FormRequest
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
            'description' => 'nullable|string|max:100|min:3',
            'type_product_id' => 'integer|digits:1'
        ];
    }

    /**
     * Get validation errors messages
     * 
     * @return array
     */
    public function messages(): array {
        return [
            'description.max' => 'Este campo não atende aos requisitos mínimos.',
            'description.min' => 'Este campo não atende aos requisitos mínimos.',
            'description.string' => 'Este campo não atende aos requisitos mínimos.',
            'type_product_id.integer' => 'Este campo não atende aos requisitos mínimos. 1',
            'type_product_id.digits' => 'Este campo não atende aos requisitos mínimos. 2'
        ];
    }

}

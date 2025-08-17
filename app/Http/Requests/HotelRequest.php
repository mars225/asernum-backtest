<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HotelRequest extends FormRequest
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
        $hotelId = $this->route('id');

        return [
            'label' => 'required|string|max:180',
            'code' => [
            'required',
            'string',
            'max:40',
            Rule::unique('hotels', 'code')->ignore($hotelId),
        ],
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'stars' => 'nullable|integer|between:1,5',
        ];
    }

    public function messages(): array
    {
        return [
            'label.required' => 'Le nom de l’hôtel est obligatoire.',
            'label.max' => 'Le nom de l’hôtel ne peut pas dépasser 180 caractères.',
            'code.required' => 'Le code de l’hôtel est obligatoire.',
            'code.unique' => 'Ce code est déjà utilisé par un autre hôtel.',
            'stars.integer' => 'Le nombre d’étoiles doit être un entier.',
            'stars.between' => 'Le nombre d’étoiles doit être compris entre 1 et 5.',
        ];
    }
}

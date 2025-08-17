<?php

namespace App\Http\Requests\RoomRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomRequest extends FormRequest
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
            'hotel_id' => 'required|exists:hotels,id',
            'room_label' => 'required|string',
            'number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('rooms')
                    ->where('hotel_id', $this->input('hotel_id')),
            ],
            'type' => 'required|in:single,double,suite',
            'price_per_night' => 'required|numeric|min:0',
            'occupants' => 'required|integer|min:1',
            'available' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'hotel_id.required' => 'L’hôtel est obligatoire.',
            'room_label.required' => 'Le nom de chambre est obligatoire.',
            'hotel_id.exists' => 'L’hôtel sélectionné est invalide.',
            'number.required' => 'Le numéro de chambre est obligatoire.',
            'number.unique' => 'Ce numéro de chambre existe déjà pour cet hôtel.',
            'type.required' => 'Le type de chambre est obligatoire.',
            'type.in' => 'Le type doit être : single, double ou suite.',
            'price_per_night.required' => 'Le prix par nuit est obligatoire.',
            'price_per_night.numeric' => 'Le prix par nuit doit être un nombre.',
            'price_per_night.min' => 'Le prix par nuit doit être au moins 0.',
            'occupants.required' => 'Le nombre d’occupants est obligatoire.',
            'occupants.integer' => 'Le nombre d’occupants doit être un entier.',
            'occupants.min' => 'Il doit y avoir au moins 1 occupant.',
            'available.required' => 'Le statut de disponibilité est obligatoire.',
            'available.boolean' => 'Le statut doit être vrai ou faux.',
        ];
    }

    public function all($keys = null)
    {
        $data = parent::all($keys);
        if ($this->route('hotelId')) {
            $data['hotel_id'] = $this->route('hotelId');
        }
        return $data;
    }
}

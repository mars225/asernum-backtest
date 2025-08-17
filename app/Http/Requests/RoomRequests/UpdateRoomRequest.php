<?php

namespace App\Http\Requests\RoomRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roomId = $this->route('id');
        return [
                // 'hotel_id' n'est pas requis ni validé
                'room_label' => 'required|string',
                'number' => [
                    'required',
                    'string',
                    'max:20',
                    Rule::unique('rooms')
                        ->ignore($roomId),
                ],
                'type' => 'required|in:single,double,suite',
                'price_per_night' => 'required|numeric|min:0',
                'occupants' => 'required|integer|min:1',
            ];
    }

    public function messages(): array
    {
        return [
            'room_label.required' => 'Le nom de chambre est obligatoire.',
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
        ];
    }
}

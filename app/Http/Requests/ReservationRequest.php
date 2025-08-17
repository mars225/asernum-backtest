<?php

namespace App\Http\Requests;

use App\Models\Reservation;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReservationRequest extends FormRequest
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
            'customer_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => ['nullable', Rule::in(['pending','confirmed','cancelled','finished'])],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Le client est obligatoire.',
            'customer_id.exists' => 'Le client sélectionné est invalide.',
            'room_id.required' => 'La chambre est obligatoire.',
            'room_id.exists' => 'La chambre sélectionnée est invalide.',
            'start_date.required' => 'La date d’arrivée est obligatoire.',
            'start_date.date' => 'La date d’arrivée doit être valide.',
            'end_date.required' => 'La date de départ est obligatoire.',
            'end_date.date' => 'La date de départ doit être valide.',
            'end_date.after' => 'La date de départ doit être après la date d’arrivée.',
            'status.in' => 'Le statut doit être : pending, confirmed, cancelled ou finished.',
        ];
    }

    // Optionnel : vérifier la disponibilité et chevauchements
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $roomId = $this->room_id;
            $start = $this->start_date;
            $end = $this->end_date;

            $overlap = Reservation::where('room_id', $roomId)
                ->where('status', '!=', 'cancelled')
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('start_date', [$start, $end])
                          ->orWhereBetween('end_date', [$start, $end])
                          ->orWhere(function($q) use ($start, $end) {
                              $q->where('start_date', '<=', $start)
                                ->where('end_date', '>=', $end);
                          });
                })
                ->exists();

            if ($overlap) {
                $validator->errors()->add('room_id', 'Cette chambre n’est pas disponible sur la période choisie.');
            }
        });
    }
}

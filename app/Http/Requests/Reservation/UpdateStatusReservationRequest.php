<?php

namespace App\Http\Requests\Reservation;

use App\Models\Reservation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateStatusReservationRequest extends FormRequest
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
            'reservation_id' => 'required|exists:reservations,id',
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled', 'finished'])],
        ];
    }

    public function messages(): array
    {
        return [
            'reservation_id.required' => 'La réservation est obligatoire.',
            'reservation_id.exists' => 'La réservation sélectionnée est invalide.',
            'status.in' => 'Le statut doit être : pending, confirmed, cancelled ou finished.',
        ];
    }


    protected function prepareForValidation()
    {

        $this->merge([
            'reservation_id' => $this->route('id'),
        ]);
    }

}

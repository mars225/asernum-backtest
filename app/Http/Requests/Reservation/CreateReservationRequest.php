<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'customer';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn($query) => $query->where('id', Auth::id())->where('role', 'customer')),
            ],
            'room_id'    => 'required|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
            'status'     => ['required', Rule::in(['pending'])],
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
            'status.in' => 'Le statut doit être : pending.',
        ];
    }

    protected function prepareForValidation()
    {
        //dd('prepareForValidation', Auth::check(), Auth::id(), $this->all());
        if (Auth::check()) {
            $this->merge([
                'customer_id' => Auth::id(),
                'status' => 'pending',
            ]);
        }
    }
}

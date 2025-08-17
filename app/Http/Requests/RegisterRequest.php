<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'username' => 'required|string|max:80|unique:users,username',
            'mobile' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed', // password_confirmation obligatoire
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L’email est obligatoire.',
            'username.required' => 'Le nom d’utilisateur est obligatoire.',
            'email.email' => 'L’email n’est pas valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'username.unique' => 'Ce nom d’utilisateur est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ];
    }
}

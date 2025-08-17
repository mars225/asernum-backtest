<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = $this->route('id'); // Pour update, ignore l'utilisateur actuel
        return [
            'name' => 'required|string|max:255',
            'email' => "required|email|max:255|unique:users,email,{$userId}",
            'username' => "required|string|max:80|unique:users,username,{$userId}",
            'mobile' => 'nullable|string|max:20',
            'role' => 'required|in:admin,customer',
            'password' => $userId ? 'nullable|string|min:6' : 'required|string|min:6',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L’email est obligatoire.',
            'email.email' => 'L’email n’est pas valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'username.unique' => 'Ce nom d’utilisateur est déjà utilisé.',
            'username.required' => 'Le d’utilisateur est obligatoire.',
            'role.required' => 'Le rôle est obligatoire.',
            'role.in' => 'Le rôle doit être admin ou customer.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
        ];
    }
}

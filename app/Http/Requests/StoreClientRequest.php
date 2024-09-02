<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'surnom' => 'required|string|max:50',
            'adresse' => 'required|string|max:255',
            'telephone' => [
                'required',
                'string',
                'size:9',
                new PhoneNumber,
                'unique:clients,telephone',
            ],
            'users.email' => [
                'required_with:users.password',
                'email',
                'unique:users,email'
            ],
            'users.password' => [
                'required_with:users.email',
                'string',
                'min:6',
                'confirmed'
            ],
            'users.nom' => 'nullable|string|max:50',
            'users.prenom' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'telephone.unique' => 'Le numéro de téléphone est déjà utilisé.',
            'users.email.unique' => 'L\'email fourni est déjà utilisé.',
            'users.password.min' => 'Le mot de passe doit comporter au moins 6 caractères.',
            'users.password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
            'users.password.required_with' => 'Le mot de passe est requis lorsque l\'email est fourni.',
            'users.email.required_with' => 'L\'email est requis lorsque le mot de passe est fourni.',
        ];
    }
}

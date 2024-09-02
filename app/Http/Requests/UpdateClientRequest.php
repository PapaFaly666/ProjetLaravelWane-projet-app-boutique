<?php

namespace App\Http\Requests;

use App\Rules\PhoneNumber;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    /**
      *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;  
    }

    /**
      *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $id = $this->route('id'); 
        return [
            'surnom' => 'required|string|max:50',
            'adresse' => 'required|string|max:255',
            'telephone' => [
                'required',
                'string',
                'size:9',  
                new PhoneNumber,  
                'unique:clients,telephone,' . $id, 
            ],
        ];
    }

    /**
      *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'telephone.regex' => 'Le numéro de téléphone doit commencer par 77, 76, 75, 70, ou 78 suivi de 7 chiffres.',
            'telephone.size' => 'Le numéro de téléphone doit comporter exactement 9 chiffres.',
            'telephone.unique' => 'Le numéro de téléphone doit être unique.',
        ];
    }
}

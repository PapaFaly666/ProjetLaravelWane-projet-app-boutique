<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    /**
     * Exécute la règle de validation.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('/^(77|76|75|70|78)[0-9]{7}$/', $value)) {
             $fail('Le numéro de téléphone doit commencer par 77, 76, 75, 70, ou 78 suivi de 7 chiffres.');
        }
    }
}

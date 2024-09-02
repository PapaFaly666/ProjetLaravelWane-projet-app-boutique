<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CustumPassword implements ValidationRule
{
    /**
      *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
         $validator = Validator::make(
            [$attribute => $value],
            [
                $attribute => [
                    Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols()
                ]
            ]
        );

         if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $fail($message);
            }
        }

         if (preg_match_all('/\d/', $value) < 3) {
            $fail('Le mot de passe doit contenir au moins trois chiffres.');
        }
    }
}

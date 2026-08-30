<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{
    public function passes($attribute, $value)
    {
        $value = (string) $value;
        if (!preg_match('/^\+?[0-9\s\-()]+$/', $value)) {
            return false;
        }

        $digits = preg_replace('/\D/', '', $value);
        return strlen($digits) >= 7 && strlen($digits) <= 15;
    }

    public function message()
    {
        return 'El teléfono debe contener entre 7 y 15 dígitos y un formato válido.';
    }
}

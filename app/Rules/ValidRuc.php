<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidRuc implements Rule
{
    public function passes($attribute, $value)
    {
        if (!preg_match('/^\d{11}$/', (string) $value)) {
            return false;
        }

        if (!in_array(substr((string) $value, 0, 2), ['10', '15', '16', '17', '20'], true)) {
            return false;
        }

        $digits = array_map('intval', str_split((string) $value));
        $weights = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
        $sum = 0;

        foreach ($weights as $index => $weight) {
            $sum += $digits[$index] * $weight;
        }

        $checkDigit = 11 - ($sum % 11);
        if ($checkDigit >= 10) {
            $checkDigit -= 10;
        }

        return $digits[10] === $checkDigit;
    }

    public function message()
    {
        return 'El RUC ingresado no es válido.';
    }
}

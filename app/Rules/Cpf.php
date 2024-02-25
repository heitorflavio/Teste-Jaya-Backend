<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Cpf implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cpf = preg_replace('/[^0-9]/', '', (string) $value);

        // Verifica se o CPF tem 11 dígitos
        if (strlen($cpf) != 11) {
            $fail("The $attribute field must contain a valid CPF (11 digits).");
            return;
        }

        // Verifica se todos os números são iguais, o que invalida o CPF
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            $fail("The CPF provided in the $attribute field is invalid (all digits are equal).");
            return;
        }

        // Calcula os dígitos verificadores
        for ($i = 9; $i < 11; $i++) {
            $sum = 0;
            for ($j = 0; $j < $i; $j++) {
                $sum += $cpf[$j] * (($i + 1) - $j);
            }
            $remainder = $sum % 11;
            $digit = $remainder < 2 ? 0 : 11 - $remainder;
            if ($cpf[$i] != $digit) {
                $fail("The CPF provided in the $attribute field is invalid (incorrect verification digits).");
                return;
            }
        }
    }
}

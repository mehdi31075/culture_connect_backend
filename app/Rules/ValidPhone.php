<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPhone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove all non-digit characters for validation
        $phoneDigits = preg_replace('/\D/', '', $value);

        // Check if it's a valid phone number (7-15 digits)
        if (strlen($phoneDigits) < 7 || strlen($phoneDigits) > 15) {
            $fail('The :attribute must be a valid phone number.');
        }
    }
}

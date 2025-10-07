<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidOtp implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if OTP is exactly 6 digits
        if (!preg_match('/^[0-9]{6}$/', $value)) {
            $fail('The :attribute must be exactly 6 digits.');
        }
    }
}

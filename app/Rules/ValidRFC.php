<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidRFC implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        if (in_array($value, ['XAXX010101000', 'XEXX010101000'])) {
            $fail(__('The VAT is not valid'));

            return;
        }

        $regex = '/^([A-ZÑ&]{3,4})([0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01]))([A-Z\d]{3})?$/i';
        if (! preg_match($regex, $value)) {
            $fail(__('The VAT is not valid'));
        }
    }
}

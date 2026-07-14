<?php

namespace App\Rules;

use Closure;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Lukeraymonddowning\Honey\Facades\Honey;

class Recaptcha implements ValidationRule
{
    protected $isRequired;

    public function __construct() {
        $this->isRequired = (config('honey.recaptcha.status') && config('honey.recaptcha.site_key') && config('honey.recaptcha.secret_key'));
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void {
        try {
            if (config('honey.recaptcha.status')) {
                if (Honey::recaptcha()->checkToken($value)->isSpam()) {
                    $fail(__('ReCAPTCHA validación fallida'));
                }
            }
        } catch (Exception $e) {
            $fail(__('Algo salió mal, ReCAPTCHA validación fallida'));
        }
    }
}

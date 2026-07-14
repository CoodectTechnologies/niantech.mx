<?php

namespace App\Traits;

use Lukeraymonddowning\Honey\Traits\WithRecaptcha;
use Exception;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

trait LivewireRecaptcha
{
    use WithRecaptcha;

    public function validateRecaptcha() {
        if (config('honey.recaptcha.status')) {
            $badRequest = false;
            try {
                if (! $this->recaptchaPasses()) {
                    $badRequest = true;
                }
            } catch (Exception $e) {
                $badRequest = true;
            }
            if ($badRequest) {
                Session::flash('alert-comment', '¡Ups! creemos que eres un robot.');
                Session::flash('alert-comment-type', 'warning');
                throw ValidationException::withMessages([
                    'honey_recaptcha_token' => __('ReCAPTCHA validación fallida'),
                ]);
            }
        }
    }
}

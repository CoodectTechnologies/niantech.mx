<?php

namespace App\Notifications\Auth;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class VerifyEmail extends BaseVerifyEmail
{
    protected function buildMailMessage($url) {
        return (new MailMessage)
            ->subject(Lang::get('Verificar dirección de correo electrónico'))
            ->markdown('emails.auth.verify-email', [
                'url' => $url,
            ]);
    }
}

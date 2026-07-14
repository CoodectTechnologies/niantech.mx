<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Route;

class UserCreate extends Notification implements ShouldQueue
{
    use Queueable;

    private $password;
    private $url;

    public function __construct($password = null) {
        $this->password = $password;
        if (Route::has('ecommerce.home.index')) {
            $this->url = route('ecommerce.account.profile.index');
        } else {
            $this->url = route('web.home.index');
        }
    }
    public function via($notifiable) {
        return ['database', 'mail'];
    }
    public function toMail($notifiable) {
        return (new MailMessage)
            ->subject(__('Welcome to').' '.config('app.name'))
            ->markdown('emails.user.create', [
                'user' => $notifiable,
                'password' => $this->password,
                'url' => $this->url,
            ]);
    }
    public function toArray($notifiable) {
        return [
            'url' => $this->url,
            'icon' => 'fa fa-user',
            'type' => 'success',
            'title' => __('Welcome to').' '.config('app.name'),
            'body' => __('Your email address is :email Your password can be found in the email sent to you.', ['email' => $notifiable->email]),
        ];
    }
}

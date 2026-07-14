<?php

namespace App\Notifications\Newsletter;

use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewsletterCreate extends Notification implements ShouldQueue
{
    use Queueable;

    private $newsletter;

    public function __construct(Newsletter $newsletter) {
        $this->newsletter = $newsletter;
    }
    public function via($notifiable) {
        return ['database'];
    }
    public function toArray($notifiable) {
        return [
            'url' => route('admin.newsletter.index', ['search' => $this->newsletter->email]),
            'icon' => 'fa fa-users',
            'type' => 'success',
            'title' => 'Nuevo subscriptor',
            'body' => $this->newsletter->email.'  se ha suscrito',
        ];
    }
}

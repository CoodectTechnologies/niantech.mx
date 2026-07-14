<?php

namespace App\Notifications\Contact;

use App\Models\EmailWeb;
use App\Models\NotificationPreference;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactCreate extends Notification implements ShouldQueue
{
    use Queueable;

    private $emailWeb;

    public function __construct(EmailWeb $emailWeb) {
        $this->emailWeb = $emailWeb;
    }
    public function via($notifiable) {
        $notificationPreference = NotificationPreference::where('user_id', $notifiable->id)->first();
        $pushNotifications = $notificationPreference->push_notifications ?? true;
        $emailNotifications = $notificationPreference->email_notifications ?? true;
        $channels = ['database'];
        if ($pushNotifications) {
            $channels[] = 'broadcast';
        }
        if ($emailNotifications) {
            $channels[] = 'mail';
        }

        return $channels;
    }
    public function toMail($notifiable) {
        return (new MailMessage)
            ->subject(__('New message website'))
            ->markdown('emails.contact.create', [
                'emailWeb' => $this->emailWeb,
            ]);
    }
    public function toArray($notifiable) {
        return [
            'url' => route('admin.email-web.index'),
            'icon' => 'fa fa-user',
            'type' => 'success',
            'title' => __('New message website'),
            'body' => __('You have a new message from').': '.$this->emailWeb->name,
        ];
    }
    public function toBroadcast($notifiable) {
        return new BroadcastMessage([
            'url' => route('admin.email-web.index'),
            'icon' => 'fas fa-comments',
            'type' => 'success',
            'title' => __('New message website'),
            'body' => __('You have a new message from').': '.$this->emailWeb->name,
        ]);
    }
}

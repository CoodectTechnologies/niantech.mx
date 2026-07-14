<?php

namespace App\Notifications\Subscription;

use App\Models\NotificationPreference;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionCreate extends Notification implements ShouldQueue
{
    use Queueable;

    private $subscription;

    public function __construct(Subscription $subscription) {
        $this->subscription = $subscription;
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
            ->subject(__('Subscripción creada'))
            ->markdown('emails.subscription.create', [
                'subscription' => $this->subscription,
            ]);
    }
    public function toArray($notifiable) {
        return [
            'url' => route('admin.subscription.billing.index'),
            'icon' => 'fa fa-wallet',
            'type' => 'success',
            'title' => __('Subscripción creada'),
            'body' => __('Se ha generado con éxito la suscripción al plan: '.$this->subscription->plan->title),
        ];
    }
    public function toBroadcast($notifiable) {
        return new BroadcastMessage([
            'url' => route('admin.subscription.billing.index'),
            'icon' => 'fa fa-wallet',
            'type' => 'success',
            'title' => __('Subscripción creada'),
            'body' => __('Se ha generado con éxito la suscripción al plan: '.$this->subscription->plan->title),
        ]);
    }
}

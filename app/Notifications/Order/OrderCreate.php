<?php

namespace App\Notifications\Order;

use App\Models\NotificationPreference;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreate extends Notification implements ShouldQueue
{
    use Queueable;

    private $order;

    public function __construct(Order $order) {
        $this->order = $order;
        $this->order->load('products');
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
            ->subject('Notificación de Pedido - '.$this->order->number)
            ->markdown('emails.order.create-admin', [
                'order' => $this->order,
                'user' => $notifiable,
            ]);
    }
    public function toArray($notifiable) {
        return [
            'url' => route('admin.order.show', $this->order),
            'icon' => 'fas fa-shopping-bag',
            'type' => 'success',
            'title' => __('New order').' '.$this->order->number,
            'body' => __('Payment status').' '.$this->order->paymentStatusToString(),
        ];
    }
    public function toBroadcast($notifiable) {
        return new BroadcastMessage([
            'url' => route('admin.order.show', $this->order),
            'icon' => 'fas fa-shopping-bag',
            'type' => 'success',
            'title' => __('New order').' '.$this->order->number,
            'body' => __('Payment status').' '.$this->order->paymentStatusToString(),
        ]);
    }
}

<?php

namespace App\Notifications\Comment;

use App\Models\BlogPost;
use App\Models\NotificationPreference;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentCreate extends Notification implements ShouldQueue
{
    use Queueable;

    private $model;
    private $comment;
    private $url;
    private $title;

    public function __construct($model, $comment) {
        $this->model = $model;
        $this->comment = $comment;
        if ($this->model instanceof Product) {
            $this->url = route('admin.catalog.product.show', ['product' => $this->model, 'submodule' => 'comments']);
            $this->title = 'Nuevo comentario de producto';
        } elseif ($this->model instanceof BlogPost) {
            $this->url = route('admin.blog.post.show', $this->model).'#comments';
            $this->title = 'Nuevo comentario de blog';
        }
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
            ->subject($this->title ?? '')
            ->markdown('emails.comment.create', [
                'model' => $this->model,
                'comment' => $this->comment,
                'title' => $this->title ?? '',
                'url' => $this->url ?? '',
            ]);
    }
    public function toArray($notifiable) {
        return [
            'url' => $this->url ?? '',
            'icon' => 'fas fa-comments',
            'type' => 'success',
            'title' => $this->title ?? '',
            'body' => $this->comment->body,
        ];
    }
    public function toBroadcast($notifiable) {
        return new BroadcastMessage([
            'url' => $this->url ?? '',
            'icon' => 'fas fa-comments',
            'type' => 'success',
            'title' => $this->title ?? '',
            'body' => $this->comment->body,
        ]);
    }
}

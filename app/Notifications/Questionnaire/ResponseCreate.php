<?php

namespace App\Notifications\Questionnaire;

use App\Models\QuestionnaireResponse;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResponseCreate extends Notification implements ShouldQueue
{
    use Queueable;

    public $response;

    /**
     * Create a new notification instance.
     */
    public function __construct(QuestionnaireResponse $response) {
        $this->response = $response;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage {
        $statusBadge = $this->response->is_apt ? '✅ APTO' : '❌ NO APTO';

        return (new MailMessage)
            ->subject('Nueva Respuesta de Cuestionario - '.$this->response->questionnaire->name)
            ->greeting('¡Hola!')
            ->line('Se ha recibido una nueva respuesta para el cuestionario:')
            ->line('**'.$this->response->questionnaire->name.'**')
            ->line('')
            ->line('**Información del Usuario:**')
            ->line('Nombre: '.$this->response->name)
            ->line('Email: '.$this->response->email)
            ->line('Teléfono: '.($this->response->phone ?? 'No proporcionado'))
            ->line('')
            ->line('**Resultado:**')
            ->line('Estado: '.$statusBadge)
            ->line('Porcentaje de Aptitud: '.number_format($this->response->positive_percentage, 1).'%')
            ->action('Ver Detalles Completos', route('admin.questionnaire.show', $this->response->questionnaire));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array {
        return [
            'response_id' => $this->response->id,
            'questionnaire_id' => $this->response->questionnaire_id,
            'questionnaire_name' => $this->response->questionnaire->name,
            'user_name' => $this->response->name,
            'user_email' => $this->response->email,
            'user_phone' => $this->response->phone,
            'positive_percentage' => $this->response->positive_percentage,
            'is_apt' => $this->response->is_apt,
            'message' => $this->response->name.' ha completado el cuestionario "'.$this->response->questionnaire->name.'"',
            'url' => route('admin.questionnaire.show', $this->response->questionnaire),
        ];
    }
}

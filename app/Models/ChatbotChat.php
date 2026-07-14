<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Prism\Prism\ValueObjects\Messages\AssistantMessage;
use Prism\Prism\ValueObjects\Messages\UserMessage;

class ChatbotChat extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function chatbot() {
        return $this->belongsTo(Chatbot::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function chatbotChatMessages() {
        return $this->hasMany(ChatbotChatMessage::class);
    }
    public function getPrimsMessagesHistory() {
        return $this->chatbotChatMessages()->orderBy('created_at', 'asc')->get()->map(function ($msg) {
            return match ($msg->role) {
                'user' => new UserMessage($msg->content),
                'assistant' => new AssistantMessage($msg->content),
            };
        })->toArray();
    }
}

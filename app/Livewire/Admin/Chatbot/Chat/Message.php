<?php

namespace App\Livewire\Admin\Chatbot\Chat;

use Exception;
use Livewire\Component;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Streaming\Events\TextDeltaEvent;
use Throwable;

class Message extends Component
{
    public $chatbot;
    public $chat;
    public $user;
    public $message;

    public function mount($chatbot, $chat, $user, $message) {
        $this->chatbot = $chatbot;
        $this->chat = $chat;
        $this->user = $user;
        $this->message = $message;
        if ($this->message['stream'] && $this->message['role'] === 'assistant') {
            $this->js('$wire.generateContent()');
        }
    }
    public function render() {
        return view('livewire.admin.chatbot.chat.message');
    }
    public function generateContent() {
        try {
            if (true) {
                $stream = Prism::text()
                    ->using(Provider::OpenAI, $this->chatbot->model)
                    ->withSystemPrompt($this->chatbot->getSystemPrompt())
                    ->withMessages($this->chat->getPrimsMessagesHistory())
                    ->asStream();

                $fullResponse = '';

                foreach ($stream as $event) {
                    if ($event instanceof TextDeltaEvent) {
                        $this->stream(
                            el: 'stream.'.$this->getId(),
                            content: $event->delta,
                            replace: false,
                        );

                        $fullResponse .= $event->delta;
                        ob_flush();
                        flush();
                        usleep(50000);
                    }
                }
            } else {
                $prompt = $this->message['prompt'];
                $response = "Respuesta para $prompt";
                $fullResponse = '';
                foreach (str_split($response) as $char) {
                    $fullResponse .= $char;
                    $this->stream(
                        el: 'stream.'.$this->getId(),
                        content: $char,
                        replace: false
                    );
                    ob_flush();
                    flush();
                    usleep(50000);
                }
            }
            $this->message['content'] = $fullResponse;
            $this->message['stream'] = false;
            $this->update($this->message);
            $this->dispatch('message-success');
        } catch (Exception $e) {
            $this->dispatch('rollback-message');
            $this->dispatch('alert', 'warning', 'Ocurrió un error: '.$e->getMessage());
        } catch (Throwable $t) {
            $this->dispatch('rollback-message');
            $this->dispatch('alert', 'warning', 'Ocurrió un error: '.$t->getMessage());
        }
    }
    private function update($message) {
        return $this->chat->chatbotChatMessages()->where('id', $message['id'])->update([
            'content' => $message['content'],
        ]);
    }
}

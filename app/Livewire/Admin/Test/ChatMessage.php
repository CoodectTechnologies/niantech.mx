<?php

namespace App\Livewire\Admin\Test;

use Exception;
use Livewire\Component;
use Nette\Utils\Random;
use Throwable;

class ChatMessage extends Component
{
    public $message;

    public function mount() {
        if ($this->message['stream'] && $this->message['role'] === 'assistant') {
            $this->js('$wire.generateContent()');
        }
    }
    public function render() {
        return view('livewire.admin.test.chat-message');
    }
    public function generateContent() {
        try {
            // Lanzar excepción para simular error
            // throw new Exception('Simulated error for testing rollback.');

            $prompt = $this->message['prompt'];
            $response = "Respuesta para '$prompt': ".Random::generate(50, '0-9A-Z');

            $fullResponse = '';
            foreach (str_split($response) as $char) {
                $fullResponse .= $char;
                $this->stream(
                    to: 'stream.'.$this->getId(),
                    content: $char,
                    replace: false
                );
                usleep(10000);
            }
            $this->message['content'] = $fullResponse;
            $this->message['stream'] = false;
        } catch (Exception $e) {
            $this->dispatch('rollback-message');
            $this->dispatch('alert', 'warning', 'Exception: Ocurrió un error: '.$e->getMessage());
        } catch (Throwable $t) {
            $this->dispatch('rollback-message');
            $this->dispatch('alert', 'warning', 'Throwable: Ocurrió un error: '.$t->getMessage());
        }
    }
}

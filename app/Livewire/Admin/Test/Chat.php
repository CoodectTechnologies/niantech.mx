<?php

namespace App\Livewire\Admin\Test;

use Exception;
use Illuminate\Support\Str;
use Livewire\Component;

class Chat extends Component
{
    protected $listeners = ['rollback-message' => 'rollbackMessage'];
    public $ask;
    public $messages = [];

    public function rules() {
        return [
            'ask' => 'required|string',
        ];
    }
    public function mount() {
        $this->messages = [
            ['id' => (string) Str::uuid(), 'role' => 'assistant', 'content' => 'Hola, ¿en qué puedo ayudarte?', 'stream' => false],
            ['id' => (string) Str::uuid(), 'role' => 'user', 'content' => '¿Cuál es el horario de atención?', 'stream' => false],
            ['id' => (string) Str::uuid(), 'role' => 'assistant', 'content' => 'Nuestro horario de atención es de lunes a viernes, de 9:00 a 18:00 horas.', 'stream' => false],
        ];
    }
    public function render() {
        return view('livewire.admin.test.chat');
    }
    public function getAsk() {
        $this->validate();
        try {
            $this->generateMessageUser();
            $this->generateMessageAssistant();
            $this->ask = '';
        } catch (Exception $e) {
            $this->dispatch('alert', 'warning', 'Ocurrió un error: '.$e->getMessage());
        }
    }
    private function generateMessageUser() {
        $this->messages[] = [
            'id' => (string) Str::uuid(),
            'role' => 'user',
            'stream' => false,
            'content' => $this->ask,
        ];
    }
    private function generateMessageAssistant() {
        $this->messages[] = [
            'id' => (string) Str::uuid(),
            'role' => 'assistant',
            'content' => '',
            'stream' => true,
            'prompt' => $this->ask,
        ];
    }
    public function rollbackMessage() {
        $userMessageIndex = count($this->messages) - 2;
        $this->ask = $this->messages[$userMessageIndex]['content'];
        array_pop($this->messages);
        array_pop($this->messages);
    }
}

<?php

namespace App\Livewire\Web\Chatbot;

use App\Models\Chatbot;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $listeners = [
        'render' => 'render',
        'rollback-message' => 'rollbackMessage',
    ];
    public $chatbot;
    public $chat;
    public $user;
    public $ask = null;
    public $messages = [];
    public $hasMore = true;
    public $historyPage = 1;
    public $historyPerPage = 10;

    public function rules() {
        return [
            'ask' => 'required|string',
        ];
    }
    public function messages() {
        return [
            'ask.required' => __('The message field is required.'),
            'ask.string' => __('The message must be a string.'),
        ];
    }
    public function mount() {
        $this->user = Auth::check() ? User::find(Auth::id()) : null;
        $this->chatbot = Chatbot::where('status', 1)->first();
        if ($this->chatbot) {
            // Identificador único para usuario o sesión
            $identifier = $this->user->id ?? session()->getId();
            // Buscar chat existente o crear uno nuevo
            $this->chat = $this->chatbot->chatbotChats()->where('user_identifier', $identifier)->first();
            if (! $this->chat) {
                $this->chat = $this->chatbot->chatbotChats()->create([
                    'user_id' => $this->user->id ?? null,
                    'user_identifier' => $identifier,
                    'name' => 'new chat',
                ]);
            }
            $this->loadHistory();
        }
    }
    public function render() {
        return view('livewire.web.chatbot.index');
    }
    public function placeholder(array $params = []) {
        return view('admin.components.skeletons.general', $params);
    }
    public function getAsk() {
        $this->validate();
        try {
            DB::beginTransaction();
            $this->generateMessageUser();
            $this->generateMessageAssistant();
            $this->ask = '';
            $this->dispatch('scroll-to-bottom');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', 'error', __('An error occurred while sending the message: ').$e->getMessage());
        }
    }
    private function generateMessageUser() {
        $message = $this->chat->chatbotChatMessages()->create([
            'user_id' => $this->user->id,
            'role' => 'user',
            'created_at' => now(),
            'content' => $this->ask,
        ]);
        $this->messages[] = [
            'id' => $message->id,
            'role' => $message->role,
            'content' => $message->content,
            'date' => $message->created_at->toISOString(),
            'stream' => false,
        ];
    }
    private function generateMessageAssistant() {
        $message = $this->chat->chatbotChatMessages()->create([
            'user_id' => $this->user->id,
            'role' => 'assistant',
            'created_at' => now()->addSecond(1),
            'content' => '',
        ]);
        $this->messages[] = [
            'id' => $message->id,
            'role' => $message->role,
            'content' => $message->content,
            'date' => $message->created_at->toISOString(),
            'stream' => true,
            'prompt' => $this->ask,
        ];
    }
    public function rollbackMessage() {
        if (empty($this->messages)) {
            return;
        }
        $assistantMessage = array_pop($this->messages);
        $userMessage = array_pop($this->messages);
        $this->ask = $userMessage['content'];
        $this->chat->chatbotChatMessages()->whereIn('id', [$assistantMessage['id'], $userMessage['id']])->delete();
    }
    public function loadHistory(bool $prepend = false): void {
        if (! ($this->chat->exists ?? false) && $this->hasMore) {
            return;
        }

        $skip = ($this->historyPage - 1) * $this->historyPerPage;

        $query = $this->chat->chatbotChatMessages()
            ->orderBy('created_at', 'desc')
            ->skip($skip)
            ->take($this->historyPerPage)
            ->get();

        if ($query->isEmpty()) {
            $this->hasMore = false;

            return;
        }

        $messages = $query
            ->reverse()
            ->map(fn ($message) => $this->mapMessage($message))
            ->toArray();

        $this->messages = $prepend
            ? array_merge($messages, $this->messages)
            : $messages;

        $this->hasMore = $query->count() === $this->historyPerPage;

        if ($prepend) {
            $this->dispatch('history-loaded');
        }
    }
    public function loadMore(): void {
        if (! ($this->chat->exists ?? false) && $this->hasMore) {
            return;
        }

        $this->historyPage++;
        $this->loadHistory(prepend: true);
    }
    public function clearHistory(): void {
        if (! ($this->chat->exists ?? false)) {
            return;
        }

        $this->chat->chatbotChatMessages()->delete();
        $this->reset('ask', 'messages', 'historyPage', 'hasMore');
        $this->dispatch('history-loaded');
    }
    protected function mapMessage($message): array {
        return [
            'id' => $message->id,
            'role' => $message->role,
            'content' => $message->content,
            'date' => $message->created_at->toISOString(),
            'stream' => false,
        ];
    }
}

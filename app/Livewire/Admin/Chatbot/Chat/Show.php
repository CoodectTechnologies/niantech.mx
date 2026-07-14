<?php

namespace App\Livewire\Admin\Chatbot\Chat;

use App\Models\Chatbot;
use App\Models\ChatbotChat;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Show extends Component
{
    public $listeners = [
        'render' => 'render',
        'select-chat' => 'selectChat',
        'rollback-message' => 'rollbackMessage',
    ];
    public Chatbot $chatbot;
    public ChatbotChat $chat;
    public User $user;
    public $chatId;
    public $chatOwner = null;
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
    public function mount($chatbot) {
        $this->chatbot = $chatbot;
        $this->chatbot->load('chatbotKnowledgeSources');
        $this->user = User::find(Auth::id());
        $this->chatOwner = $this->chatbot->chatbotChats()->where('user_id', $this->chatbot->user_id)->first();
        $this->loadHistory();
    }
    public function render() {
        return view('livewire.admin.chatbot.chat.show');
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
    public function selectChat($chatId) {
        $this->chatId = $chatId;
        $this->chat = $this->chatbot->chatbotChats()->find($chatId);
        $this->reset('ask', 'messages', 'historyPage', 'hasMore');
        $this->loadHistory();
        $this->dispatch('selected-chat');
    }
    public function createChatOwn() {
        $chat = $this->chatbot->chatbotChats()->where('user_id', $this->user->id)->first();
        if (! $chat) {
            $chat = $this->chatbot->chatbotChats()->create([
                'user_id' => $this->user->id,
                'name' => __('New Chat'),
            ]);
        }
        $this->selectChat($chat->id);
        $this->dispatch('render')->to('admin.chatbot.chat.index');
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
    public function deleteChat(): void {
        if (! ($this->chat->exists ?? false)) {
            return;
        }

        $this->chat->delete();
        $this->chatId = null;
        $this->chat = new ChatbotChat;
        $this->messages = [];
        $this->historyPage = 1;
        $this->hasMore = false;
        $this->dispatch('render')->to('admin.chatbot.chat.index');
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

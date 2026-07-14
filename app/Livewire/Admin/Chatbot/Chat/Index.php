<?php

namespace App\Livewire\Admin\Chatbot\Chat;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $listeners = ['render'];
    public $chatbot;
    public $user;
    public $search;

    public function mount($chatbot) {
        $this->chatbot = $chatbot;
        $this->user = User::find(Auth::id());
    }
    public function render() {
        $chats = $this->chatbot->chatbotChats();
        if ($this->search) {
            $chats = $chats->whereHas('user', function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }
        $chats = $chats->paginate(10);

        return view('livewire.admin.chatbot.chat.index', compact('chats'));
    }
    public function placeholder(array $params = []) {
        return view('admin.components.skeletons.general', $params);
    }
    public function selectChat($chatId) {
        $this->dispatch('select-chat', chatId: $chatId);
    }
}

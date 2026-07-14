<?php

namespace App\Livewire\Admin\Chatbot\Chatbot;

use App\Models\Chatbot;
use App\Models\User;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $user;
    public $search;
    protected $queryString = ['search'];

    public function mount() {
        $this->user = User::find(auth()->id());
    }
    public function render() {
        $chatbots = Chatbot::orderBy('created_at', 'desc');
        if ($this->search) {
            $chatbots = $chatbots->where('name', 'LIKE', "%{$this->search}%");
        }
        $chatbots = $chatbots->paginate();

        return view('livewire.admin.chatbot.chatbot.index', compact('chatbots'));
    }
    public function destroy(Chatbot $chatbot) {
        try {
            $chatbot->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

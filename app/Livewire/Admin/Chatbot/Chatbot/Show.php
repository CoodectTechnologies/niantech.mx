<?php

namespace App\Livewire\Admin\Chatbot\Chatbot;

use App\Models\Chatbot;
use Illuminate\Http\Request;
use Livewire\Component;

class Show extends Component
{
    public $chatbot;
    public $submodule;

    public function mount(Chatbot $chatbot, Request $request) {
        $this->chatbot = $chatbot;
        $this->submodule = $request->submodule;
    }
    public function render() {
        return view('livewire.admin.chatbot.chatbot.show');
    }
}

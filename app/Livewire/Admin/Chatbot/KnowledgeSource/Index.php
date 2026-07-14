<?php

namespace App\Livewire\Admin\Chatbot\KnowledgeSource;

use App\Jobs\Admin\Chatbot\KnowledgeSource\ProcessKnowledgeSource;
use App\Models\Chatbot;
use App\Models\ChatbotKnowledgeSource;
use Exception;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    public $listeners = ['render'];
    public $chatbot;

    public function mount(Chatbot $chatbot) {
        $this->chatbot = $chatbot;
        $this->chatbot->load('chatbotKnowledgeSources');
    }
    public function render() {
        $knowledgeSources = $this->chatbot->chatbotKnowledgeSources()->get();

        return view('livewire.admin.chatbot.knowledge-source.index', compact('knowledgeSources'));
    }
    public function placeholder(array $params = []) {
        return view('admin.components.skeletons.general', $params);
    }
    public function destroy(ChatbotKnowledgeSource $knowledgeSource) {
        try {
            $knowledgeSource->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
    public function regenerate(ChatbotKnowledgeSource $knowledgeSource) {
        try {
            ProcessKnowledgeSource::dispatch($knowledgeSource);
            $this->dispatch('alert', 'success', __('Regeneration started'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

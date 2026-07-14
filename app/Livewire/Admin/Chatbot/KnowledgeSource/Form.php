<?php

namespace App\Livewire\Admin\Chatbot\KnowledgeSource;

use App\Jobs\Admin\Chatbot\KnowledgeSource\ProcessKnowledgeSource;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $chatbot;
    public $newSource = ['name' => '', 'type' => 'file', 'file' => null, 'path' => ''];

    public function rules() {
        return [
            'newSource.name' => 'required|string|max:255',
            'newSource.type' => 'required|in:file,url',
            'newSource.file' => $this->newSource['type'] === 'file' ? 'required|file|mimes:pdf|max:10240' : 'nullable',
            'newSource.path' => $this->newSource['type'] === 'url' ? 'required|url' : 'nullable',
        ];
    }
    public function messages() {
        return [
            'newSource.name.required' => 'El nombre es requerido',
            'newSource.type.required' => 'El tipo es requerido',
            'newSource.type.in' => 'El tipo seleccionado no es válido',
            'newSource.file.required' => 'El archivo es requerido',
            'newSource.file.file' => 'El archivo debe ser un archivo válido',
            'newSource.file.mimes' => 'El archivo debe ser un archivo de tipo: pdf, txt, doc, docx',
            'newSource.file.max' => 'El archivo no debe ser mayor a 10MB',
            'newSource.path.required' => 'La URL es requerida',
            'newSource.path.url' => 'La URL debe ser una URL válida',
        ];
    }
    public function mount($chatbot) {
        $this->chatbot = $chatbot;
    }
    public function render() {
        return view('livewire.admin.chatbot.knowledge-source.form');
    }
    public function placeholder(array $params = []) {
        return view('admin.components.skeletons.general', $params);
    }
    public function save() {
        $this->validate();
        $knowledgeSourceTemp = [
            'name' => $this->newSource['name'],
            'type' => $this->newSource['type'],
            'path' => match ($this->newSource['type']) {
                'file' => $this->newSource['file'] ? $this->newSource['file']->store('chatbot-knowledge') : null,
                'url' => $this->newSource['path'],
            },
        ];
        try {
            $knowledgeSource = $this->chatbot->chatbotKnowledgeSources()->create($knowledgeSourceTemp);
            ProcessKnowledgeSource::dispatch($knowledgeSource);
            $this->reset('newSource');
            $this->dispatch('alert', 'success', __('Registration successfully saved'));
            $this->dispatch('render');
        } catch (Exception $e) {
            if ($this->newSource['type'] == 'file' && $knowledgeSourceTemp['path']) {
                Storage::delete($knowledgeSourceTemp['path']);
            }
            $this->dispatch('alert', 'error', __('An error occurred while saving the source: ').$e->getMessage());
        }
    }
}

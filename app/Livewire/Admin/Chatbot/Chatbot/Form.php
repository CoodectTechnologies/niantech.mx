<?php

namespace App\Livewire\Admin\Chatbot\Chatbot;

use App\Enums\OpenAI;
use App\Models\Chatbot;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $chatbot;
    public $imageTmp;

    protected function rules() {
        return [
            'chatbot.name' => 'required|string|max:255',
            'chatbot.model' => ['required', 'string', Rule::enum(OpenAI::class)],
            'chatbot.temperature' => 'required|numeric|min:0|max:2',
            'chatbot.system_promt' => 'required|string',
            'chatbot.status' => 'required|boolean',
        ];
    }
    protected function messages() {
        return [
            'chatbot.name.required' => 'El nombre es requerido',
            'chatbot.model.required' => 'El modelo es requerido',
            'chatbot.temperature.required' => 'La temperatura es requerida',
            'chatbot.temperature.numeric' => 'La temperatura debe ser un número',
            'chatbot.temperature.min' => 'La temperatura debe ser mayor o igual a 0',
            'chatbot.temperature.max' => 'La temperatura debe ser menor o igual a 2',
            'chatbot.system_promt.required' => 'El system prompt es requerido',
            'chatbot.status.required' => 'El estado es requerido',
            'chatbot.status.boolean' => 'El estado debe ser verdadero o falso',
        ];
    }
    public function mount(Chatbot $chatbot) {
        $this->chatbot = $chatbot;
        $this->chatbot->load('chatbotKnowledgeSources');
        $this->chatbot->temperature = $chatbot->exists ? $chatbot->temperature : 0.5;
        $this->chatbot->status = $chatbot->exists ? $chatbot->status : false;
    }
    public function render() {
        $models = OpenAI::cases();

        return view('livewire.admin.chatbot.chatbot.form', compact('models'));
    }
    public function save() {
        $this->validate();
        $this->chatbot->user_id = auth()->id();
        $this->saveImage();
        $this->chatbot->save();
        if ($this->chatbot->status) {
            Chatbot::where('id', '!=', $this->chatbot->id)->update(['status' => false]);
        }
        Session::flash('alert', __('Registration successfully saved'));
        Session::flash('alert-type', 'success');

        return Redirect::route('admin.chatbot.show', $this->chatbot);
    }
    public function saveImage() {
        if ($this->imageTmp) {
            $url = $this->imageTmp->store('chatbot/');
            if ($this->chatbot->image && Storage::exists($this->chatbot->image)) {
                Storage::delete($this->chatbot->image);
            }
            $this->chatbot->image = $url;
        }
    }
    public function removeImage() {
        if ($this->chatbot->image) {
            if (Storage::exists($this->chatbot->image->url)) {
                Storage::delete($this->chatbot->image->url);
            }
            $this->chatbot->image()->delete();
            $this->chatbot->image = null;
        }
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }
}

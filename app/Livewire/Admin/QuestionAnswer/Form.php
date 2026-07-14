<?php

namespace App\Livewire\Admin\QuestionAnswer;

use App\Models\QuestionAnswer;
use App\Traits\LivewireTranslatable;
use Livewire\Component;

class Form extends Component
{
    use LivewireTranslatable;

    public $questionAnswer;
    public $method;
    public $imageTmp;

    protected function rules() {
        return [
            'translations.question.'.translatable() => 'required',
            'translations.answer.'.translatable() => 'required',
        ];
    }
    public function mount(QuestionAnswer $questionAnswer, $method) {
        $this->questionAnswer = $questionAnswer;
        $this->method = $method;
        $this->loadTranslations($this->questionAnswer);
    }
    public function render() {
        return view('livewire.admin.question-answer.form');
    }
    public function store() {
        $this->validate();
        $this->saveTranslations($this->questionAnswer);
        $this->questionAnswer->save();
        $this->questionAnswer = new QuestionAnswer;
        $this->regenerateCache();
        $this->reset('imageTmp', 'translations');
        $this->dispatch('alert', 'success', 'Agregado con éxito');
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->saveTranslations($this->questionAnswer);
        $this->questionAnswer->update();
        $this->regenerateCache();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
    private function regenerateCache() {
        QuestionAnswer::regenerateCache();
    }
}

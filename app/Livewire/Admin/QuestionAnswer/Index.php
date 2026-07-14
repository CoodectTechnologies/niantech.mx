<?php

namespace App\Livewire\Admin\QuestionAnswer;

use App\Models\QuestionAnswer;
use Exception;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render() {
        $questionAnswers = QuestionAnswer::orderBy('id', 'desc')->get();

        return view('livewire.admin.question-answer.index', compact('questionAnswers'));
    }
    public function destroy(QuestionAnswer $questionAnswer) {
        try {
            $questionAnswer->delete();
            QuestionAnswer::regenerateCache();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

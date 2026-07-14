<?php

namespace App\Livewire\Admin\Questionnaire;

use App\Models\Questionnaire;
use App\Models\QuestionnaireResponse;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public $questionnaire;
    public $search = '';
    public $filterApt = 'all'; // all, apt, not-apt
    protected $queryString = ['search', 'filterApt'];

    public function mount(Questionnaire $questionnaire) {
        $this->questionnaire = $questionnaire;
        $this->questionnaire->load(['questions.options']);
    }
    public function updatingSearch() {
        $this->resetPage();
    }
    public function updatingFilterApt() {
        $this->resetPage();
    }
    public function render() {
        $responses = QuestionnaireResponse::with(['answers.question', 'answers.option'])
            ->where('questionnaire_id', $this->questionnaire->id)
            ->search($this->search)
            ->when($this->filterApt === 'apt', function ($query) {
                $query->apt();
            })
            ->when($this->filterApt === 'not-apt', function ($query) {
                $query->where('is_apt', false);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.admin.questionnaire.show', compact('responses'));
    }
    public function deleteResponse($id) {
        $response = QuestionnaireResponse::find($id);
        if ($response && $response->questionnaire_id === $this->questionnaire->id) {
            $response->delete();
            $this->dispatch('alert', 'success', __('Response successfully deleted'));
        }
    }
}

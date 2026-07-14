<?php

namespace App\Livewire\Admin\Questionnaire;

use App\Models\Questionnaire;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    protected $queryString = ['search'];

    public function updatingSearch() {
        $this->resetPage();
    }
    public function render() {
        $questionnaires = Questionnaire::with(['image', 'user'])
            ->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
            })
            ->orderBy('id', 'desc')
            ->paginate(12);

        return view('livewire.admin.questionnaire.index', compact('questionnaires'));
    }
    public function delete($id) {
        $questionnaire = Questionnaire::find($id);
        if ($questionnaire) {
            $questionnaire->delete();
            Questionnaire::regenerateCache();
            $this->dispatch('alert', 'success', __('Registration successfully deleted'));
        }
    }
}

<?php

namespace App\Livewire\Admin\Newsletter;

use App\Models\Newsletter;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 50;
    public $search;
    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['render'];

    public function updatingSearch() {
        $this->resetPage();
    }
    public function render() {
        $newsletters = Newsletter::query()->orderBy('id', 'desc');
        if ($this->search) {
            $newsletters = $newsletters->where('email', 'LIKE', "%{$this->search}%")->orWhere('email', 'LIKE', "%{$this->search}%");
        }
        $newsletters = $newsletters->paginate($this->perPage);

        return view('livewire.admin.newsletter.index', compact('newsletters'));
    }
    public function destroy(Newsletter $newsletter) {
        try {
            $newsletter->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

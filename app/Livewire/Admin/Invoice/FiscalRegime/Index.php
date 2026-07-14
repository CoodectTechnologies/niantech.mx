<?php

namespace App\Livewire\Admin\Invoice\FiscalRegime;

use App\Models\FiscalRegime;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['render'];
    public $search;

    public function render() {
        $fiscalRegimes = FiscalRegime::query()->orderBy('id', 'desc');
        if ($this->search) {
            $fiscalRegimes = $fiscalRegimes->where('code', 'LIKE', "%{$this->search}%")
                ->orWhere('description', 'LIKE', "%{$this->search}%");
        }
        $fiscalRegimes = $fiscalRegimes->paginate();

        return view('livewire.admin.invoice.fiscal-regime.index', compact('fiscalRegimes'));
    }
    public function updatingSearch() {
        $this->resetPage();
    }
    public function destroy(FiscalRegime $fiscalRegime) {
        try {
            $fiscalRegime->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

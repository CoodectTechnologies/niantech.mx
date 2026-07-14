<?php

namespace App\Livewire\Admin\Invoice\UseCfdi;

use App\Models\UseCfdi;
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
        $useCfdis = UseCfdi::query()->orderBy('id', 'desc');
        if ($this->search) {
            $useCfdis = $useCfdis->where('code', 'LIKE', "%{$this->search}%")
                ->orWhere('description', 'LIKE', "%{$this->search}%");
        }
        $useCfdis = $useCfdis->paginate();

        return view('livewire.admin.invoice.use-cfdi.index', compact('useCfdis'));
    }
    public function updatingSearch() {
        $this->resetPage();
    }
    public function destroy(UseCfdi $useCfdi) {
        try {
            $useCfdi->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

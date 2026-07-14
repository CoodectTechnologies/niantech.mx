<?php

namespace App\Livewire\Admin\Catalog\UnitType;

use App\Models\UnitType;
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
        $unitTypes = UnitType::query()->orderBy('id', 'desc');
        if ($this->search) {
            $unitTypes = $unitTypes->where('name', 'LIKE', "%{$this->search}%");
        }
        $unitTypes = $unitTypes->paginate($this->perPage);

        return view('livewire.admin.catalog.unit-type.index', compact('unitTypes'));
    }
    public function destroy(UnitType $unitType) {
        try {
            $unitType->delete();
            UnitType::regenerateCache();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

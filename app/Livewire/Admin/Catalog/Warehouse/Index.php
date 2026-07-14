<?php

namespace App\Livewire\Admin\Catalog\Warehouse;

use App\Models\ProductWarehouse;
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
        $warehouses = ProductWarehouse::query()->orderBy('id', 'desc');
        if ($this->search) {
            $warehouses = $warehouses->where('name', 'LIKE', "%{$this->search}%");
        }
        $warehouses = $warehouses->paginate($this->perPage);

        return view('livewire.admin.catalog.warehouse.index', compact('warehouses'));
    }
    public function destroy(ProductWarehouse $warehouse) {
        try {
            $warehouse->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

<?php

namespace App\Livewire\Admin\Setting\ShippingClass;

use App\Models\ShippingClass;
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
        $shippingClasses = ShippingClass::query()->with('products')->orderBy('id', 'desc');
        if ($this->search) {
            $shippingClasses = $shippingClasses->where('name', 'LIKE', "%{$this->search}%");
        }
        $shippingClasses = $shippingClasses->paginate($this->perPage);

        return view('livewire.admin.setting.shipping-class.index', compact('shippingClasses'));
    }
    public function destroy(ShippingClass $shippingClass) {
        try {
            $shippingClass->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

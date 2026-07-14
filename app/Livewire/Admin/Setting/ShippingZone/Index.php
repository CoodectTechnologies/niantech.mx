<?php

namespace App\Livewire\Admin\Setting\ShippingZone;

use App\Models\ShippingZone;
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
        $shippingZones = ShippingZone::query()->with(['country', 'states'])->orderBy('id', 'desc');
        if ($this->search) {
            $shippingZones = $shippingZones->where('name', 'LIKE', "%{$this->search}%")
                ->orWhereRelation('states', 'name', 'LIKE', "%{$this->search}%");
        }
        $shippingZones = $shippingZones->paginate($this->perPage);

        return view('livewire.admin.setting.shipping-zone.index', compact('shippingZones'));
    }
    public function destroy(ShippingZone $shippingZone) {
        try {
            $shippingZone->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

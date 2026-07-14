<?php

namespace App\Livewire\Admin\Setting\Warehouse;

use App\Models\Country;
use App\Models\ProductWarehouse;
use App\Models\State;
use Livewire\Component;

class Form extends Component
{
    public $method;
    public $countryId;
    public $warehouseState = [];
    public $warehouseStateArray = [];

    public function mount($method) {
        $this->method = $method;
        $this->countryId = Country::where('default', true)->first()->id;
        $this->loadWarehouseState();
    }
    public function render() {
        $countries = Country::where('status', true)->get();

        return view('livewire.admin.setting.warehouse.form', compact('countries'));
    }
    public function save() {
        foreach ($this->warehouseState['states'] as $stateId => $productWarehouses) {
            $state = State::find($stateId);
            $syncProductWarehouses = [];
            foreach ($productWarehouses['productWarehouses'] as $productWarehouseId => $pwArray) {
                $syncProductWarehouses[$productWarehouseId]['priority'] = $pwArray['priority'];
            }
            $state->productWarehouses()->sync($syncProductWarehouses);
        }
        $this->dispatch('alert', 'success', __('Changes saves'));
        $this->dispatch('render');
    }
    public function loadWarehouseState() {
        $this->warehouseState = [];
        $this->warehouseStateArray = [];
        $states = State::where('country_id', $this->countryId)->get();
        $productWarehouses = ProductWarehouse::all();
        foreach ($states as $state) {
            $this->warehouseStateArray['states'][$state->id] = $state->toArray();
            foreach ($productWarehouses as $productWarehouse) {
                $this->warehouseStateArray['states'][$state->id]['productWarehouses'][$productWarehouse->id] = $productWarehouse->toArray();
                $productWarehouseState = $productWarehouse->states()->where('state_id', $state->id)->first();
                $this->warehouseState['states'][$state->id]['productWarehouses'][$productWarehouse->id]['priority'] = $productWarehouseState ? $productWarehouseState->pivot->priority : null;
            }
        }
    }
}

<?php

namespace App\Livewire\Admin\Catalog\Warehouse;

use App\Models\ProductWarehouse;
use Livewire\Component;

class Form extends Component
{
    public $warehouse;
    public $method;

    protected function rules() {
        return [
            'warehouse.name' => 'required',
        ];
    }
    public function mount(ProductWarehouse $warehouse, $method) {
        $this->warehouse = $warehouse;
        $this->method = $method;
    }
    public function render() {
        return view('livewire.admin.catalog.warehouse.form');
    }
    public function store() {
        $this->validate();
        $this->warehouse->save();
        $this->warehouse = new ProductWarehouse;
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->warehouse->update();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
}

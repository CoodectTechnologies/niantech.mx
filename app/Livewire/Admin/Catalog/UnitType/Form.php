<?php

namespace App\Livewire\Admin\Catalog\UnitType;

use App\Models\UnitType;
use Livewire\Component;

class Form extends Component
{
    public $unitType;
    public $method;

    protected function rules() {
        return [
            'unitType.name_sat' => 'required',
            'unitType.name' => 'required',
            'unitType.code' => 'required',
            'unitType.description' => 'nullable',
        ];
    }
    public function mount(UnitType $unitType, $method) {
        $this->unitType = $unitType;
        $this->method = $method;
    }
    public function render() {
        return view('livewire.admin.catalog.unit-type.form');
    }
    public function store() {
        $this->validate();
        $this->unitType->save();
        $this->unitType = new UnitType;
        UnitType::regenerateCache();
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->unitType->update();
        UnitType::regenerateCache();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
}

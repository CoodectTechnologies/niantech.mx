<?php

namespace App\Livewire\Admin\Invoice\FiscalRegime;

use App\Models\FiscalRegime;
use Livewire\Component;

class Form extends Component
{
    public $fiscalRegime;
    public $method;

    protected function rules() {
        return [
            'fiscalRegime.code' => 'required',
            'fiscalRegime.description' => 'required',
        ];
    }
    public function mount(FiscalRegime $fiscalRegime, $method) {
        $this->fiscalRegime = $fiscalRegime;
        $this->method = $method;
    }
    public function render() {
        return view('livewire.admin.invoice.fiscal-regime.form');
    }
    public function store() {
        $this->validate();
        $this->fiscalRegime->save();
        $this->fiscalRegime = new FiscalRegime;
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->fiscalRegime->update();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
}

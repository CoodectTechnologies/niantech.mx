<?php

namespace App\Livewire\Admin\Invoice\UseCfdi;

use App\Models\UseCfdi;
use Livewire\Component;

class Form extends Component
{
    public $useCfdi;
    public $method;

    protected function rules() {
        return [
            'useCfdi.code' => 'required',
            'useCfdi.description' => 'required',
        ];
    }
    public function mount(UseCfdi $useCfdi, $method) {
        $this->useCfdi = $useCfdi;
        $this->method = $method;
    }
    public function render() {
        return view('livewire.admin.invoice.use-cfdi.form');
    }
    public function store() {
        $this->validate();
        $this->useCfdi->save();
        $this->useCfdi = new UseCfdi;
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->useCfdi->update();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
}

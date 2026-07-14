<?php

namespace App\Livewire\Admin\Setting\ModuleWeb;

use App\Models\ModuleWeb;
use Livewire\Component;

class Form extends Component
{
    public $moduleWeb;
    public $method;

    public function mount(ModuleWeb $moduleWeb, $method) {
        $this->moduleWeb = $moduleWeb;
        $this->method = $method;
    }
    protected function rules() {
        return [
            'moduleWeb.name' => 'required|unique:module_webs,name,'.$this->moduleWeb->id,
        ];
    }
    public function render() {
        return view('livewire.admin.setting.module-web.form');
    }
    public function store() {
        $this->validate();
        $this->moduleWeb->save();
        $this->dispatch('alert', 'success', 'Módulo web agregado con éxito');
        $this->dispatch('render');
        $this->moduleWeb = new ModuleWeb;
    }
    public function update() {
        $this->validate();
        $this->moduleWeb->update();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
}

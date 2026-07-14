<?php

namespace App\Livewire\Admin\Setting\Configurator;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Form extends Component
{
    public $method;
    public $active;
    public $budgetActive;

    protected function rules() {
        return [
            'active' => 'required',
            'budgetActive' => 'required',
        ];
    }
    public function mount($method) {
        $this->method = $method;
        $this->active = config('configurator.active');
        $this->budgetActive = config('configurator.budget_active');
    }
    public function render() {
        return view('livewire.admin.setting.configurator.form');
    }
    public function update() {
        $this->validate();
        try {
            setEnvValue('CONFIGURATOR_ACTIVE', $this->active);
            setEnvValue('CONFIGURATOR_BUDGET_ACTIVE', $this->budgetActive);
            if (file_exists(App::getCachedConfigPath())) {
                Artisan::call('config:cache');
            }
            $this->dispatch('alert', 'success', __('Registration successfully updated'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
        $this->dispatch('render');
    }
}

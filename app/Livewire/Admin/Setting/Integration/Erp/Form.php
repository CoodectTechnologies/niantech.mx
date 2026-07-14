<?php

namespace App\Livewire\Admin\Setting\Integration\Erp;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Form extends Component
{
    public $method;
    public $erpStatus;
    public $erpUrl;
    public $erpDatabase;
    public $erpUsername;
    public $erpPassword;

    protected function rules() {
        return [
            'erpStatus' => 'nullable',
            'erpUrl' => 'required|url',
            'erpDatabase' => 'required|string',
            'erpUsername' => 'required|string',
            'erpPassword' => 'required|string',
        ];
    }
    public function mount($method) {
        $this->method = $method;
        $this->erpStatus = config('services.erp.status');
        $this->erpUrl = config('services.erp.url');
        $this->erpDatabase = config('services.erp.database');
        $this->erpUsername = config('services.erp.username');
        $this->erpPassword = config('services.erp.password');
    }
    public function render() {
        return view('livewire.admin.setting.integration.erp.form');
    }
    public function update() {
        $this->validate();
        try {
            setEnvValue('ERP_STATUS', $this->erpStatus);
            setEnvValue('ERP_URL', $this->erpUrl);
            setEnvValue('ERP_DATABASE', $this->erpDatabase);
            setEnvValue('ERP_USERNAME', $this->erpUsername);
            setEnvValue('ERP_PASSWORD', $this->erpPassword);
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

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
    public $erpLanguage;
    public $erpKey;

    protected function rules() {
        return [
            'erpStatus' => 'nullable',
            'erpUrl' => 'required|url',
            'erpDatabase' => 'required|string',
            'erpLanguage' => 'required|string',
            'erpKey' => 'required|string',
        ];
    }
    public function mount($method) {
        $this->method = $method;
        $this->erpStatus = config('services.odoo.status');
        $this->erpUrl = config('services.odoo.url');
        $this->erpDatabase = config('services.odoo.database');
        $this->erpLanguage = config('services.odoo.language');
        $this->erpKey = config('services.odoo.key');
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
            setEnvValue('ODOO_LANGUAGE', $this->erpLanguage);
            setEnvValue('ODOO_KEY', $this->erpKey);
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

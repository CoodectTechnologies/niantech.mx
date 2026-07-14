<?php

namespace App\Livewire\Admin\Setting\AccessPayment\Paypal;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Form extends Component
{
    public $method;
    public $paypalStatus;
    public $paypalErpId;
    public $paypalClientId;

    protected function rules() {
        return [
            'paypalStatus' => 'nullable',
            'paypalErpId' => 'nullable',
            'paypalClientId' => 'nullable',
        ];
    }
    public function mount($method) {
        $this->method = $method;
        $this->paypalStatus = config('services.paypal.status');
        $this->paypalErpId = config('services.paypal.erp_id');
        $this->paypalClientId = config('services.paypal.client_id');
    }
    public function render() {
        return view('livewire.admin.setting.access-payment.paypal.form');
    }
    public function update() {
        $this->validate();
        try {
            setEnvValue('PAYPAL_STATUS', $this->paypalStatus);
            setEnvValue('PAYPAL_ERP_ID', $this->paypalErpId);
            setEnvValue('PAYPAL_CLIENT_ID', $this->paypalClientId);
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

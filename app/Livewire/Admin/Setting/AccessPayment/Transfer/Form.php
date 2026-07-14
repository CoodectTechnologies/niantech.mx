<?php

namespace App\Livewire\Admin\Setting\AccessPayment\Transfer;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Form extends Component
{
    public $method;
    public $paymentStatus;
    public $paymentErpId;
    public $paymentBank;
    public $paymentAccountBank;
    public $paymentTarget;
    public $paymentName;

    protected function rules() {
        return [
            'paymentStatus' => 'required',
            'paymentErpId' => 'required',
            'paymentAccountBank' => 'required',
            'paymentTarget' => 'required',
            'paymentBank' => 'required',
            'paymentName' => 'required',
        ];
    }
    public function mount($method) {
        $this->method = $method;
        $this->paymentStatus = config('services.transfer.status');
        $this->paymentErpId = config('services.transfer.erp_id');
        $this->paymentAccountBank = config('services.transfer.account_bank');
        $this->paymentTarget = config('services.transfer.target');
        $this->paymentBank = config('services.transfer.bank');
        $this->paymentName = config('services.transfer.name');
    }
    public function render() {
        return view('livewire.admin.setting.access-payment.transfer.form');
    }
    public function update() {
        $this->validate();
        try {
            setEnvValue('TRANSFER_STATUS', $this->paymentStatus);
            setEnvValue('TRANSFER_ERP_ID', $this->paymentErpId);
            setEnvValue('TRANSFER_BANK', $this->paymentBank);
            setEnvValue('TRANSFER_ACCOUNT_BANK', $this->paymentAccountBank);
            setEnvValue('TRANSFER_TARGET', $this->paymentTarget);
            setEnvValue('TRANSFER_NAME', $this->paymentName);
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

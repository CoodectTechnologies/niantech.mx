<?php

namespace App\Livewire\Admin\Setting\AccessPayment\Mercadopago;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Form extends Component
{
    public $method;
    public $mercadoStatus;
    public $mercadoPagoErpId;
    public $mercadoPagoKey;
    public $mercadoPagoToken;
    public $mercadoPagoCountryCode;
    public $mercadoPagoCurrencyCode;

    protected function rules() {
        return [
            'mercadoStatus' => 'nullable',
            'mercadoPagoErpId' => 'nullable',
            'mercadoPagoKey' => 'nullable',
            'mercadoPagoToken' => 'nullable',
            'mercadoPagoCountryCode' => 'nullable',
            'mercadoPagoCurrencyCode' => 'nullable',
        ];
    }
    public function mount($method) {
        $this->method = $method;
        $this->mercadoStatus = config('services.mercadopago.status');
        $this->mercadoPagoErpId = config('services.mercadopago.erp_id');
        $this->mercadoPagoKey = config('services.mercadopago.key');
        $this->mercadoPagoToken = config('services.mercadopago.token');
        $this->mercadoPagoCountryCode = config('services.mercadopago.country_code');
        $this->mercadoPagoCurrencyCode = config('services.mercadopago.currency_code');
    }
    public function render() {
        return view('livewire.admin.setting.access-payment.mercadopago.form');
    }
    public function update() {
        $this->validate();
        try {
            setEnvValue('MERCADOPAGO_STATUS', $this->mercadoStatus);
            setEnvValue('MERCADOPAGO_ERP_ID', $this->mercadoPagoErpId);
            setEnvValue('MERCADOPAGO_PUBLIC_KEY', $this->mercadoPagoKey);
            setEnvValue('MERCADOPAGO_ACCESS_TOKEN', $this->mercadoPagoToken);
            setEnvValue('MERCADOPAGO_COUNTRY_CODE', $this->mercadoPagoCountryCode);
            setEnvValue('MERCADOPAGO_CURRENCY_CODE', $this->mercadoPagoCurrencyCode);
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
    public function countriesCodeAllowed() {
        return ['es-MX', 'es-AR', 'pt-BR', 'es-CL', 'es-CO', 'es-PE', 'es-UY'];
    }
    public function currenciesCodeAllowed() {
        return ['MXN', 'ARS', 'BRL', 'CLP', 'COP', 'PEN', 'UYU'];
    }
}

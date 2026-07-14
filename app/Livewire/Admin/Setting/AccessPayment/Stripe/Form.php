<?php

namespace App\Livewire\Admin\Setting\AccessPayment\Stripe;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Form extends Component
{
    public $method;
    public $stripeStatus;
    public $stripeErpId;
    public $stripePublic;
    public $stripeSecret;

    protected function rules() {
        return [
            'stripeStatus' => 'nullable',
            'stripeErpId' => 'nullable',
            'stripePublic' => 'nullable',
            'stripeSecret' => 'nullable',
        ];
    }
    public function mount($method) {
        $this->method = $method;
        $this->stripeStatus = config('services.stripe.status');
        $this->stripeErpId = config('services.stripe.erp_id');
        $this->stripePublic = config('services.stripe.public');
        $this->stripeSecret = config('services.stripe.secret');
    }
    public function render() {
        return view('livewire.admin.setting.access-payment.stripe.form');
    }
    public function update() {
        $this->validate();
        try {
            setEnvValue('STRIPE_STATUS', $this->stripeStatus);
            setEnvValue('STRIPE_ERP_ID', $this->stripeErpId);
            setEnvValue('STRIPE_KEY', $this->stripePublic);
            setEnvValue('STRIPE_SECRET', $this->stripeSecret);
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

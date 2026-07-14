<?php

namespace App\Livewire\Admin\Setting\AccessPayment\Openpaybbva;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;
use Openpay\Data\Openpay;

class Form extends Component
{
    protected $listeners = ['render'];
    public $method;
    public $openpaybbvaStatus;
    public $openpaybbvaErpId;
    public $openpaybbvaId;
    public $openpaybbvaPublic;
    public $openpaybbvaPrivate;
    public $openpaybbvaCountryCode;

    protected function rules() {
        return [
            'openpaybbvaStatus' => 'nullable',
            'openpaybbvaErpId' => 'nullable',
            'openpaybbvaId' => 'nullable',
            'openpaybbvaPublic' => 'nullable',
            'openpaybbvaPrivate' => 'nullable',
            'openpaybbvaCountryCode' => 'nullable',
        ];
    }
    public function mount($method) {
        $this->method = $method;
        $this->openpaybbvaStatus = config('services.openpay_bbva.status');
        $this->openpaybbvaErpId = config('services.openpay_bbva.erp_id');
        $this->openpaybbvaId = config('services.openpay_bbva.id');
        $this->openpaybbvaPublic = config('services.openpay_bbva.public');
        $this->openpaybbvaPrivate = config('services.openpay_bbva.private');
        $this->openpaybbvaCountryCode = config('services.openpay_bbva.country_code');
    }
    public function render() {
        $webhooks = $this->getWebhooks();

        return view('livewire.admin.setting.access-payment.openpaybbva.form', compact('webhooks'));
    }
    public function update() {
        $this->validate();
        try {
            setEnvValue('OPENPAY_BBVA_STATUS', $this->openpaybbvaStatus);
            setEnvValue('OPENPAY_BBVA_ERP_ID', $this->openpaybbvaErpId);
            setEnvValue('OPENPAY_BBVA_ID', $this->openpaybbvaId);
            setEnvValue('OPENPAY_BBVA_PUBLIC_KEY', $this->openpaybbvaPublic);
            setEnvValue('OPENPAY_BBVA_PRIVATE_KEY', $this->openpaybbvaPrivate);
            setEnvValue('OPENPAY_BBVA_COUNTRY_CODE', $this->openpaybbvaCountryCode);
            if (file_exists(App::getCachedConfigPath())) {
                Artisan::call('config:cache');
            }
            $this->createWebhook();
            $this->dispatch('alert', 'success', __('Registration successfully updated'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
        $this->dispatch('render');
    }
    public function countriesCodeAllowed() {
        return ['MX', 'CO', 'PE'];
    }
    public function createWebhook() {
        if (
            config('services.openpay_bbva.status') &&
            config('services.openpay_bbva.public') &&
            config('services.openpay_bbva.private')
        ) {
            try {
                if (! count($this->getWebhooks())) {
                    $openpay = Openpay::getInstance(config('services.openpay_bbva.id'), config('services.openpay_bbva.private'), countryByLanguage()['code'], request()->ip());
                    $webhook = [
                        'url' => route('web.webhook.payment.openpaybbva'),
                        'user' => config('app.name'),
                        'password' => config('app.key'),
                        'event_types' => [
                            'charge.refunded',
                            'charge.failed',
                            'charge.cancelled',
                            'charge.created',
                            'chargeback.accepted',
                        ],
                    ];
                    $webhook = $openpay->webhooks->add($webhook);

                    return $webhook;
                }
            } catch (Exception $e) {
                $this->dispatch('alert', 'warning', $e->getMessage());

                return null;
            }
        }

        return null;
    }
    public function getWebhooks() {
        if (
            config('services.openpay_bbva.status') &&
            config('services.openpay_bbva.public') &&
            config('services.openpay_bbva.private')
        ) {
            try {
                $openpay = Openpay::getInstance(config('services.openpay_bbva.id'), config('services.openpay_bbva.private'), countryByLanguage()['code'], request()->ip());
                $webhooks = $openpay->webhooks->getList([]);

                return $webhooks;
            } catch (Exception $e) {
                $this->dispatch('alert', 'warning', $e->getMessage());

                return [];
            }
        }

        return [];
    }
    public function deleteWebhook($webhookId) {
        if (
            config('services.openpay_bbva.status') &&
            config('services.openpay_bbva.public') &&
            config('services.openpay_bbva.private')
        ) {
            try {
                $openpay = Openpay::getInstance(config('services.openpay_bbva.id'), config('services.openpay_bbva.private'), countryByLanguage()['code'], request()->ip());
                $webhook = $openpay->webhooks->get($webhookId);
                $webhook->delete();
                $this->dispatch('alert', 'success', 'Webhook eliminado con éxito, te recomendamos agregar uno nuevo para que tu sistemas este atento a los cambios de status de pago');
                $this->dispatch('render')->self();
            } catch (Exception $e) {
                $this->dispatch('alert', 'warning', $e->getMessage());
                $this->dispatch('render')->self();
            }
        }
    }
}

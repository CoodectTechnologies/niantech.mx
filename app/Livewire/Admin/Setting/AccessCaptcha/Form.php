<?php

namespace App\Livewire\Admin\Setting\AccessCaptcha;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Form extends Component
{
    public $method;
    public $captchaStatus;
    public $captchaPublicKey;
    public $captchaSecretKey;

    protected function rules() {
        return [
            'captchaStatus' => 'nullable',
            'captchaPublicKey' => 'nullable',
            'captchaSecretKey' => 'nullable',
        ];
    }
    public function mount($method) {
        $this->method = $method;
        $this->captchaStatus = config('honey.recaptcha.status');
        $this->captchaPublicKey = config('honey.recaptcha.site_key');
        $this->captchaSecretKey = config('honey.recaptcha.secret_key');
    }
    public function render() {
        return view('livewire.admin.setting.access-captcha.form');
    }
    public function update() {
        $this->validate();
        try {
            setEnvValue('NEWSLETTER_STATUS', $this->captchaStatus);
            setEnvValue('RECAPTCHA_SITE_KEY', $this->captchaPublicKey);
            setEnvValue('RECAPTCHA_SECRET_KEY', $this->captchaSecretKey);
            if (file_exists(App::getCachedConfigPath())) {
                Artisan::call('config:cache');
            }
            $this->dispatch('alert', 'success', 'Accesos actualizados con éxito');
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
        $this->dispatch('render');
    }
}

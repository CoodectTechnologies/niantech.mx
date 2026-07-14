<?php

namespace App\Livewire\Admin\Setting\Integration\Brands;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Form extends Component
{
    public $method;
    public $brandsStatus;
    public $brandsUrl;
    public $brandsUser;
    public $brandsPass;
    public $brandsAllowed;

    protected function rules() {
        return [
            'brandsStatus' => 'nullable',
            'brandsUrl' => 'nullable',
            'brandsUser' => 'nullable',
            'brandsPass' => 'nullable',
            'brandsAllowed' => 'nullable',
        ];
    }
    public function mount($method) {
        $this->method = $method;
        $this->brandsStatus = config('services.vadeto_brands.status');
        $this->brandsUrl = config('services.vadeto_brands.url');
        $this->brandsUser = config('services.vadeto_brands.user');
        $this->brandsPass = config('services.vadeto_brands.pass');
        $this->brandsAllowed = implode(',', config('services.vadeto_brands.allowed'));
    }
    public function render() {
        return view('livewire.admin.setting.integration.brands.form');
    }
    public function update() {
        $this->validate();
        try {
            setEnvValue('BRANDS_STATUS', $this->brandsStatus);
            setEnvValue('BRANDS_URL', $this->brandsUrl);
            setEnvValue('BRANDS_USER', $this->brandsUser);
            setEnvValue('BRANDS_PASS', $this->brandsPass);
            setEnvValue('BRANDS_ALLOWED', $this->brandsAllowed);
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

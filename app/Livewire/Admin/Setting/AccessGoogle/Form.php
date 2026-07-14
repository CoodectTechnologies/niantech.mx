<?php

namespace App\Livewire\Admin\Setting\AccessGoogle;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Form extends Component
{
    public $method;
    public $googleStatus;
    public $googleClientId;
    public $googleClientSecret;

    protected function rules() {
        return [
            'googleStatus' => 'nullable',
            'googleClientId' => 'nullable',
            'googleClientSecret' => 'nullable',
        ];
    }
    public function mount($method) {
        $this->method = $method;
        $this->googleStatus = config('services.google.status');
        $this->googleClientId = config('services.google.client_id');
        $this->googleClientSecret = config('services.google.client_secret');
    }
    public function render() {
        return view('livewire.admin.setting.access-google.form');
    }
    public function update() {
        $this->validate();
        try {
            setEnvValue('GOOGLE_CLIENT_STATUS', $this->googleStatus);
            setEnvValue('GOOGLE_CLIENT_ID', $this->googleClientId);
            setEnvValue('GOOGLE_CLIENT_SECRET', $this->googleClientSecret);
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

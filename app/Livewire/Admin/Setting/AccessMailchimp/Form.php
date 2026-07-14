<?php

namespace App\Livewire\Admin\Setting\AccessMailchimp;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Form extends Component
{
    public $method;
    public $mailchimpStatus;
    public $mailchimpApiKey;
    public $mailchimpListId;

    protected function rules() {
        return [
            'mailchimpStatus' => 'nullable',
            'mailchimpApiKey' => 'nullable',
            'mailchimpListId' => 'nullable',
        ];
    }
    public function mount($method) {
        $this->method = $method;
        $this->mailchimpStatus = config('newsletter.status');
        $this->mailchimpApiKey = config('newsletter.driver_arguments.api_key');
        $this->mailchimpListId = config('newsletter.lists.subscribers.id');
    }
    public function render() {
        return view('livewire.admin.setting.access-mailchimp.form');
    }
    public function update() {
        $this->validate();
        try {
            setEnvValue('NEWSLETTER_STATUS', $this->mailchimpStatus);
            setEnvValue('NEWSLETTER_API_KEY', $this->mailchimpApiKey);
            setEnvValue('NEWSLETTER_LIST_ID', $this->mailchimpListId);
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

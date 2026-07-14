<?php

namespace App\Livewire\Admin\Setting\Contact;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class Form extends Component
{
    public $method;
    public $phone;
    public $phone2;
    public $email;
    public $facebook;
    public $twitter;
    public $instagram;
    public $youtube;
    public $whatsapp;
    public $linkedin;
    public $map;

    protected function rules() {
        return [
            'phone' => 'required',
            'phone2' => 'nullable',
            'email' => 'required',
            'facebook' => 'nullable',
            'twitter' => 'nullable',
            'instagram' => 'nullable',
            'youtube' => 'nullable',
            'whatsapp' => 'nullable',
            'linkedin' => 'nullable',
            'map' => 'nullable',
        ];
    }
    public function mount($method) {
        $this->method = $method;
        $this->phone = config('contact.phone');
        $this->phone2 = config('contact.phone2');
        $this->email = config('contact.email');
        $this->facebook = config('contact.facebook');
        $this->twitter = config('contact.twitter');
        $this->instagram = config('contact.instagram');
        $this->youtube = config('contact.youtube');
        $this->whatsapp = config('contact.whatsapp');
        $this->linkedin = config('contact.linkedin');
        $this->map = config('contact.map');
    }
    public function render() {
        return view('livewire.admin.setting.contact.form');
    }
    public function update() {
        $this->validate();
        try {
            setEnvValue('CONTACT_PHONE', $this->phone);
            setEnvValue('CONTACT_PHONE2', $this->phone2);
            setEnvValue('CONTACT_EMAIL', $this->email);
            setEnvValue('CONTACT_FACEBOOK', $this->facebook);
            setEnvValue('CONTACT_TWITTER', $this->twitter);
            setEnvValue('CONTACT_INSTAGRAM', $this->instagram);
            setEnvValue('CONTACT_YOUTUBE', $this->youtube);
            setEnvValue('CONTACT_WHATSAPP', $this->whatsapp);
            setEnvValue('CONTACT_LINKEDIN', $this->linkedin);
            setEnvValue('CONTACT_MAP_IFRAME', $this->map);
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

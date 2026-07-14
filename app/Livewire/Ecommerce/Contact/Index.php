<?php

namespace App\Livewire\Ecommerce\Contact;

use App\Models\EmailWeb;
use App\Models\User;
use App\Notifications\Contact\ContactCreate as NotificationContactCreate;
use App\Traits\LivewireRecaptcha;
use Lukeraymonddowning\Honey\Traits\WithRecaptcha;
use Exception;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class Index extends Component
{
    use LivewireRecaptcha;
    use WithRecaptcha;

    public $emailWeb;

    protected function rules() {
        return [
            'emailWeb.name' => 'required',
            'emailWeb.email' => 'required|email',
            'emailWeb.phone' => 'required',
            'emailWeb.subject' => 'required',
            'emailWeb.body' => 'required',
        ];
    }
    public function mount(EmailWeb $emailWeb, $subject = null, $body = null) {
        $this->emailWeb = $emailWeb;
        if ($subject) {
            $this->emailWeb->subject = $subject;
        }
        if ($body) {
            $this->emailWeb->body = $body;
        }
    }
    public function render() {
        return view('livewire.ecommerce.contact.index');
    }
    public function sendEmail() {
        $this->validateRecaptcha();
        $this->validate();
        $this->emailWeb->phone = str_replace(' ', '', $this->emailWeb->phone);
        $this->emailWeb->save();
        try {
            Notification::send(
                User::permission('correos')->get(),
                new NotificationContactCreate($this->emailWeb)
            );
            $this->emailWeb = new EmailWeb;
            session()->flash('alert', 'Correo enviado con éxito');
            session()->flash('alert-type', 'success');
        } catch (Exception $e) {
            session()->flash('alert', '¡Ups! ocurrio un error.'.$e->getMessage());
            session()->flash('alert-type', 'danger');
        }
    }
}

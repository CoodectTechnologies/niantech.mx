<?php

namespace App\Livewire\Web\Contact;

use App\Models\EmailWeb;
use App\Models\User;
use App\Notifications\Contact\ContactCreate;
use App\Traits\LivewireRecaptcha;
use Exception;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class Index extends Component
{
    use LivewireRecaptcha;

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
    public function mount(EmailWeb $emailWeb) {
        $this->emailWeb = $emailWeb;
    }
    public function render() {
        return view('livewire.web.contact.index');
    }
    public function sendEmail() {
        $this->validate();
        $this->validateRecaptcha();
        $this->emailWeb->phone = str_replace(' ', '', $this->emailWeb->phone);
        $this->emailWeb->save();
        try {
            Notification::send(User::permission('correos')->get(), new ContactCreate($this->emailWeb));
            $this->emailWeb = new EmailWeb;
            session()->flash('alert', 'Correo enviado con éxito');
            session()->flash('alert-type', 'success');
        } catch (Exception $e) {
            session()->flash('alert', '¡Ups! ocurrio un error.'.$e->getMessage());
            session()->flash('alert-type', 'danger');
        }
    }
}

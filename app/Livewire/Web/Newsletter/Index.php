<?php

namespace App\Livewire\Web\Newsletter;

use App\Models\Newsletter;
use App\Models\User;
use App\Notifications\Newsletter\NewsletterCreate;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class Index extends Component
{
    public $email;

    protected function rules() {
        return [
            'email' => 'required|email|unique:subscribers,email',
        ];
    }
    protected function messages() {
        return [
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'El campo correo electrónico debe ser una dirección de correo electrónico válida.',
            'email.unique' => 'Este correo electrónico ya está suscrito.',
        ];
    }
    public function render() {
        return view('livewire.web.newsletter.index');
    }
    public function store() {
        $subscriber = Newsletter::create([
            'email' => $this->email,
        ]);
        Notification::send(User::permission(['subscriptores'])->get(), new NewsletterCreate($subscriber));
        $this->reset('email');
        session()->flash('alert-type-subscriber', 'success');
        session()->flash('alert-subscriber', __('Excellent, now you will be up to date with our latest news.'));
    }
}

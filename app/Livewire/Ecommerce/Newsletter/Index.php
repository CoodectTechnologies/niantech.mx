<?php

namespace App\Livewire\Ecommerce\Newsletter;

use App\Models\Newsletter;
use App\Models\User;
use App\Notifications\Newsletter\NewsletterCreate;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;

class Index extends Component
{
    public $email;

    public function render() {
        return view('livewire.ecommerce.newsletter.index');
    }
    public function store() {
        $this->validate([
            'email' => 'required|email|unique:newsletters,email',
        ]);
        $newsletter = Newsletter::create([
            'email' => $this->email,
        ]);
        Notification::send(
            User::permission(['newsletter'])->get(),
            new NewsletterCreate($newsletter)
        );
        $this->reset('email');
        session()->flash('alert-type-newsletter', 'success');
        session()->flash('alert-newsletter', 'Excelente, ahora estarás al tanto de cuando haya alguna oferta.');
    }
}

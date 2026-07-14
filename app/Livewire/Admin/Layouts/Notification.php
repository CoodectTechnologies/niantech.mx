<?php

namespace App\Livewire\Admin\Layouts;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notification extends Component
{
    protected $listeners = ['render'];
    public $notificationsCount = 0;

    public function mount() {}
    public function render() {
        $this->loadNotifications();

        return view('livewire.admin.layouts.notification');
    }
    private function loadNotifications() {
        $this->notificationsCount = Auth::user()->unreadNotifications()->count() ?? 0;
    }
}

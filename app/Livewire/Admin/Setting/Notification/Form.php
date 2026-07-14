<?php

namespace App\Livewire\Admin\Setting\Notification;

use App\Models\NotificationPreference;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Form extends Component
{
    public User $user;
    public NotificationPreference $notificationPreference;

    public function rules() {
        return [
            'notificationPreference.push_notifications' => 'nullable',
            'notificationPreference.email_notifications' => 'nullable',
        ];
    }
    public function mount() {
        $this->user = User::find(Auth::id());
        $this->notificationPreference = NotificationPreference::where('user_id', $this->user->id)->firstOrNew();
        $this->notificationPreference->push_notifications = $this->notificationPreference->exists ? $this->notificationPreference->push_notifications : true;
        $this->notificationPreference->email_notifications = $this->notificationPreference->exists ? $this->notificationPreference->email_notifications : true;
    }
    public function render() {
        return view('livewire.admin.setting.notification.form');
    }
    public function save() {
        $this->validate();
        try {
            $this->notificationPreference->user_id = $this->user->id;
            $this->notificationPreference->save();
            $this->dispatch('alert', 'success', 'Preferencias guardadas');
        } catch (Exception $e) {
            $this->dispatch('alert', 'success', 'Error: '.$e->getMessage());
        }
    }
}

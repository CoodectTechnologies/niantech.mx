<?php

namespace App\Livewire\Admin\Notification;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $user;
    public $filterReadAt = 'Sin leer';
    public $perPage = 15;

    public function mount() {
        $this->user = User::find(Auth::id());
    }
    public function render() {
        $query = $this->user->notifications();
        if ($this->filterReadAt == 'Sin leer') {
            $query->whereNull('read_at');
        } else {
            $query->whereNotNull('read_at');
        }
        $notifications = $query->paginate($this->perPage);

        return view('livewire.admin.notification.index', compact('notifications'));
    }
    public function markAllAsRead() {
        $this->user->unreadNotifications()->update(['read_at' => now()]);
        $this->dispatch('render')->to('admin.layouts.sidebar.notification.index');
    }
    public function markAndRedirect($id, $url) {
        $this->user->notifications()->where('id', $id)->update(['read_at' => now()]);

        return Redirect::to($url);
    }
}

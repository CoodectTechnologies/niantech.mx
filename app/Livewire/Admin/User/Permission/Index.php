<?php

namespace App\Livewire\Admin\User\Permission;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];
    public $user;

    public function mount(User $user) {
        $this->user = $user;
    }
    public function render() {
        $permissions = $this->user->permissions;

        return view('livewire.admin.user.permission.index', compact('permissions'));
    }
}

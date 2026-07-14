<?php

namespace App\Livewire\Admin\User\User;

use App\Models\User;
use Illuminate\Http\Request;
use Livewire\Component;

class Show extends Component
{
    public $user;
    public $submodule;
    public $connectAcountGoogle;
    protected $listeners = ['render'];

    public function mount(User $user, Request $request) {
        $this->user = $user;
        $this->submodule = $request->submodule ?? null;
        $this->user->load(['roles']);
        $this->connectAcountGoogle = $user->connected_google;
    }
    public function render() {
        return view('livewire.admin.user.user.show');
    }
    public function syncConnectAcount() {
        $this->user->update(['connected_google' => $this->connectAcountGoogle]);
        $this->dispatch('alert', 'success', 'Cambio aplicado con éxito');
    }
}

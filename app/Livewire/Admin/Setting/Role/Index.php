<?php

namespace App\Livewire\Admin\Setting\Role;

use App\Models\Role;
use Exception;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render() {
        $roles = Role::with('permissions')->orderBy('id', 'desc')->get();

        return view('livewire.admin.setting.role.index', compact('roles'));
    }
    public function destroy(Role $role) {
        try {
            $role->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

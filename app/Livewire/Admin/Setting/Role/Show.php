<?php

namespace App\Livewire\Admin\Setting\Role;

use App\Models\Role;
use App\Models\User;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public $search;
    protected $queryString = ['search'];
    public $role;
    protected $listeners = ['render'];

    public function updatingSearch() {
        $this->resetPage();
    }
    public function mount(Role $role) {
        $this->role = $role;
    }
    public function render() {
        $permissions = $this->role->permissions()->get();
        $users = $this->role->users();
        if ($this->search) {
            $users = $users->where('name', 'LIKE', "%{$this->search}%");
        }
        $users = $users->get();

        return view('livewire.admin.setting.role.show', compact('users', 'permissions'));
    }
    public function removeUser(User $user) {
        try {
            $user->removeRole($this->role);
            $this->dispatch('alert', 'success', 'Usuario removido del rol');
        } catch (Exception $e) {
            $this->dispatch('alert', 'error', 'Ocurrio un error al remover: '.$e->getMessage());
        }
    }
    public function destroy(Role $role) {
        try {
            $role->delete();
            session()->flash('alert-type', 'success');
            session()->flash('alert', 'Rol eliminado con éxito');
            redirect()->route('admin.setting.role.index');
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

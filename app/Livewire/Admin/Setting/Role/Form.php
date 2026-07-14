<?php

namespace App\Livewire\Admin\Setting\Role;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;

class Form extends Component
{
    public $role;
    public $method;
    public $permissionsArray = [];

    protected function rules() {
        return [
            'role.name' => 'required',
        ];
    }
    public function mount(Role $role, $method) {
        $this->role = $role;
        $this->method = $method;
        $this->permissionsArray = $role->permissions->pluck('name')->toArray();
    }
    public function render() {
        $permissions = Permission::orderBy('id', 'desc')->get();

        return view('livewire.admin.setting.role.form', compact('permissions'));
    }
    public function store() {
        $this->validate();
        $this->role->save();
        $this->savePermission();
        $this->role = new Role;
        $this->reset('permissionsArray');
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->role->update();
        $this->savePermission();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
    public function savePermission() {
        $this->role->syncPermissions($this->permissionsArray);
    }
}

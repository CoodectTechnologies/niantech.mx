<?php

namespace App\Livewire\Admin\Setting\Permission;

use App\Enums\Role\Role as EnumsRole;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\LivewireTranslatable;
use Livewire\Component;

class Form extends Component
{
    use LivewireTranslatable;

    public $permission;
    public $method;

    public function mount(Permission $permission, $method) {
        $this->permission = $permission;
        $this->method = $method;
        $this->loadTranslations($this->permission);
    }
    protected function rules() {
        return [
            'permission.name' => 'required|unique:permissions,name,'.$this->permission->id,
            'translations.alias.'.translatable() => 'nullable',
        ];
    }
    protected function messages() {
        return [
            'permission.name.required' => __('Permission name is required'),
            'permission.name.unique' => __('This permission name already exists'),
        ];
    }
    public function render() {
        return view('livewire.admin.setting.permission.form');
    }
    public function store() {
        $this->validate();
        $this->saveTranslations($this->permission);
        $this->permission->save();
        $this->assingToAdmin();
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
        $this->permission = new Permission;
    }
    public function update() {
        $this->validate();
        $this->permission->update();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
    public function assingToAdmin() {
        $admin = Role::where('name', EnumsRole::ADMINISTRATOR->value)->first();
        $admin->givePermissionTo($this->permission->name);
    }
}

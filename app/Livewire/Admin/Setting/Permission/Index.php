<?php

namespace App\Livewire\Admin\Setting\Permission;

use App\Models\Permission;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $search;
    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['render'];

    public function updatingSearch() {
        $this->resetPage();
    }
    public function render() {
        $permissions = Permission::query()->with('roles')->orderBy('id', 'desc');
        if ($this->search) {
            $permissions = $permissions->where('name', 'LIKE', "%{$this->search}%");
        }
        $permissions = $permissions->paginate($this->perPage);

        return view('livewire.admin.setting.permission.index', compact('permissions'));
    }
    public function destroy(Permission $permission) {
        try {
            $permission->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

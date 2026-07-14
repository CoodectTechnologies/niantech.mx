<?php

namespace App\Livewire\Admin\Setting\ModuleWeb;

use App\Models\ModuleWeb;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 50;
    public $search;
    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['render'];

    public function updatingSearch() {
        $this->resetPage();
    }
    public function render() {
        $modulesWeb = ModuleWeb::query()->orderBy('id', 'desc');
        if ($this->search) {
            $modulesWeb = $modulesWeb->where('name', 'LIKE', "%{$this->search}%");
        }
        $modulesWeb = $modulesWeb->paginate($this->perPage);

        return view('livewire.admin.setting.module-web.index', compact('modulesWeb'));
    }
    public function destroy(ModuleWeb $moduleWeb) {
        try {
            $moduleWeb->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

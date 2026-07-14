<?php

namespace App\Livewire\Admin\Wholesale;

use App\Models\Wholesale;
use Exception;
use Livewire\Component;

class Index extends Component
{
    public $search;
    protected $queryString = ['search'];
    protected $listeners = ['render'];

    public function render() {
        $wholesales = Wholesale::query()->with(['products', 'currencies'])->orderBy('id', 'desc');
        if ($this->search) {
            $wholesales = $wholesales->where('name', 'LIKE', "%{$this->search}%");
        }
        $wholesales = $wholesales->get();

        return view('livewire.admin.wholesale.index', compact('wholesales'));
    }
    public function destroy(Wholesale $wholesale) {
        try {
            $wholesale->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

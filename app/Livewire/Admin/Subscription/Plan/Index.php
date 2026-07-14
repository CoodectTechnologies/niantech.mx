<?php

namespace App\Livewire\Admin\Subscription\Plan;

use App\Models\Plan;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $queryString = ['filterSearch' => ['except' => '']];
    protected $listeners = ['render'];
    public $filterSearch;

    public function mount() {}
    public function render() {
        $plans = Plan::query()->orderBy('id', 'desc');
        $plans = $this->filters($plans);
        $plans = $plans->paginate();

        return view('livewire.admin.subscription.plan.index', compact('plans'));
    }
    public function placeholder(array $params = []) {
        return view('admin.components.skeletons.general', $params);
    }
    private function filters($plans) {
        if ($this->filterSearch) {
            $plans = $plans->where('title', 'LIKE', "%{$this->filterSearch}%");
        }

        return $plans;
    }
    public function destroy(Plan $plan) {
        try {
            $plan->delete();
            $this->dispatch('alert', 'success', __('Eliminación exitosa'));
        } catch (Exception $e) {
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
    public function updatingFilterSearch() {
        $this->resetPage();
    }
}

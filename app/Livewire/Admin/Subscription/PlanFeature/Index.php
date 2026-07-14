<?php

namespace App\Livewire\Admin\Subscription\PlanFeature;

use App\Models\PlanFeature;
use Exception;
use Livewire\Component;

class Index extends Component
{
    protected $queryString = ['search' => ['except' => '']];
    protected $listeners = ['render'];
    public $search;

    public function render() {
        $planFeatures = PlanFeature::with('plans')->orderBy('id', 'desc');
        $planFeatures = $this->filters($planFeatures);
        $planFeatures = $planFeatures->paginate(100);

        return view('livewire.admin.subscription.plan-feature.index', compact('planFeatures'));
    }
    private function filters($plans) {
        if ($this->search) {
            $plans = $plans->where('name', 'ILIKE', "%{$this->search}%");
        }

        return $plans;
    }
    public function destroy(PlanFeature $planFeature) {
        try {
            $planFeature->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

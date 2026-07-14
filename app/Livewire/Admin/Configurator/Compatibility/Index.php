<?php

namespace App\Livewire\Admin\Configurator\Compatibility;

use App\Models\ConfiguratorCompatibility;
use App\Models\ConfiguratorStage;
use Exception;
use Livewire\Component;

class Index extends Component
{
    public $configuratorStageIdFilter;

    public function render() {
        $configuratorStages = $this->getConfiguratorStages();
        $configuratorCompatibilities = $this->getConfiguratorCompatibilities();

        return view('livewire.admin.configurator.compatibility.index', compact('configuratorStages', 'configuratorCompatibilities'));
    }
    public function getConfiguratorStages() {
        return ConfiguratorStage::orderBy('order')->get();
    }
    public function getConfiguratorCompatibilities() {
        $configuratorCompatibilities = ConfiguratorCompatibility::with(['configuratorStageProduct'])->orderBy('id');
        if ($this->configuratorStageIdFilter) {
            $configuratorCompatibilities = $configuratorCompatibilities->whereHas('configuratorStageProduct', function ($query) {
                $query->where('configurator_stage_id', $this->configuratorStageIdFilter);
            });
        }
        $configuratorCompatibilities = $configuratorCompatibilities->get();

        return $configuratorCompatibilities;
    }
    public function destroy(ConfiguratorCompatibility $configuratorCompatibility) {
        try {
            $configuratorCompatibility->products()->detach();
            $configuratorCompatibility->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

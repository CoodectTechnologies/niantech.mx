<?php

namespace App\Livewire\Admin\Configurator\Stage;

use App\Models\ConfiguratorStage;
use Exception;
use Livewire\Component;

class Index extends Component
{
    public $search;
    public $typeFilter;
    protected $queryString = ['search'];

    public function render() {
        $configuratorStages = ConfiguratorStage::orderBy('order');
        if ($this->search) {
            $configuratorStages = $configuratorStages->where('name', 'LIKE', "%{$this->search}%");
        }
        if ($this->typeFilter) {
            $configuratorStages = $configuratorStages->where('type', $this->typeFilter);
        }
        $configuratorStages = $configuratorStages->get();

        return view('livewire.admin.configurator.stage.index', compact('configuratorStages'));
    }
    public function getTypes() {
        return [ConfiguratorStage::TYPE_COMPONENT, ConfiguratorStage::TYPE_ADDON];
    }
    public function destroy(ConfiguratorStage $configuratorStage) {
        try {
            if (count($configuratorStage->configuratorCompatibilities)) {
                foreach ($configuratorStage->configuratorCompatibilities as $cC) {
                    $cC->products()->detach();
                }
                $configuratorStage->configuratorCompatibilities()->delete();
            }
            $configuratorStage->products()->detach();
            $configuratorStage->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

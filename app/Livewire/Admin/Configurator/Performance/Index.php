<?php

namespace App\Livewire\Admin\Configurator\Performance;

use App\Models\ConfiguratorPerformance;
use Exception;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render() {
        $configuratorPerformances = ConfiguratorPerformance::orderByDesc('id')->get();

        return view('livewire.admin.configurator.performance.index', compact('configuratorPerformances'));
    }
    public function destroy(ConfiguratorPerformance $banner) {
        try {
            $banner->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

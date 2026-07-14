<?php

namespace App\Livewire\Admin\Configurator\Game;

use App\Models\ConfiguratorGame;
use Exception;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render() {
        $configuratorGames = ConfiguratorGame::orderByDesc('id')->get();

        return view('livewire.admin.configurator.game.index', compact('configuratorGames'));
    }
    public function destroy(ConfiguratorGame $banner) {
        try {
            $banner->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

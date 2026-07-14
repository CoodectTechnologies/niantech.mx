<?php

namespace App\Livewire\Admin\Package\Package;

use App\Models\Package;
use Exception;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render() {
        $packages = Package::with('packageFeatures')->orderBy('order')->get();

        return view('livewire.admin.package.package.index', compact('packages'));
    }
    public function destroy(Package $package) {
        try {
            $package->delete();
            Package::regenerateCache();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

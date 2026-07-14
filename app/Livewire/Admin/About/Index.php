<?php

namespace App\Livewire\Admin\About;

use App\Models\About;
use Exception;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render() {
        $about = About::first() ?? new About;

        return view('livewire.admin.about.index', compact('about'));
    }
    public function destroy(About $about) {
        try {
            $about->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

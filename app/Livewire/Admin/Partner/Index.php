<?php

namespace App\Livewire\Admin\Partner;

use App\Models\Partner;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render() {
        $partners = Partner::with('image')->orderBy('id', 'desc')->get();

        return view('livewire.admin.partner.index', compact('partners'));
    }
    public function destroy(Partner $partner) {
        try {
            if ($partner->image) {
                if (Storage::exists($partner->image->url)) {
                    Storage::delete($partner->image->url);
                }
                $partner->image()->delete();
            }
            $partner->delete();
            Partner::regenerateCache();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

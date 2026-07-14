<?php

namespace App\Livewire\Admin\Banner;

use App\Models\Banner;
use App\Models\ModuleWeb;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];
    public $module;

    public function render() {
        $modulesWeb = ModuleWeb::orderBy('id')->get();
        $banners = Banner::with(['image', 'moduleWeb']);
        if ($this->module) {
            $banners = $banners->where('module_web_id', $this->module);
        }
        $banners = $banners->orderBy('order')->get();

        return view('livewire.admin.banner.index', compact('banners', 'modulesWeb'));
    }
    public function destroy(Banner $banner) {
        try {
            if ($banner->image) {
                if (Storage::exists($banner->image->url)) {
                    Storage::delete($banner->image->url);
                }
                $banner->image()->delete();
            }
            if ($banner->video) {
                if (Storage::exists($banner->video)) {
                    Storage::delete($banner->video);
                }
            }
            Banner::regenerateCache();
            $banner->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

<?php

namespace App\Livewire\Admin\Gallery;

use App\Models\Gallery;
use App\Models\ModuleWeb;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];
    public $module;

    public function render() {
        $galleries = Gallery::with(['images', 'moduleWeb'])->orderBy('id', 'desc')->get();
        if ($this->module) {
            $galleries = $galleries->where('module_web_id', $this->module);
        }
        $modulesWeb = ModuleWeb::where('name', 'Galeria')->orderBy('id')->get();

        return view('livewire.admin.gallery.index', compact('galleries', 'modulesWeb'));
    }
    public function destroy(Gallery $gallery) {
        try {
            foreach ($gallery->images()->get() as $image) {
                if (Storage::exists($image->url)) {
                    Storage::delete($image->url);
                }
                $image->delete();
            }
            $gallery->delete();
            Gallery::regenerateCache();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

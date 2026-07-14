<?php

namespace App\Livewire\Admin\Gallery;

use App\Models\Gallery;
use App\Models\Image;
use App\Models\ModuleWeb;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $gallery;
    public $method;
    public $imagesTmp = [];
    public $imagesTmpInputId;

    protected function rules() {
        return [
            'gallery.module_web_id' => 'required|exists:module_webs,id',
            'imagesTmp' => count($this->gallery->images) ? 'nullable' : 'required',
        ];
    }
    public function mount(Gallery $gallery, $method) {
        $this->gallery = $gallery;
        $this->method = $method;
        $this->loadRandomImagesTmpInputId();
    }
    public function render() {
        $galleryImages = $this->gallery->images()->get();
        $modulesWeb = ModuleWeb::where('name', 'Galeria')->orderBy('id')->get();

        return view('livewire.admin.gallery.form', compact('galleryImages', 'modulesWeb'));
    }
    public function store() {
        $this->validate();
        $this->gallery->save();
        $this->saveImages();
        $this->gallery = new Gallery;
        $this->regenerateCache();
        $this->loadRandomImagesTmpInputId();
        $this->reset('imagesTmp');
        $this->dispatch('alert', 'success', 'Galería agregadas con éxito');
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->gallery->update();
        $this->saveImages();
        $this->regenerateCache();
        $this->loadRandomImagesTmpInputId();
        $this->reset('imagesTmp');
        $this->dispatch('alert', 'success', 'Galería actualizadas con éxito');
        $this->dispatch('render');
    }
    public function saveImages() {
        if ($this->imagesTmp) {
            foreach ($this->imagesTmp as $imageTmp) {
                $url = $imageTmp->store('gallery/'.strtolower($this->gallery->module));
                imagesManager($url, 800, $this->gallery);
            }
        }
    }
    public function removeImageTemp($key) {
        if (array_splice($this->imagesTmp, $key, 1)) {
            $this->dispatch('alert', 'success', __('Image successfully deleted'));
        }
    }
    public function removeImage(Image $image) {
        $image->delete();
        $this->regenerateCache();
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }
    protected function loadRandomImagesTmpInputId() {
        $this->imagesTmpInputId = rand(1, 1000).'-'.$this->gallery->id;
    }
    public function regenerateCache() {
        Gallery::regenerateCache();
    }
}

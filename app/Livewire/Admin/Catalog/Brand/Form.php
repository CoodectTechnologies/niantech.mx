<?php

namespace App\Livewire\Admin\Catalog\Brand;

use App\Models\ProductBrand;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $brand;
    public $method;
    public $imageTmp;

    protected function rules() {
        return [
            'brand.name' => 'required',
            'imageTmp' => 'image|nullable',
        ];
    }
    public function mount(ProductBrand $brand, $method) {
        $this->brand = $brand;
        $this->method = $method;
    }
    public function render() {
        return view('livewire.admin.catalog.brand.form');
    }
    public function store() {
        $this->validate();
        $this->brand->save();
        $this->saveImage();
        $this->brand = new ProductBrand;
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->brand->update();
        $this->saveImage();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
    public function saveImage() {
        if ($this->imageTmp) {
            $url = $this->imageTmp->store('catalog/brand');
            imageManager($url, 200, $this->brand);
        }
    }
    public function removeImage() {
        if ($this->brand->image) {
            if (Storage::exists($this->brand->image->url)) {
                Storage::delete($this->brand->image->url);
            }
            $this->brand->image()->delete();
            $this->brand->image = null;
        }
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }
}

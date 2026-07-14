<?php

namespace App\Livewire\Admin\Service;

use App\Models\Image;
use App\Models\Service;
use App\Traits\LivewireTranslatable;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use LivewireTranslatable;
    use WithFileUploads;

    public $method;
    public $service;
    public $imageTmp;
    public $imagesTmp = [];
    public $imagesTmpInputId;

    // Tools
    public $order;
    protected $listeners = ['render'];

    public function mount(Service $service, $method) {
        $this->service = $service;
        $this->method = $method;
        $this->order = $service->order;
        $this->loadTranslations($this->service);
    }
    protected function rules() {
        return [
            'translations.name.'.translatable() => 'required|unique_translation:services,name,'.$this->service->id,
            'translations.fragment.'.translatable() => 'required',
            'translations.body.'.translatable() => 'required',
            'service.order' => 'required',
            'translations.meta_title.'.translatable() => 'nullable',
            'translations.meta_description.'.translatable() => 'nullable',
            'translations.meta_keywords.'.translatable() => 'nullable',
            'imageTmp' => $this->service->image ? 'image|nullable' : 'image|required',
        ];
    }
    public function render() {
        $this->loadLastOrder();
        $serviceImages = $this->service->images()->orderBy('id', 'desc')->get();

        return view('livewire.admin.service.form', compact('serviceImages'));
    }
    public function store() {
        $this->validate();
        $this->reOrder();
        $this->saveTranslations($this->service);
        $this->service->save();
        $this->saveImage();
        $this->saveImages();
        $this->regenerateCache();
        session()->flash('alert', __('Registration successfully added'));
        session()->flash('alert-type', 'success');

        return redirect()->route('admin.service.show', $this->service);
    }
    public function update() {
        $this->validate();
        $this->reOrder();
        $this->saveTranslations($this->service);
        $this->service->update();
        $this->saveImage();
        $this->saveImages();
        $this->regenerateCache();
        session()->flash('alert', __('Registration successfully updated'));
        session()->flash('alert-type', 'success');

        return redirect()->route('admin.service.show', $this->service);
    }
    public function saveImage() {
        if ($this->imageTmp) {
            $url = $this->imageTmp->store('service');
            imageManager($url, 900, $this->service);
        }
    }
    protected function saveImages() {
        if ($this->imagesTmp) {
            foreach ($this->imagesTmp as $imgTmp) {
                $url = $imgTmp->store('services');
                imagesManager($url, 600, $this->service);
            }
        }
    }
    public function removeImageTemp($key) {
        if (array_splice($this->imagesTmp, $key, 1)) {
            $this->dispatch('alert', 'success', __('Image successfully deleted'));
        }
    }
    public function removeImageMain() {
        if ($this->service->image) {
            if (Storage::exists($this->service->image->url)) {
                Storage::delete($this->service->image->url);
            }
            $this->service->image()->delete();
            $this->service->image = null;
        }
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }
    public function removeImage(Image $image) {
        try {
            if (Storage::exists($image->url)) {
                Storage::delete($image->url);
            }
            $image->delete();
            $this->dispatch('alert', 'success', __('Image successfully deleted'));
        } catch (Exception $e) {
            $this->dispatch('alert', 'warning', $e->getMessage());
        }
    }
    private function reOrder() {
        if ($this->order != $this->service->order) {
            $servicesToOrder = Service::where('order', '>=', $this->service->order)->get();
            foreach ($servicesToOrder as $bannerToOrder) {
                $bannerToOrder->order = $bannerToOrder->order + 1;
                $bannerToOrder->update();
            }
        }
    }
    private function loadLastOrder() {
        if (! $this->service->order) {
            $lastOrder = Service::latest('order')->first();
            if ($lastOrder) {
                $this->service->order = ($lastOrder->order + 1);
            } else {
                $this->service->order = 1;
            }
        }
    }
    protected function loadRandomImagesTmpInputId() {
        $this->imagesTmpInputId = rand(1, 1000).'-'.$this->service->id;
    }
    private function regenerateCache() {
        Service::regenerateCache();
    }
}

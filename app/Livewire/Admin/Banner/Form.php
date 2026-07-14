<?php

namespace App\Livewire\Admin\Banner;

use App\Models\Banner;
use App\Models\ModuleWeb;
use App\Traits\LivewireTranslatable;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use LivewireTranslatable;
    use WithFileUploads;

    public $banner;
    public $method;
    public $imageTmp;
    public $videoTmp;

    // Tools
    public $order;

    protected function rules() {
        return [
            'banner.module_web_id' => 'required|exists:module_webs,id',
            'banner.order' => 'nullable',
            'banner.type' => 'required',
            'translations.title.'.translatable() => 'nullable',
            'translations.subtitle.'.translatable() => 'nullable',
            'translations.description.'.translatable() => 'nullable',
            'banner.btn_url' => 'nullable',
            'translations.btn_text.'.translatable() => 'nullable',
            'banner.color' => 'nullable',
            'banner.overlay' => 'nullable',
        ];
    }
    public function mount(Banner $banner, $method) {
        $this->banner = $banner;
        $this->method = $method;
        $this->order = $banner->order;
        $banner->type = $banner->exists ? $banner->type : 'Imagen';
        $this->loadLastOrder();
        $this->loadTranslations($this->banner);
    }
    public function render() {
        $modulesWeb = ModuleWeb::orderBy('name')->get();

        return view('livewire.admin.banner.form', compact('modulesWeb'));
    }
    public function store() {
        $this->validate();
        $this->validateType();
        $this->reOrder();
        $this->saveVideo();
        $this->saveTranslations($this->banner);
        $this->banner->save();
        $this->saveImage();
        $this->regenerateCache();
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->validateType();
        $this->reOrder();
        $this->saveVideo();
        $this->saveTranslations($this->banner);
        $this->banner->update();
        $this->saveImage();
        $this->regenerateCache();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
    private function validateType() {
        if ($this->banner->type == 'Imagen') {
            if (! $this->banner->image) {
                $this->validate(['imageTmp' => 'required']);
            }
        }
        if ($this->banner->type == 'Video') {
            if (! $this->banner->video) {
                $this->validate(['videoTmp' => 'required']);
            }
        }
    }
    public function saveImage() {
        if ($this->imageTmp) {
            $url = $this->imageTmp->store('banner/'.strtolower($this->banner->module));
            imageManager($url, 1920, $this->banner);
        }
    }
    public function saveVideo() {
        if ($this->videoTmp) {
            $url = $this->videoTmp->store('banner/'.strtolower($this->banner->module));
            if ($this->banner->video) {
                if (Storage::exists($this->banner->video)) {
                    Storage::delete($this->banner->video);
                }
            }
            $this->banner->video = $url;
        }
    }
    public function removeImage() {
        if ($this->banner->image) {
            if (Storage::exists($this->banner->image->url)) {
                Storage::delete($this->banner->image->url);
            }
            $this->banner->image()->delete();
            $this->banner->image = null;
        }
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }
    public function removeVideo() {
        if ($this->banner->video) {
            if (Storage::exists($this->banner->video)) {
                Storage::delete($this->banner->video);
            }
            $this->banner->video = null;
        }
        $this->reset('videoTmp');
        $this->dispatch('alert', 'success', __('Video successfully deleted'));
    }
    private function reOrder() {
        if ($this->order != $this->banner->order) {
            $reOrder = Banner::where('order', $this->banner->order)->where('module_web_id', $this->banner->module_web_id)->where('id', '<>', $this->banner->id)->first();
            if ($reOrder) {
                $bannersToOrders = Banner::where('order', '>=', $this->banner->order);
                if ($this->banner->exists) {
                    $bannersToOrders = $bannersToOrders->where('id', '<>', $this->banner->id)->where('module_web_id', $this->banner->module_web_id);
                }
                $bannersToOrders->increment('order');
            }
        }
    }
    public function loadLastOrder($moduleWebId = null) {
        if ($this->banner->module_web_id && ! $this->banner->exists) {
            $modulesWebIdTMP = $this->banner->module_web_id;
            if ($moduleWebId) {
                $modulesWebIdTMP = $moduleWebId;
            }
            $lastOrder = Banner::latest('order');
            if ($modulesWebIdTMP) {
                $lastOrder = $lastOrder->where('module_web_id', $modulesWebIdTMP);
            }
            $lastOrder = $lastOrder->first();
            if ($lastOrder) {
                $this->banner->order = ($lastOrder->order + 1);
            } else {
                $this->banner->order = 1;
            }
        }
    }
    private function regenerateCache() {
        Banner::regenerateCache();
    }
}

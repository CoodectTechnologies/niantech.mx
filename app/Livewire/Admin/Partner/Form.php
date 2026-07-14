<?php

namespace App\Livewire\Admin\Partner;

use App\Models\Partner;
use App\Traits\LivewireTranslatable;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use LivewireTranslatable;
    use WithFileUploads;

    public $partner;
    public $method;
    public $imageTmp;

    protected function rules() {
        return [
            'translations.name.'.translatable() => 'required',
            'imageTmp' => $this->partner->image ? 'image|nullable' : 'image|required',
        ];
    }
    public function mount(Partner $partner, $method) {
        $this->partner = $partner;
        $this->method = $method;
        $this->loadTranslations($this->partner);
    }
    public function render() {
        return view('livewire.admin.partner.form');
    }
    public function store() {
        $this->validate();
        $this->saveTranslations($this->partner);
        $this->partner->save();
        $this->saveImage();
        $this->partner = new Partner;
        $this->regenerateCache();
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->saveTranslations($this->partner);
        $this->partner->update();
        $this->saveImage();
        $this->regenerateCache();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
    public function saveImage() {
        if ($this->imageTmp) {
            $url = $this->imageTmp->store('partner');
            imageManager($url, 200, $this->partner);
        }
    }
    public function removeImage() {
        if ($this->partner->image) {
            if (Storage::exists($this->partner->image->url)) {
                Storage::delete($this->partner->image->url);
            }
            $this->partner->image()->delete();
            $this->partner->image = null;
        }
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }
    private function regenerateCache() {
        Partner::regenerateCache();
    }
}

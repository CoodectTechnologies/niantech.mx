<?php

namespace App\Livewire\Admin\Testimony;

use App\Models\Testimony;
use App\Traits\LivewireTranslatable;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use LivewireTranslatable;
    use WithFileUploads;

    public $testimony;
    public $method;
    public $imageTmp;

    protected function rules() {
        return [
            'translations.name.'.translatable() => 'required',
            'translations.position.'.translatable() => 'required',
            'translations.body.'.translatable() => 'required',
            'imageTmp' => $this->testimony->image ? 'image|nullable' : 'image|required',
        ];
    }
    public function mount(Testimony $testimony, $method) {
        $this->testimony = $testimony;
        $this->method = $method;
        $this->loadTranslations($this->testimony);
    }
    public function render() {
        return view('livewire.admin.testimony.form');
    }
    public function store() {
        $this->validate();
        $this->saveTranslations($this->testimony);
        $this->testimony->save();
        $this->saveImage();
        $this->regenerateCache();
        $this->testimony = new Testimony;
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->saveTranslations($this->testimony);
        $this->testimony->update();
        $this->saveImage();
        $this->regenerateCache();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
    public function saveImage() {
        if ($this->imageTmp) {
            $url = $this->imageTmp->store('testimony');
            imageManager($url, 200, $this->testimony);
        }
    }
    public function removeImage() {
        if ($this->testimony->image) {
            if (Storage::exists($this->testimony->image->url)) {
                Storage::delete($this->testimony->image->url);
            }
            $this->testimony->image()->delete();
            $this->testimony->image = null;
        }
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }
    public function regenerateCache() {
        Testimony::regenerateCache();
    }
}

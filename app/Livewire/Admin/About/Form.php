<?php

namespace App\Livewire\Admin\About;

use App\Models\About;
use App\Traits\LivewireTranslatable;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use LivewireTranslatable;
    use WithFileUploads;

    public $about;
    public $method;
    public $imageTmp;
    public $image2Tmp;

    protected function rules() {
        return [
            'translations.title.'.translatable() => 'required',
            'translations.information.'.translatable() => 'required',
            'translations.mission.'.translatable() => 'required',
            'translations.vision.'.translatable() => 'required',
            'translations.values.'.translatable() => 'required',
        ];
    }
    public function mount(About $about) {
        $this->about = $about;
        $this->loadTranslations($this->about);
    }
    public function render() {
        return view('livewire.admin.about.form');
    }
    public function save() {
        $this->validate();
        $this->saveTranslations($this->about);
        $this->about->save();
        $this->saveImage();
        $this->saveImage2();
        $this->regenerateCache();
        if($this->about->wasRecentlyCreated):
            $this->about = new About;
            $this->reset('imageTmp', 'image2Tmp', 'translations');
        endif;
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function saveImage() {
        if ($this->imageTmp) {
            $url = $this->imageTmp->store('about');
            imageManager($url, 900, $this->about);
        }
    }
    public function saveImage2() {
        if ($this->image2Tmp) {
            $url = $this->image2Tmp->store('about');
            imageManager($url, 900, $this->about, 'image2');
        }
    }
    public function removeImage() {
        if ($this->about->image) {
            if (Storage::exists($this->about->image->url)) {
                Storage::delete($this->about->image->url);
            }
            $this->about->image()->delete();
            $this->about->image = null;
        }
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }
    public function removeImage2() {
        if ($this->about->image2) {
            if (Storage::exists($this->about->image2->url)) {
                Storage::delete($this->about->image2->url);
            }
            $this->about->image2()->delete();
            $this->about->image2 = null;
        }
        $this->reset('image2Tmp');
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }
    private function regenerateCache() {
        About::regenerateCache();
    }
}

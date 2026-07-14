<?php

namespace App\Livewire\Admin\Portfolio;

use App\Models\Image;
use App\Models\Portfolio;
use App\Models\Service;
use App\Traits\LivewireTranslatable;
use Exception;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use LivewireTranslatable;
    use WithFileUploads;

    public $method;
    public $project;
    public $imageTmp;
    public $imagesTmp = [];
    public $imagesTmpInputId;
    protected $listeners = ['render'];

    public function mount(Portfolio $project, $method) {
        $this->project = $project;
        $this->method = $method;
        $this->loadRandomImagesTmpInputId();
        $this->loadTranslations($this->project);
    }
    protected function rules() {
        return [
            'project.service_id' => 'nullable|exists:services,id',
            'translations.name.'.translatable() => 'required|unique_translation:portfolios,name,'.$this->project->id,
            'project.client' => 'nullable',
            'translations.fragment.'.translatable() => 'required',
            'translations.body.'.translatable() => 'nullable',
            'project.link' => 'nullable|url',
            'project.date' => 'nullable|date',
            'translations.meta_title.'.translatable() => 'nullable',
            'translations.meta_description.'.translatable() => 'nullable',
            'translations.meta_keywords.'.translatable() => 'nullable',
            'imageTmp' => $this->project->image ? 'image|nullable' : 'image|required',
        ];
    }
    public function render() {
        $services = Service::orderBy('id', 'desc')->get();
        $projectImages = $this->project->images()->orderBy('id', 'desc')->get();

        return view('livewire.admin.portfolio.form', compact('services', 'projectImages'));
    }
    public function store() {
        $this->validate();
        $this->saveTranslations($this->project);
        $this->project->save();
        $this->saveImage();
        $this->saveImages();
        $this->regenerateCache();
        Session::flash('alert', __('Registration successfully added'));
        Session::flash('alert-type', 'success');

        return Redirect::route('admin.portfolio.show', $this->project);
    }
    public function update() {
        $this->validate();
        $this->saveTranslations($this->project);
        $this->project->update();
        $this->saveImage();
        $this->saveImages();
        $this->regenerateCache();
        Session::flash('alert', __('Registration successfully updated'));
        Session::flash('alert-type', 'success');

        return Redirect::route('admin.portfolio.show', $this->project);
    }
    protected function loadRandomImagesTmpInputId() {
        $this->imagesTmpInputId = rand(1, 1000).'-'.$this->project->id;
    }
    public function saveImage() {
        if ($this->imageTmp) {
            $url = $this->imageTmp->store('portfolio');
            imageManager($url, 900, $this->project);
        }
    }
    protected function saveImages() {
        if ($this->imagesTmp) {
            foreach ($this->imagesTmp as $imgTmp) {
                $url = $imgTmp->store('portfolio');
                imagesManager($url, 600, $this->project);
            }
        }
    }
    public function removeImageTemp($key) {
        if (array_splice($this->imagesTmp, $key, 1)) {
            $this->dispatch('alert', 'success', __('Image successfully deleted'));
        }
    }
    public function removeImageMain() {
        if ($this->project->image) {
            if (Storage::exists($this->project->image->url)) {
                Storage::delete($this->project->image->url);
            }
            $this->project->image()->delete();
            $this->project->image = null;
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
    private function regenerateCache() {
        Portfolio::regenerateCache();
    }
}

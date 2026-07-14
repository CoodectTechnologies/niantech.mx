<?php

namespace App\Livewire\Admin\Package\Feature;

use App\Models\PackageFeature;
use App\Traits\LivewireTranslatable;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Form extends Component
{
    use LivewireTranslatable;

    public $packageFeature;
    public $method;

    protected function rules() {
        return [
            'translations.name.'.translatable() => 'required',
        ];
    }
    public function mount(PackageFeature $packageFeature, $method) {
        $this->packageFeature = $packageFeature;
        $this->method = $method;
        $this->loadTranslations($this->packageFeature);
    }
    public function render() {
        return view('livewire.admin.package.feature.form');
    }
    public function store() {
        $this->validate();
        $this->saveTranslations($this->packageFeature);
        $this->packageFeature->save();
        $this->packageFeature = new PackageFeature;
        Cache::forget('packageFeatures');
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->saveTranslations($this->packageFeature);
        $this->packageFeature->update();
        Cache::forget('packageFeatures');
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
}

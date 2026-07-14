<?php

namespace App\Livewire\Admin\Package\Package;

use App\Models\Package;
use App\Models\PackageFeature;
use App\Traits\LivewireTranslatable;
use Livewire\Component;

class Form extends Component
{
    use LivewireTranslatable;

    public $package;
    public $method;

    // Tools
    public $order;
    public $packageFeatureArray = [];

    protected function rules() {
        return [
            'package.order' => 'required',
            'translations.title.'.translatable() => 'required',
            'translations.subtitle.'.translatable() => 'nullable',
            'package.price' => 'nullable',
        ];
    }
    public function mount(Package $package, $method) {
        $this->package = $package;
        $this->method = $method;
        $this->order = $package->order;
        $this->packageFeatureArray = $this->package->packageFeatures()->pluck('package_feature_id')->toArray();
        $this->loadTranslations($this->package);
    }
    public function render() {
        $this->loadLastOrder();
        $packageFeatures = PackageFeature::orderBy('id', 'desc')->get();

        return view('livewire.admin.package.package.form', compact('packageFeatures'));
    }
    public function hydrate() {
        $this->dispatch('renderJs');
    }
    public function store() {
        $this->validate();
        $this->reOrder();
        $this->saveTranslations($this->package);
        $this->package->save();
        $this->savePackageFeatures();
        $this->package = new Package;
        $this->reset('packageFeatureArray');
        $this->regenerateCache();
        $this->dispatch('alert', 'success', 'Paquete agregado con éxito');
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->reOrder();
        $this->saveTranslations($this->package);
        $this->package->update();
        $this->savePackageFeatures();
        $this->regenerateCache();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
    public function savePackageFeatures() {
        $this->package->packageFeatures()->sync($this->packageFeatureArray);
    }
    private function reOrder() {
        if ($this->order != $this->package->order) {
            $packagesToOrder = Package::where('order', '>=', $this->package->order)->get();
            foreach ($packagesToOrder as $packageToOrder) {
                $packageToOrder->order = $packageToOrder->order + 1;
                $packageToOrder->update();
            }
        }
    }
    private function loadLastOrder() {
        if (! $this->package->order) {
            $lastOrder = Package::latest('order')->first();
            if ($lastOrder) {
                $this->package->order = ($lastOrder->order + 1);
            } else {
                $this->package->order = 1;
            }
        }
    }
    private function regenerateCache() {
        Package::regenerateCache();
    }
}

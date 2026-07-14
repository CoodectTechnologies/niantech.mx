<?php

namespace App\Livewire\Admin\Catalog\Gender;

use App\Models\ProductGender;
use App\Traits\LivewireTranslatable;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use LivewireTranslatable;
    use WithFileUploads;

    public $gender;
    public $method;

    protected function rules() {
        return [
            'translations.name.'.translatable() => 'required',
        ];
    }
    public function mount(ProductGender $gender, $method) {
        $this->gender = $gender;
        $this->method = $method;
        $this->loadTranslations($this->gender);
    }
    public function render() {
        return view('livewire.admin.catalog.gender.form');
    }
    public function store() {
        $this->validate();
        $this->saveTranslations($this->gender);
        $this->gender->save();
        $this->gender = new ProductGender;
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    public function update() {
        $this->validate();
        $this->saveTranslations($this->gender);
        $this->gender->update();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
}

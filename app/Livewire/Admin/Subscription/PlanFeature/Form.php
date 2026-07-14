<?php

namespace App\Livewire\Admin\Subscription\PlanFeature;

use App\Models\PlanFeature;
use App\Traits\LivewireTranslatable;
use Livewire\Component;

class Form extends Component
{
    use LivewireTranslatable;

    public $planFeature;

    public function mount(PlanFeature $planFeature) {
        $this->planFeature = $planFeature;
        $this->loadTranslations($this->planFeature);
    }
    protected function rules() {
        return [
            'translations.name.'.translatable() => 'required',
        ];
    }
    protected function messages() {
        return [
            'translations.name.required' => __('El nombre de la característica es requerida'),
        ];
    }
    public function render() {
        return view('livewire.admin.subscription.plan-feature.form');
    }
    public function save() {
        $this->validate();
        $this->saveTranslations($this->planFeature);
        $this->planFeature->save();
        $this->dispatch('alert', 'success', __('Registro guardado'));
        $this->dispatch('render');
        if ($this->planFeature->wasRecentlyCreated) {
            $this->planFeature = new PlanFeature;
            $this->loadTranslations($this->planFeature);
        }
    }
}

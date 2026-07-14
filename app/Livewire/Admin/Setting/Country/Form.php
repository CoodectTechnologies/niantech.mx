<?php

namespace App\Livewire\Admin\Setting\Country;

use App\Models\Country;
use Livewire\Component;

class Form extends Component
{
    public $country;
    public $method;

    public function mount(Country $country, $method) {
        $this->country = $country;
        $this->method = $method;
    }
    protected function rules() {
        return [
            'country.code' => 'required|unique:countries,code,'.$this->country->id,
            'country.name' => 'required|unique:countries,name,'.$this->country->id,
            'country.status' => 'required',
            'country.phonecode' => 'nullable',
            'country.default' => 'nullable',
        ];
    }
    public function render() {
        return view('livewire.admin.setting.country.form');
    }
    public function store() {
        $this->validate();
        if (! $this->validateDefault()) {
            return false;
        }
        $this->country->save();
        $this->saveDefault();
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
        $this->country = new Country;
    }
    public function update() {
        $this->validate();
        if (! $this->validateDefault()) {
            return false;
        }
        $this->country->update();
        $this->saveDefault();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
    public function validateDefault() {
        $validateDefault = true;
        if ($this->country->exists) {
            $countryDefault = Country::where('default', true)->where('id', $this->country->id)->first();
            if (
                $countryDefault &&
                $countryDefault->default &&
                ! $this->country->default
            ) {
                $this->dispatch('alert', 'warning', __('You cannot remove the default of this country, you must default to another country'));
                $validateDefault = false;
            }
        }

        return $validateDefault;
    }
    public function saveDefault() {
        if (! Country::count()) {
            $this->country->default = true;
            $this->country->active = true;
        } else {
            if ($this->country->default) {
                Country::query()->where('id', '<>', $this->country->id)->update([
                    'default' => false,
                ]);
            }
        }
    }
}

<?php

namespace App\Livewire\Admin\Setting\Currency;

use App\Models\Currency;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Form extends Component
{
    public $currency;
    public $method;

    public function mount(Currency $currency, $method) {
        $this->currency = $currency;
        $this->method = $method;
        $this->currency->active = $this->currency->active ?? true;
    }
    protected function rules() {
        return [
            'currency.name' => 'required|unique:currencies,name,'.$this->currency->id,
            'currency.code' => 'required|unique:currencies,code,'.$this->currency->id,
            'currency.value' => 'required',
            'currency.symbol' => 'required',
            'currency.default' => 'required',
            'currency.active' => 'required',
        ];
    }
    public function render() {
        return view('livewire.admin.setting.currency.form');
    }
    public function store() {
        $this->validate();
        if (
            $this->currency->default &&
            ! $this->currency->active
        ) {
            $this->dispatch('alert', 'warning', __('It is not possible to deactivate this currency as it is set as default.'));

            return false;
        }
        $this->validateDefault();
        $this->currency->save();
        $this->saveCache();
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
        $this->currency = new Currency;
    }
    public function update() {
        $this->validate();
        if (
            $this->currency->default &&
            ! $this->currency->active
        ) {
            $this->dispatch('alert', 'warning', __('It is not possible to deactivate this currency as it is set as default.'));

            return false;
        }
        $this->validateDefault();
        $this->currency->update();
        $this->saveCache();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');
    }
    private function validateDefault() {
        if (! Currency::count()) {
            $this->currency->default = true;
        } else {
            if ($this->currency->default) {
                Currency::query()->where('id', '<>', $this->currency->id)->update(['default' => false]);
            }
        }
    }
    private function saveCache() {
        $currencies = Currency::validate()->orderBy('code')->get();
        Cache::forever('currencies', $currencies);
    }
}

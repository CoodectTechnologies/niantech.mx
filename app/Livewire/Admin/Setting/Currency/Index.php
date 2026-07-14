<?php

namespace App\Livewire\Admin\Setting\Currency;

use App\Models\Currency;
use Exception;
use Livewire\Component;

class Index extends Component
{
    public $search;
    protected $queryString = ['search'];
    protected $listeners = ['render'];

    public function render() {
        $currencies = Currency::orderBy('id');
        if ($this->search) {
            $currencies = $currencies->where('code', 'LIKE', "%{$this->search}%")->orWhere('name', 'LIKE', "%{$this->search}%");
        }
        $currencies = $currencies->get();

        return view('livewire.admin.setting.currency.index', compact('currencies'));
    }
    public function destroy(Currency $currency) {
        try {
            if (Currency::count() == 1) {
                $this->dispatch('alert', 'warning', 'No puedes eliminar todas las monedas, deberá de existir minimo 1');

                return;
            }
            if ($currency->default) {
                $this->dispatch('alert', 'warning', 'No puedes eliminar esta moneda, primero deberás de asignar a otra moneda la opcion por default');

                return;
            }
            $currency->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}

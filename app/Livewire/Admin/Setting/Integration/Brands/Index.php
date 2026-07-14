<?php

namespace App\Livewire\Admin\Setting\Integration\Brands;

use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render() {
        return view('livewire.admin.setting.integration.brands.index');
    }
}

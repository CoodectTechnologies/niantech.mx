<?php

namespace App\Livewire\Admin\Setting\AccessPayment\Openpaybbva;

use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render() {
        return view('livewire.admin.setting.access-payment.openpaybbva.index');
    }
}

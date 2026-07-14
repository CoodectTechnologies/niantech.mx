<?php

namespace App\Livewire\Ecommerce\Layouts;

use Livewire\Component;

class Compare extends Component
{
    protected $listeners = ['render'];

    public function render() {
        return view('livewire.ecommerce.layouts.compare');
    }
}

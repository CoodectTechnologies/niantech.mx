<?php

namespace App\Livewire\Ecommerce\Layouts;

use Livewire\Component;

class Wishlist extends Component
{
    protected $listeners = ['render'];

    public function render() {
        return view('livewire.ecommerce.layouts.wishlist');
    }
}

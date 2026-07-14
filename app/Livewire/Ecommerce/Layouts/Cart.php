<?php

namespace App\Livewire\Ecommerce\Layouts;

use App\Services\Cart\CartService;
use Livewire\Component;

class Cart extends Component
{
    protected $listeners = ['render'];

    public function render() {
        return view('livewire.ecommerce.layouts.cart');
    }
    public function removeProduct($rowId) {
        CartService::destroy($rowId);
    }
}

<?php

namespace App\Livewire\Ecommerce\Checkout;

use App\Models\Order;
use Livewire\Component;

class Complete extends Component
{
    public $order;

    public function mount(Order $order) {
        $this->order = $order;
        $this->order->load(['products', 'address.state.country', 'billingAddress.state.country']);
    }
    public function render() {
        return view('livewire.ecommerce.checkout.complete');
    }
}

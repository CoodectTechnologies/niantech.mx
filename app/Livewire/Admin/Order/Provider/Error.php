<?php

namespace App\Livewire\Admin\Order\Provider;

use App\Models\Order;
use App\Services\Synchronizers\Order\OrderController as OrderControllerProvider;
use Livewire\Component;

class Error extends Component
{
    protected $listeners = ['render'];
    public $order;

    public function mount(Order $order) {
        $this->order = $order;
        $this->order->load('orderProviderErrors.productWarehouse');
    }
    public function render() {
        $orderProviderErrors = $this->order->orderProviderErrors()->get();

        return view('livewire.admin.order.provider.error', compact('orderProviderErrors'));
    }
    public function resendOrdersProvider() {
        OrderControllerProvider::create($this->order);
        $this->dispatch('render');
    }
}

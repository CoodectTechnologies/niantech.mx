<?php

namespace App\Livewire\Admin\Order\Provider;

use App\Models\Order;
use App\Models\OrderProductWarehouse;
use App\Models\OrderProvider;
use App\Models\ProductWarehouse;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];
    public $order;
    public $productWarehouse;

    public function mount(Order $order, ProductWarehouse $productWarehouse) {
        $this->order = $order;
        $this->productWarehouse = $productWarehouse;
    }
    public function render() {
        $orderProviders = $this->getOrderProviders();
        $orderProductWarehouses = $this->getOrderProductWarehouses();

        return view('livewire.admin.order.provider.index', compact('orderProviders', 'orderProductWarehouses'));
    }
    private function getOrderProviders() {
        return OrderProvider::where('order_id', $this->order->id)->where('product_warehouse_id', $this->productWarehouse->id)->get();
    }
    private function getOrderProductWarehouses() {
        return OrderProductWarehouse::query()
            ->with(['orderProduct.product'])
            ->where('product_warehouse_id', $this->productWarehouse->id)
            ->whereHas('orderProduct.order', function ($query) {
                $query->where('id', $this->order->id);
            })->get();
    }
}

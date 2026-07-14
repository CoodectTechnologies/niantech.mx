<?php

namespace App\Livewire\Admin\Order\Order;

use App\Models\Order;
use App\Models\ProductWarehouse;
use Livewire\Component;

class Show extends Component
{
    protected $listeners = ['render', 'refresh'];
    public $order;
    public $paymentStatus;
    public $status;

    protected function rules() {
        return [
            'order.payment_status' => 'required',
            'order.status' => 'required',
        ];
    }
    public function mount(Order $order) {
        $this->order = $order;
        $this->paymentStatus = $order->payment_status;
        $this->status = $order->status;
        $this->order->load(['products', 'address.state.country']);
    }
    public function render() {
        return view('livewire.admin.order.order.show');
    }
    public function getWarehousesGroup() {
        $productWarehousesIds = $this->order->orderProductWarehouses()->where('apply_provider', true)->pluck('product_warehouse_id')->toArray();
        $productWarehousesGroup = ProductWarehouse::whereIn('id', $productWarehousesIds)->get();

        return $productWarehousesGroup;
    }
    public function refresh() {
        $this->order = Order::where('id', $this->order->id)->first();
        $this->order->load(['products', 'address.state.country']);
    }
}

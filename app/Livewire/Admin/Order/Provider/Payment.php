<?php

namespace App\Livewire\Admin\Order\Provider;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderProviderPayment;
use App\Services\Synchronizers\Order\VoucherController;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Payment extends Component
{
    use WithFileUploads;

    public $order;
    public $orderProviderPayment;
    public $total = 0;
    public $voucherTMP;

    public function rules() {
        return [
            'orderProviderPayment.account' => 'required',
            'orderProviderPayment.amount' => 'required',
            'voucherTMP' => $this->orderProviderPayment->voucher ? 'nullable' : 'required',
        ];
    }
    public function mount(Order $order) {
        $this->order = $order;
        $this->order->load(['orderProductWarehouses.orderProduct', 'orderProviderPayment', 'orderProviders']);
        $this->orderProviderPayment = $order->orderProviderPayment ?? new OrderProviderPayment;
        $this->loadTotal();
        // $this->orderProviderPayment->amount = $this->orderProviderPayment->amount ?? $this->total;
    }
    public function render() {
        return view('livewire.admin.order.provider.payment');
    }
    public function save() {
        $this->validate();
        if ($this->voucherTMP) {
            $url = $this->voucherTMP->store('order/provider/'.$this->order->number.'/voucher');
            $originalFileName = $this->voucherTMP->getClientOriginalName();
            $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);
            $this->orderProviderPayment->voucher = $url;
            $this->orderProviderPayment->voucher_ext = $extension;
        }
        $this->orderProviderPayment->order_id = $this->order->id;
        $this->orderProviderPayment->order_provider_ids = implode(', ', $this->order->orderProviders->pluck('provider_id')->toArray());
        $this->orderProviderPayment->save();
        VoucherController::create($this->orderProviderPayment);
        $this->dispatch('render')->to('admin.order.provider.index');
    }
    private function loadTotal() {
        $this->total = 0;
        $orderProducts = OrderProduct::with('product')->where('order_id', $this->order->id)->whereHas('orderProductWarehouses', function ($query) {
            $query->where('apply_provider', true);
        })->get();
        foreach ($orderProducts as $orderProduct) {
            $this->total += $orderProduct->subtotal;
        }
        $this->total = floor($this->total);
    }
    public function removeVoucher() {
        if ($this->orderProviderPayment->voucher) {
            if (Storage::exists($this->orderProviderPayment->voucher)) {
                Storage::delete($this->orderProviderPayment->voucher);
            }
            $this->orderProviderPayment->voucher = null;
            $this->orderProviderPayment->voucher_ext = null;
        }
        $this->reset('voucherTMP');
        $this->dispatch('alert', 'success', __('Voucher successfully deleted'));
    }
}

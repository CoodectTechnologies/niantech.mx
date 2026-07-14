@if($order->invoice)
    @livewire('admin.invoice.invoice.show', ['invoice' => $order->invoice], key('invoice-' . $order->id))
@endif

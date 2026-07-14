<div class="mb-5">
    <div class="">
        <h3 class="">{{ __('Shipping address') }}</h3>
        <address>
            <strong>{{ $order->address->state->country->name }}.</strong><br>
            {{ $order->address->state->name }}, {{ $order->address->municipality }}<br>
            {{ $order->address->colony }}, {{ $order->address->street }}, Código postal:
            {{ $order->address->zip_code }}<br>
            <abbr title="Phone">{{ __('Phone') }}: {{ $order->address->phone }}
            </abbr> <br>
            <abbr title="Email">{{ __('Email') }}: {{ $order->address->email }}
            </abbr>
        </address>
    </div>
</div>
@if($order->billingAddress)
    <div class="mb-5">
        <div class="order-summary-wrapper sticky-sidebar">
            <h3 class="">{{ __('Billing address') }}</h3>
            <address>
                <strong>{{ $order->billingAddress->state->country->name }}.</strong><br>
                {{ $order->billingAddress->vat }}, {{ $order->billingAddress->state->name }},
                {{ $order->billingAddress->municipality }}<br>
                {{ $order->billingAddress->colony }}, {{ $order->billingAddress->street }}, Código postal:
                {{ $order->billingAddress->zip_code }}<br>
                <abbr title="Phone">{{ __('Phone') }}: {{ $order->billingAddress->phone }}
                </abbr> <br>
                <abbr title="Email">{{ __('Email') }}: {{ $order->billingAddress->email }}
                </abbr>
            </address>
        </div>
    </div>
@endif

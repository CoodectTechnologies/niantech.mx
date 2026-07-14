<div class="order">
    <!-- Start of PageContent -->
    <div class="page-content mb-10 pb-2">
        <div class="container">
            <div class="order-success text-center font-weight-bolder">
                {{ __('Thank you. Your order has been received.') }}
            </div>
            <!-- End of Order Success -->

           <div class="card">
                <div class="card-body">
                    <ul class="order-view list-style-none">
                        <li>
                            <label>{{ __('Order number') }}</label>
                            <strong>{{ $order->number }}</strong>
                        </li>
                        <li>
                            <label>{{ __('Status') }}</label>
                            <strong>{!! $order->statusToString() !!}</strong>
                        </li>
                        <li>
                            <label>{{ __('Date') }}</label>
                            <strong>{{ $order->dateToString() }}</strong>
                        </li>
                        <li>
                            <label>{{ __('Total') }}</label>
                            <strong>{{ $order->totalToString() }}</strong>
                        </li>
                        <li>
                            <label>{{ __('Payment method') }}</label>
                            <strong>{{ $order->payment_method ?? 'Sin método de pago' }}</strong>
                        </li>
                        <li>
                            <label>{{ __('Payment status') }}</label>
                            <strong>{!! $order->paymentStatusToString() !!}</strong>
                        </li>
                    </ul>
                </div>
           </div>
            <!-- End of Order View -->

            <div class="order-details-wrapper mb-5">
                <table class="order-table">
                    <thead>
                        <tr>
                            <th class="text-dark">{{ __('Products') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderProducts as $orderProduct)
                            <tr>
                                <td>
                                    <a href="{{ route('ecommerce.product.show', $orderProduct->product) }}">
                                        {{ $orderProduct->product->getName() }}</a>&nbsp;<strong>x
                                        {{ $orderProduct->quantity }}</strong><br>
                                        @if($orderProduct->type == 'Digital')
                                            <span>{{ __('Type') }}: {{ $orderProduct->product->type }}</span> <br>
                                        @endif
                                        @if($orderProduct->productVariant)
                                            @foreach($orderProduct->productVariant->productOptionValues as $optionValue)
                                                <span class="text-muted">
                                                    {{ $optionValue->productOption->name }}: {{ $optionValue->value }}
                                                </span><br>
                                            @endforeach
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    ${{ number_format($orderProduct->subtotal, 2) }} {{ $order->currency }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>{{ __('Subtotal') }}:</th>
                            <td>{{ $order->subtotalToString() }}</td>
                        </tr>
                        @if($order->tax)
                            <tr>
                                <th>{{ __('Tax') }}:</th>
                                <td>{{ $order->taxToString() }}</td>
                            </tr>
                        @endif
                        @if($order->coupon)
                            <tr>
                                <th>{{ __('Coupon') }}:</th>
                                <td>-{{ $order->coupon_percentage_discount }}%</td>
                            </tr>
                        @endif
                        <tr>
                            <th>{{ __('Shipping price') }}:</th>
                            <td>{{ $order->shippingPriceToString() }}</td>
                        </tr>
                        <tr>
                            <th>{{ __('Shipping method') }}:</th>
                            <td>{{ $order->shipping_method }} - {{ $order->shipping_days }}</td>
                        </tr>
                        <tr class="total">
                            <th class="border-no">{{ __('Total') }}:</th>
                            <td class="border-no">{{ $order->totalToString() }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- End of Order Details -->
            <div id="account-addresses">
                <div class="row">
                    <div class="col-sm-6 mb-8">
                        <div class="ecommerce-address address">
                            <h4 class="title title-underline ls-25 font-weight-bold">{{ __('Shipping address') }}</h4>
                            <address>
                                <strong>{{ $order->address->state->country->name }}.</strong><br>
                                {{ $order->address->state->name }},
                                {{ $order->address->municipality }}<br>
                                {{ $order->address->colony }}, {{ $order->address->street }}, {{ __('Zip code') }}: {{ $order->address->zip_code }}<br>
                                <abbr title="Phone">
                                    {{ __('Phone') }}: {{ $order->address->phone }}
                                </abbr> <br>
                                <abbr title="Email">
                                    {{ __('Email') }}: {{ $order->address->email }}
                                </abbr>
                            </address>
                        </div>
                    </div>
                    @if($order->billingAddress)
                        <div class="col-sm-6 mb-8">
                            <div class="ecommerce-address billing-address">
                                <h4 class="title title-underline ls-25 font-weight-bold">{{ __('Billing address') }}
                                </h4>
                                <address>
                                    <strong>{{ $order->billingAddress->state->country->name }}.</strong><br>
                                    {{ $order->billingAddress->vat }}, {{ $order->billingAddress->state->name }},
                                    {{ $order->billingAddress->municipality }}<br>
                                    {{ $order->billingAddress->colony }}, {{ $order->billingAddress->street }}, {{ __('Zip code') }}: {{ $order->billingAddress->zip_code }}<br>
                                    <abbr title="Phone">
                                        {{ __('Phone') }}: {{ $order->billingAddress->phone }}
                                    </abbr> <br>
                                    <abbr title="Email">
                                        {{ __('Email') }}: {{ $order->billingAddress->email }}
                                    </abbr>
                                </address>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End of PageContent -->
</div>

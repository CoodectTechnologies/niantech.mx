<div class="checkout">
    <div class="container">
        <div class="shop-cart">
            <div class="row">
                <div class="col-lg-6">
                    <div class="order-summary-wrapper sticky-sidebar">
                        <div class="title billing-title text-uppercase ls-10 pt-1 mb-0">
                            <h5 class="subtitle">{{ __('Order number') }}: {{ $order->number }}</h5>
                        </div>
                        @include('ecommerce.checkout.partials.payment.address._index')
                        @include('ecommerce.checkout.partials.payment.order._index')
                        @include('ecommerce.checkout.partials.payment.summary._index')
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="order-summary-wrapper">
                        @include('ecommerce.checkout.partials.payment.payment._index')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('footer')
        <script defer src="https://www.paypal.com/sdk/js?client-id={{ config('services.paypal.client_id') }}&currency={{ $currency }}"></script>
        <script defer src="https://sdk.mercadopago.com/js/v2"></script>
        <script defer>
            $(document).ready(function(){
                loadPayPayl();
                loadMercadopago();
            });

            function loadPayPayl() {
                const PAYPAL = document.querySelector("#paypal-button");
                if (PAYPAL) {
                    paypal.Buttons({
                        createOrder: function(data, actions) {
                            return actions.order.create({
                                purchase_units: [{
                                    amount: {
                                        value: '{{ $order->total }}'
                                    }
                                }]
                            });
                        },
                        onApprove: function(data, actions) {
                            return actions.order.capture().then(function(details) {
                                @this.call('paymentPayPal', data);
                            });
                        }
                    }).render('#paypal-button');
                }
            }

            function loadMercadopago() {
                const MERCADOPAGO = document.querySelector('#mercadopago-button');
                if (MERCADOPAGO) {
                    const mp = new MercadoPago('{{ config('services.mercadopago.key') }}', {
                        locale: "{{ config('services.mercadopago.country_code') }}",
                    });
                    const bricksBuilder = mp.bricks();
                    mp.bricks().create("wallet", "mercadopago-button", {
                        initialization: {
                            preferenceId: "{{ $mercadoPagoId }}",
                            redirectMode: "modal"
                        },
                        callbacks: {
                            onReady: () => {},
                            onSubmit: () => {},
                            onError: (error) => {
                                console.error(error)
                            },
                        }
                    });
                }
            }
        </script>
    @endpush
</div>

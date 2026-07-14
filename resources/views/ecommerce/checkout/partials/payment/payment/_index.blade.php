<div class="payment-methods sticky-sidebar">

    @include('ecommerce.components.alert')

    {{-- TOTAL --}}
    <div class="payment-total-card mb-4">
        <small class="text-uppercase">Total a pagar</small>
        <h2 class="mb-1">
            ${{ number_format($order->total, 2) }}
            {{ $order->currency }}
        </h2>
        <span>
            <i class="fa fa-lock me-2"></i>
            Pago seguro. Tu información está protegida y cifrada.
        </span>
    </div>

    {{-- MERCADO PAGO --}}
    @if(
        config('services.mercadopago.status') &&
        config('services.mercadopago.key') &&
        config('services.mercadopago.token') &&
        strtolower($order->currency) == strtolower(config('services.mercadopago.currency_code'))
    )
        <div class="card payment-card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <strong>Mercado Pago</strong>

                    <span class="badge badge-success">
                        Recomendado
                    </span>
                </div>

                <small class="text-muted d-block mb-3">
                    Paga con tarjeta, efectivo, SPEI o saldo Mercado Pago.
                </small>

                <div wire:ignore>
                    <div id="mercadopago-button"></div>
                </div>
            </div>
        </div>
    @endif

    {{-- PAYPAL --}}
    @if(config('services.paypal.status') && config('services.paypal.client_id'))
        <div class="card payment-card mb-3">
            <div class="card-body">
                <strong>PayPal</strong>

                <small class="text-muted d-block mt-2 mb-3">
                    Paga con tu cuenta PayPal o tarjeta de crédito y débito.
                </small>

                <div wire:ignore>
                    <div id="paypal-button"></div>
                </div>
            </div>
        </div>
    @endif

    {{-- OPENPAY --}}
    @if(
        config('services.openpay_bbva.status') &&
        config('services.openpay_bbva.public') &&
        config('services.openpay_bbva.private') &&
        $openpayBbvaURL
    )
        <div
            onclick="location='{{ $openpayBbvaURL }}'"
            class="card payment-card mb-3"
            style="cursor:pointer;"
        >
            <div class="card-body text-center">

                <img
                    width="180"
                    src="{{ asset('assets/admin/media/method_payment/openpay-bbva.webp') }}"
                    alt="OpenPay BBVA"
                >

                <small class="text-muted d-block mt-3">
                    Pago con tarjeta de crédito o débito.
                </small>

            </div>
        </div>
    @endif

    {{-- STRIPE --}}
    @if(
        config('services.stripe.status') &&
        config('services.stripe.public') &&
        config('services.stripe.secret') &&
        $stripeURL
    )
        <div
            onclick="location='{{ $stripeURL }}'"
            class="card payment-card mb-3"
            style="cursor:pointer;"
        >
            <div class="card-body text-center">

                <img
                    width="180"
                    src="{{ asset('assets/admin/media/method_payment/processout.svg') }}"
                    alt="Stripe"
                >

                <small class="text-muted d-block mt-3">
                    Pago seguro con tarjeta.
                </small>

            </div>
        </div>
    @endif

    {{-- TRANSFERENCIA --}}
    @if(config('services.transfer.status'))
        <div wire:ignore>
            <div
                class="card payment-card"
                data-target="#modal"
                data-toggle="modal"
                style="cursor:pointer;"
            >
                <div class="card-body">

                    <strong>
                        Transferencia bancaria o depósito
                    </strong>

                    <small class="text-muted d-block mt-2">
                        Recibirás por correo los datos bancarios para realizar tu pago.
                    </small>

                </div>
            </div>
        </div>
    @endif

</div>

{{-- MODAL TRANSFERENCIA --}}
<div
    class="modal fade"
    id="modal"
    data-backdrop="static"
    tabindex="-1"
    role="dialog"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">
                    Transferencia bancaria o depósito
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    ×
                </button>
            </div>
            <div class="modal-body">
                <p>
                    Al confirmar tu pedido recibirás por correo:
                </p>
                <ul>
                    <li>Datos bancarios</li>
                    <li>Referencia de pago</li>
                    <li>Instrucciones para completar el depósito o transferencia</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button
                    wire:click="paymentTransfer"
                    wire:target="paymentTransfer"
                    wire:loading.attr="disabled"
                    type="button"
                    class="btn btn-dark btn-block"
                >
                    <div
                        wire:loading.remove
                        wire:target="paymentTransfer">
                        Confirmar pedido
                        <i class="fa fa-arrow-right ms-2"></i>
                    </div>
                    <div
                        wire:loading
                        wire:target="paymentTransfer">
                        Generando instrucciones...
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>
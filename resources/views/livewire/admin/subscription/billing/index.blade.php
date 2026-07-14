<div>
    <div x-data="app">
        <div class="card" id="kt_pricing">
            <div class="card-body p-lg-17">
                <div class="d-flex flex-column">
                    <div class="mb-13 text-center">
                        <h1 class="fs-2hx fw-bold mb-5">Escoge tu plan</h1>
                        <div class="text-gray-600 fw-semibold fs-5">
                            Selecciona el plan que más te convenga y disfruta de tu HORLI.
                        </div>
                    </div>
                    <div class="nav-group nav-group-outline mx-auto mb-5">
                        <button x-on:click="selectPlanType('month')" x-bind:class="{ 'active': planType == 'month' }"
                            class="btn btn-color-gray-600 btn-active btn-active-secondary px-6 py-3 me-2">
                            Mensual
                        </button>
                        <button x-on:click="selectPlanType('year')" x-bind:class="{ 'active': planType == 'year' }"
                            class="btn btn-color-gray-600 btn-active btn-active-secondary px-6 py-3">
                            Anual
                        </button>
                    </div>
                    <div class="text-center text-gray-600 fw-semibold fs-5 mb-15">
                        Con nuestro plan anual, ahorra hasta un 40% de descuento en comparación con el plan mensual.
                    </div>
                    {{-- PLANES --}}
                    <div class="row g-10 mb-15">
                        @foreach($plans as $plan)
                            <div class="col-xl-4" wire:key='plan-{{ $plan->id }}'>
                                <div class="d-flex h-100 align-items-center">
                                    <div class="w-100 d-flex flex-column flex-center rounded-3 bg-light bg-opacity-75 py-15 px-10">
                                        
                                        @php
                                            // --- 0. Inicialización y Determinación de Estado ---
                                            $priceId = $this->planType === 'month' ? $plan->stripe_price_month_id : $plan->stripe_price_year_id;
                                            $subscription = $user->subscription($plan->stripe_product_name ?? '');
                                            $isCurrentPlan = $user->subscribedToPrice($priceId ?? '', $plan->stripe_product_name ?? '');
                                            $isSubscribedToAny = $user->subscribed($plan->stripe_product_name ?? '');
                                        @endphp

                                        <div class="mb-7 text-center">
                                            <h1 class="text-gray-900 mb-5 fw-bolder">{{ $plan->title }}</h1>
                                            <div class="text-gray-600 fw-semibold mb-5">{{ $plan->subtitle }}</div>
                                            <div class="text-center">
                                                @if($plan->stripe_id)
                                                    <span class="mb-2 text-primary">$</span>
                                                    <span x-show="planType == 'month'" class="fs-3x fw-bold text-primary">{{ $plan->amount_month }}</span>
                                                    <span x-show="planType == 'month'" class="fs-7 fw-semibold opacity-50">/ <span>Mensual</span></span>
                                                    <span x-show="planType == 'year'" class="fs-3x fw-bold text-primary">{{ $plan->amount_year }}</span>
                                                    <span x-show="planType == 'year'" class="fs-7 fw-semibold opacity-50">/ <span>Anual</span></span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="w-100 mb-10">
                                            @if($plan->free_trial_days)
                                                <div class="d-flex align-items-center mb-5">
                                                    <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">Prueba gratis {{ $plan->free_trial_days }} días</span>
                                                    <i class="ki-outline ki-check-circle fs-1 text-success"></i>
                                                </div>
                                            @endif
                                            @foreach($plan->planFeatures as $feature)
                                                <div class="d-flex align-items-center mb-5">
                                                    <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">{{ $feature->name }}</span>
                                                    <i class="ki-outline ki-check-circle fs-1 text-success"></i>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                        @if($plan->stripe_id)
                                            {{-- ---------------------------------------------------------------- --}}
                                            {{-- LÓGICA DE BOTONES --}}
                                            {{-- ---------------------------------------------------------------- --}}
                                            @if($isCurrentPlan)
                                                {{-- CASO 1: ES EL PLAN ACTUAL (Activo, Cancelado en Gracia, Incompleto) --}}
                                                @if($subscription->onGracePeriod())
                                                    <span class="badge badge-warning mb-3">Renovación Cancelada (Vigente hasta: {{ $subscription->ends_at->format('d M') }})</span>
                                                    <button 
                                                        wire:click='resumeSubscription("{{ $plan->id }}")'
                                                        wire:loading.attr='disabled'
                                                        wire:target='resumeSubscription("{{ $plan->id }}")'
                                                        class="btn btn-sm btn-secondary">
                                                        Reanudar Suscripción
                                                        <span wire:loading wire:target="resumeSubscription('{{ $plan->id }}')" class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </button>                                                    
                                                @elseif ($subscription->active())
                                                    <span class="badge badge-success mb-3">Suscrito y Activo</span>
                                                    <button 
                                                        wire:click='cancelSubscription("{{ $plan->id }}")'
                                                        wire:loading.attr='disabled'
                                                        wire:target='cancelSubscription("{{ $plan->id }}")'
                                                        class="btn btn-sm btn-danger">
                                                        Cancelar Suscripción
                                                        <span wire:loading wire:target="cancelSubscription('{{ $plan->id }}')" class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                    </button>                                                    
                                                @elseif ($subscription->incomplete())
                                                    <span class="badge badge-danger mb-3">Pago Incompleto</span>
                                                    <a href="{{ route('cashier.payment', [$subscription->stripe_id]) }}" class="btn btn-sm btn-warning">
                                                        Completar Pago
                                                    </a>                                                
                                                @else 
                                                    <span class="badge badge-info mb-3">Suscripción en Proceso</span>
                                                    <button class="btn btn-sm btn-light" disabled>
                                                        En Espera
                                                    </button>
                                                @endif
                                            @elseif ($isSubscribedToAny)
                                                {{-- CASO 2: ESTÁ SUSCRITO A OTRO PLAN (Hacer Switch) --}}
                                                <button 
                                                    wire:click='newSubscription("{{ $plan->id }}")'
                                                    wire:loading.attr='disabled'
                                                    wire:target='newSubscription("{{ $plan->id }}")'
                                                    class="btn btn-sm btn-secondary">
                                                    Cambiar a este plan
                                                    <span wire:loading wire:target="newSubscription('{{ $plan->id }}')" class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </button>
                                            @elseif ($subscription?->ended())
                                                {{-- CASO 3: SUSCRIPCIÓN ANTERIOR VENCIDA (Re-Suscribir) --}}
                                                <span class="badge badge-secondary mb-3">Suscripción Vencida</span>
                                                <button 
                                                    wire:click='newSubscription("{{ $plan->id }}")'
                                                    wire:loading.attr='disabled'
                                                    wire:target='newSubscription("{{ $plan->id }}")'
                                                    class="btn btn-sm btn-primary">
                                                    Reiniciar Suscripción
                                                    <span wire:loading wire:target="newSubscription('{{ $plan->id }}')" class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </button>
                                            @else
                                                {{-- CASO 4: ESCENARIO POR DEFECTO (Compra Inicial) --}}
                                                <button
                                                    wire:click='newSubscription("{{ $plan->id }}")'
                                                    wire:loading.attr='disabled'
                                                    wire:target='newSubscription("{{ $plan->id }}")'
                                                    class="btn btn-sm btn-primary">
                                                    Suscribirte
                                                    <span wire:loading wire:target="newSubscription('{{ $plan->id }}')" class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </button>
                                            @endif
                                            
                                        @else
                                            {{-- Si el plan no tiene Stripe ID, es un plan manual o a cotizar --}}
                                            <span class="btn btn-sm btn-secondary">
                                                <a href="mailto:{{ config('contact.email') }}" class="text-dark">A cotizar</a>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    {{-- MÉTODOS DE PAGO --}}
                    <div wire:ignore.self class="card card-flush pt-3 mb-5 mb-lg-10 bg-light bg-opacity-75"
                        data-kt-subscriptions-form="pricing">
                        <div class="card-header">
                            <div class="card-title">
                                <h2 class="fw-bold">Métodos de pago</h2>
                            </div>
                            <div class="card-toolbar">
                                <a href="#" class="btn btn-light-primary" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_new_card">
                                    Nuevo método de pago
                                </a>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div id="payment_methods">
                                @forelse($paymentMethods as $index => $method)
                                    @php
                                        $card = $method->card;
                                        $billing = $method->billing_details;
                                        $brand = ucfirst($card->brand);
                                        $expMonth = str_pad($card->exp_month, 2, '0', STR_PAD_LEFT);
                                        $expYear = $card->exp_year;
                                        $last4 = $card->last4;
                                        $isExpired =
                                            $expYear < now()->year ||
                                            ($expYear == now()->year && $expMonth < now()->month);
                                        $collapseId = 'payment_method_' . $index;
                                    @endphp

                                    <div class="py-1" wire:key='payment-method-{{ $method->id }}'>
                                        <div class="py-3 d-flex flex-stack flex-wrap">
                                            <div class="d-flex align-items-center collapsible toggle {{ $index === 0 ? '' : 'collapsed' }}"
                                                data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
                                                <div class="btn btn-sm btn-icon btn-active-color-primary ms-n3 me-2">
                                                    <i
                                                        class="ki-outline ki-minus-square toggle-on text-primary fs-2"></i>
                                                    <i class="ki-outline ki-plus-square toggle-off fs-2"></i>
                                                </div>
                                                @php
                                                    $logo = match (strtolower($brand)) {
                                                        'visa' => asset('assets/admin/media/svg/card-logos') .
                                                            '/visa.svg',
                                                        'mastercard' => asset('assets/admin/media/svg/card-logos') .
                                                            '/mastercard.svg',
                                                        'american express', 'amex' => asset(
                                                            'assets/admin/media/svg/card-logos',
                                                        ) . '/american-express.svg',
                                                        default => asset('assets/admin/media/svg/card-logos') .
                                                            '/generic.svg',
                                                    };
                                                @endphp
                                                <img src="{{ $logo }}" class="w-40px me-3"
                                                    alt="{{ $brand }}" />
                                                <div class="me-3">
                                                    <div class="d-flex align-items-center fw-bold">
                                                        {{ $brand }}
                                                        @if($isExpired)
                                                            <div class="badge badge-light-danger ms-5">Expirada</div>
                                                        @endif
                                                    </div>
                                                    <div class="text-muted">Expira
                                                        {{ $expMonth }}/{{ $expYear }}</div>
                                                </div>
                                            </div>
                                            <div class="d-flex my-3 ms-9">
                                                <button wire:click="defaultPaymentMethod('{{ $method->id }}')";
                                                    class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1">
                                                    <i class="ki-duotone ki-star fs-2 {{ ($user->defaultPaymentMethod()->id ?? null) == $method->id ? 'text-primary' : '' }}"
                                                        wire:loading.remove
                                                        wire:target="defaultPaymentMethod('{{ $method->id }}')"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span><span class="path4"></span><span
                                                            class="path5"></span></i>
                                                    <span wire:loading
                                                        wire:target="defaultPaymentMethod('{{ $method->id }}')"
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </button>
                                                <button x-on:click="deletePaymentMethod('{{ $method->id }}')";
                                                    class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm me-1">
                                                    <i class="ki-duotone ki-trash fs-2" wire:loading.remove
                                                        wire:target="deletePaymentMethod('{{ $method->id }}')"><span
                                                            class="path1"></span><span class="path2"></span><span
                                                            class="path3"></span><span class="path4"></span><span
                                                            class="path5"></span></i>
                                                    <span wire:loading
                                                        wire:target="deletePaymentMethod('{{ $method->id }}')"
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </button>
                                            </div>
                                        </div>
                                        <div id="{{ $collapseId }}"
                                            class="collapse {{ $index === 0 ? 'show' : '' }} fs-6 ps-10">
                                            <div class="d-flex flex-wrap py-5">
                                                <div class="flex-equal me-5">
                                                    <table class="table table-flush fw-semibold gy-1">
                                                        <tr>
                                                            <td class="text-muted min-w-125px w-125px">Titular</td>
                                                            <td class="text-gray-800">
                                                                {{ $billing->name ?? 'Sin nombre' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Número</td>
                                                            <td class="text-gray-800">**** {{ $last4 }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Expira</td>
                                                            <td class="text-gray-800">
                                                                {{ $expMonth }}/{{ $expYear }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Tipo</td>
                                                            <td class="text-gray-800">{{ $brand }}
                                                                {{ $card->funding }} card</td>
                                                        </tr>
                                                    </table>
                                                </div>

                                                <div class="flex-equal">
                                                    <table class="table table-flush fw-semibold gy-1">
                                                        <tr>
                                                            <td class="text-muted">País</td>
                                                            <td class="text-gray-800">{{ $card->country ?? '—' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Código Postal</td>
                                                            <td class="text-gray-800">
                                                                {{ $billing->address->postal_code ?? '—' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Email</td>
                                                            <td class="text-gray-800">
                                                                @if($billing->email)
                                                                    <a href="mailto:{{ $billing->email }}"
                                                                        class="text-gray-900 text-hover-primary">
                                                                        {{ $billing->email }}
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted">No proporcionado</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-muted">Teléfono</td>
                                                            <td class="text-gray-800">
                                                                {{ $billing->phone ?? 'No proporcionado' }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if(!$loop->last)
                                        <div class="separator separator-dashed"></div>
                                    @endif
                                @empty
                                    <div class="text-center py-10 text-muted">
                                        No tienes métodos de pago registrados. Comienza adjuntando uno nuevo.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    {{-- FACTURAS --}}
                    <div wire:ignore.self class="card card-flush pt-3 mb-5 mb-lg-10 bg-light bg-opacity-75">
                        <div class="card-header">
                            <div class="card-title">
                                <h2 class="fw-bold">Facturas</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div id="invoices">
                                <div class="table-responsive">
                                    <!--begin::Table-->
                                    <table id="kt_customer_details_invoices_table_1"
                                        class="table align-middle table-row-dashed fs-6 fw-bold gs-0 gy-4 p-0 m-0">
                                        <thead class="border-bottom border-gray-200 fs-7 text-uppercase fw-bold">
                                            <tr class="text-start text-gray-500">
                                                <th class="min-w-100px">Fecha</th>
                                                <th class="min-w-100px">Total</th>
                                                <th class="w-100px">Factura</th>
                                            </tr>
                                        </thead>
                                        <tbody class="fs-6 fw-semibold text-gray-600">
                                            @foreach($invoices as $invoice)
                                                <tr>
                                                    <td>{{ $invoice->date()->toFormattedDateString() }}</td>
                                                    <td class="text-success">{{ $invoice->total() }}</td>
                                                    <td class="">
                                                        <button wire:click='downloadInvoice("{{ $invoice->id }}")' class="btn btn-sm btn-light btn-active-light-primary">
                                                            Descargar
                                                            <span wire:loading wire:target="downloadInvoice('{{ $invoice->id }}')" class="spinner-border spinner-border-sm align-middle"></span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <!--end::Table-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- AGREGAR METODO DE PAGO --}}
    <div wire:ignore.self class="modal fade" id="kt_modal_new_card" tabindex="-1" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Agregar una nueva tarjeta</h2>
                    <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7" wire:ignore>
                    <div class="position-relative mb-10">
                        <h3 class="header-card">Tarjeta de crédito o débito</h3>
                        <div class="position-absolute translate-middle-y top-50 end-0 me-5">
                            <img src="{{ asset('assets/admin') }}/media/svg/card-logos/visa.svg" alt=""
                                class="h-25px">
                            <img src="{{ asset('assets/admin') }}/media/svg/card-logos/mastercard.svg" alt=""
                                class="h-25px">
                            <img src="{{ asset('assets/admin') }}/media/svg/card-logos/american-express.svg"
                                alt="" class="h-25px">
                        </div>
                    </div>
                    <div class="mb-5">
                        <input id="card-holder-name-stripe"
                            placeholder="{{ __('Nombre del titular de la tarjeta') }}" type="text"
                            class="form-control form-control-solid">
                    </div>
                    <div class="">
                        <div id="card-payment-container-stripe" class="form-control form-control-solid"></div>
                        <span id="card-errors-stripe" class="text-danger fs-7 mt-2"></span>
                    </div>
                    <div class="d-flex justify-content-center mt-10">
                        <button wire:target='addPaymentMethod' wire:loading.attr='disabled' type="button"
                            id="card-button-stripe" class="btn btn-primary">
                            <i class="me-2 fa-solid fa-lock"></i>
                            {{ __('Agregar método de pago') }}
                            <span wire:loading wire:target="addPaymentMethod"
                                class="spinner-border spinner-border-sm align-middle"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@assets
    <script src="https://js.stripe.com/v3/"></script>
@endassets

@script
    <script>
        Alpine.data('app', () => ({
            planType: $wire.entangle('planType').live,
            planId: null,

            init() {
                this.renderCardPaymentStripe();
                // Abrir modal cuando Livewire indique que hay un plan pendiente
                Livewire.on('openPaymentModalForPlan', (planId) => {
                    this.selectPlanId(planId);
                    $('#kt_modal_new_card').modal('show');
                });
                // Cuando se cierre el modal, limpiamos el plan seleccionado
                $('#kt_modal_new_card').on('hidden.bs.modal', () => {
                    this.planId = null;
                });
            },
            selectPlanType(type) {
                this.planType = type;
            },
            selectPlanId(planId) {
                this.planId = planId;
            },
            renderCardPaymentStripe() {
                const stripe = Stripe('{{ config('cashier.key') }}');
                const elements = stripe.elements();
                const cardElement = elements.create('card', themeMode === 'dark' ? {
                    style: {
                        base: {
                            iconColor: '#5a5d6a',
                            color: '#fff',
                            fontWeight: '500',
                            fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                            fontSize: '16px',
                            fontSmoothing: 'antialiased',
                            ':-webkit-autofill': {
                                color: '#fce883',
                            },
                            '::placeholder': {
                                color: '#9a9cae',
                            },
                        },
                        invalid: {
                            iconColor: '#FFC7EE',
                            color: '#FFC7EE',
                        },
                    }
                } : {});

                cardElement.mount('#card-payment-container-stripe');

                const cardHolderName = document.getElementById('card-holder-name-stripe');
                const cardButton = document.getElementById('card-button-stripe');
                const cardErrorsStripe = document.getElementById('card-errors-stripe');

                cardButton.addEventListener('click', async (e) => {
                    cardButton.disabled = true;
                    @this.call('loadSetupIntent').then(async () => {
                        let clientSecret = @this.stripeIntent;
                        const {
                            setupIntent,
                            error
                        } = await stripe.confirmCardSetup(
                            clientSecret, {
                                payment_method: {
                                    card: cardElement,
                                    billing_details: {
                                        name: cardHolderName.value
                                    }
                                }
                            }
                        );
                        cardButton.disabled = false;
                        if (error) {
                            cardErrorsStripe.textContent = error.message;
                        } else {
                            cardErrorsStripe.textContent = '';
                            @this.call('addPaymentMethod', setupIntent.payment_method).then(() => {
                                cardHolderName.value = '';
                                cardElement.clear();
                                $('#kt_modal_new_card').modal('hide');
                                // Si Alpine tiene planId seleccionado podemos mostrar una alerta leve indicando que se procesará
                                if(this.planId){
                                    // opcional: mostrar feedback en UI, el backend intentará crear la suscripción automáticamente
                                }
                            });
                        }
                    });
                });
            },
            deletePaymentMethod(paymentMethodId) {
                swal.fire({
                    title: "{{ __('¿Estás seguro?') }}",
                    text: "{{ __('No podrá recuperar este registro') }}",
                    icon: "warning",
                    buttonsStyling: false,
                    showCancelButton: true,
                    confirmButtonText: "<i class='fa fa-trash'></i> <span class='font-weight-bold'>{{ __('Si, eliminar') }}</span>",
                    cancelButtonText: "<i class='fas fa-arrow-circle-left'></i>  <span class='text-dark font-weight-bold'>{{ __('No, cancelar') }}</span>",
                    reverseButtons: true,
                    cancelButtonClass: "btn btn-light-secondary font-weight-bold",
                    confirmButtonClass: "btn btn-danger"
                }).then(function(result) {
                    if (result.isConfirmed) {
                        @this.call('deletePaymentMethod', paymentMethodId);
                    }
                });
            }
        }));
    </script>
@endscript

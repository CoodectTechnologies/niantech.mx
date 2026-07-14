<div id="billing-addresses">
    <h3 class="subtitle text-uppercase ls-10 mt-5 mb-2">
        {{ __('Dirección de Facturación') }}
    </h3>

    <!-- Checkbox principal para requerir dirección de facturación -->
    <div class="form-group">
        <input x-model="billingRequire" type="checkbox" class="custom-checkbox" id="billing-address-require">
        <label for="billing-address-require" class="font-weight-medium">
            {{ __('Select a billing address') }}
        </label>
    </div>

    <div x-show="billingRequire" x-transition x-cloak>

        <!-- Dirección de facturación seleccionada -->
        @if($billingAddress->id ?? null)
            <div wire:key="billing.{{ $billingAddress->id }}.selected" class="card shadow-sm border-0 mb-4">
                <div class="card-body p-3 p-md-4">
                    
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fa-sharp-duotone fa-solid fa-file-invoice text-primary me-2"></i>
                            <h5 class="mb-0 fw-semibold">{{ $billingAddress->name }}</h5>
                        </div>
                        
                        <span class="badge badge-success px-3 py-1 d-flex align-items-center">
                            <i class="fa-solid fa-check fa-xs me-1"></i> Seleccionado
                        </span>
                    </div>

                    <!-- Dirección -->
                    <div class="mb-3">
                        <div class="small mb-1">{{ __('BILLING ADDRESS') }}</div>
                        <p class="mb-0">
                            {{ $billingAddress->street }}, {{ $billingAddress->colony }}<br>
                            {{ $billingAddress->municipality }}, {{ $billingAddress?->state?->name ?? '' }}<br>
                            {{ $billingAddress->state?->country?->name ?? '' }} • C.P. {{ $billingAddress->zip_code }}
                        </p>
                    </div>

                    <!-- Contacto -->
                    <div class="row">
                        <div class="col-6">
                            <div class="small">{{ __('Phone') }}</div>
                            <div class="font-weight-medium">{{ $billingAddress->phone }}</div>
                        </div>
                        <div class="col-6">
                            <div class="small">{{ __('Email') }}</div>
                            <div class="font-weight-medium text-break">{{ $billingAddress->email }}</div>
                        </div>
                    </div>

                </div>
            </div>
        @endif
        @error('billingAddress.id')
            <small class="form-text text-danger" role="alert">{{ $message }}</small>
        @enderror

        <!-- Opciones adicionales -->
        <div class="mt-3">

            @if(count($billingAddresses) > 1)
                <div class="form-group">
                    <input x-model="showMoreBillingAddresses" type="checkbox" class="custom-checkbox" id="show-billing-address-more">
                    <label for="show-billing-address-more" class="font-weight-medium">
                        {{ __('Ver mis demás direcciones de facturación') }}
                    </label>
                </div>
            @endif

            @if(count($billingAddresses))
                <div class="form-group">
                    <input x-model="billingAddressDiferentCreate" type="checkbox" class="custom-checkbox" id="billing-address-create-diferent">
                    <label for="billing-address-create-diferent" class="font-weight-medium">
                        {{ __('¿Usar una dirección de facturación diferente?') }}
                    </label>
                </div>
            @endif

        </div>

        <!-- Lista de otras direcciones de facturación -->
        <div x-show="showMoreBillingAddresses" x-transition x-cloak>
            @foreach($billingAddresses as $ba)
                @if($ba->id != ($billingAddress->id ?? null))
                    <div wire:key="billing.{{ $ba->id }}" class="card shadow-sm border-0 mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-2 font-weight-semibold">{{ $ba->name }}</h6>
                                    <p class="mb-2 small">
                                        {{ $ba->street }}, {{ $ba->colony }}<br>
                                        {{ $ba->municipality }}, {{ $ba->state?->name ?? '' }}
                                    </p>
                                </div>
                                
                                <button 
                                    wire:click.prevent="loadBillingAddress('{{ $ba->id }}')"
                                    wire:loading.attr="disabled"
                                    class="btn btn-outline-primary btn-sm">
                                    Facturar aquí
                                    <span wire:loading wire:target="loadBillingAddress('{{ $ba->id }}')" 
                                          class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Formulario para crear nueva dirección de facturación diferente -->
        <div x-show="billingAddressDiferentCreate" x-transition x-cloak>
            @livewire('ecommerce.address.form', ['redirect' => false, 'isBilling' => true, 'target' => 'billing.create.diferent'], key('billing.create.diferent'))
        </div>

    </div>
</div>
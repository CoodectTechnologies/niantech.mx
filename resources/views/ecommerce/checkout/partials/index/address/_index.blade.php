<div id="addresses">
    <h3 class="subtitle text-uppercase ls-10 mt-2">
        {{ __('Dirección de Envío') }}
    </h3>

    <!-- Dirección seleccionada -->
    @if(count($addresses) && $address->id ?? null)
        <div wire:key="address.{{ $address->id }}.selected" class="card shadow-sm border-0 mb-4">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div class="d-flex align-items-center">
                        <i class="fa-sharp-duotone fa-solid fa-map-location-dot text-primary me-2"></i>
                        <h5 class="mb-0 font-weight-bold">{{ $address->name }}</h5>
                    </div>

                    <div class="d-flex flex-wrap mt-2 mt-md-0 align-items-center">
                        <span class="badge badge-success px-3 py-1 me-2">
                            <i class="fa-solid fa-check fa-xs me-1"></i>
                            Seleccionado
                        </span>

                        <a href="{{ route('ecommerce.account.address.edit', $address) }}"
                        class="badge badge-success px-3 py-1 me-2">
                            <i class="fa-solid fa-pen fa-xs me-1"></i>
                            Editar
                        </a>
                    </div>

                </div>

                <div class="mb-3 checkout-address-content">
                    <div class="small mb-1">{{ __('SHIPPING ADDRESS') }}</div>
                    <p class="mb-0 ">
                        {{ $address->street }}, {{ $address->colony }}<br>
                        {{ $address->municipality }}, {{ $address->state?->name ?? '' }}<br>
                        {{ $address->state?->country?->name ?? '' }} • C.P. {{ $address->zip_code }}
                    </p>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="small">{{ __('Phone') }}</div>
                        <div class="font-weight-medium">{{ $address->phone }}</div>
                    </div>
                    <div class="col-6">
                        <div class="small">{{ __('Email') }}</div>
                        <div class="font-weight-medium text-break">{{ $address->email }}</div>
                    </div>
                </div>
            </div>
        </div>
    @else
        @livewire('ecommerce.address.form', ['redirect' => false, 'target' => 'shipping.create'], key('shipping.create'))
    @endif

    @error('address.id')
        <small class="form-text text-danger" role="alert">{{ $message }}</small>
    @enderror

    <!-- Opciones adicionales -->
    <div class="mt-3">
        @if(count($addresses) > 1)
            <div>
                <input x-model="showMoreAddresses" type="checkbox" class="custom-checkbox" id="show-address-more">
                <label for="show-address-more" class="font-weight-medium">
                    {{ __('Ver mis demás direcciones de envío') }}
                </label>
            </div>
        @endif
        @if(count($addresses))
            <div>
                <input x-model="addressDiferentCreate" type="checkbox" class="custom-checkbox" id="address-create-diferent">
                <label for="address-create-diferent">{{ __('¿Usar una dirección de envío diferente?') }}</label>
            </div>
        @endif
    </div>

    <!-- Más direcciones -->
    <div x-show="showMoreAddresses" x-transition x-cloak>
        @foreach($addresses as $sa)
            @if($sa->id != ($address->id ?? null))
                <div wire:key="address.{{ $sa->id }}" class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-2 font-weight-semibold">{{ $sa->name }}</h6>
                                <p class="mb-2 small">
                                    {{ $sa->street }}, {{ $sa->colony }}<br>
                                    {{ $sa->municipality }}, {{ $sa->state->name }}
                                </p>
                            </div>
                            <button wire:click.prevent="loadAddress('{{ $sa->id }}')" class="btn btn-outline-primary btn-sm">
                                Enviar aquí
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Formulario nueva dirección -->
    <div x-show="addressDiferentCreate" x-transition x-cloak>
        @livewire('ecommerce.address.form', ['redirect' => false, 'target' => 'shipping.create.diferent'], key('shipping.create.diferent'))
    </div>
</div>
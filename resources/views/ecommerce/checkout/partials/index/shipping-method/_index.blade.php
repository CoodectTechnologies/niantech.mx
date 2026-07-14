<h3 class="subtitle text-uppercase ls-10 mt-2">
    {{ __('Shipping methods') }}
</h3>
@if($address->exists ?? false)
    @forelse($shippingMethods as $sm)
        <div class="shipping-method-card">
            <div class="custom-control custom-radio">
                <input
                    wire:model.live="shippingZoneId"
                    type="radio"
                    class="d-none custom-control-input"
                    id="shipping-{{ $sm['id'] }}"
                    name="shipping-method"
                    value="{{ $sm['id'] }}"
                >
                <label class="custom-control-label shipping-label" for="shipping-{{ $sm['id'] }}">
                    <div class="shipping-content">
                        <div class="d-flex flex-column" style="line-height: 15px;">
                            <div class="shipping-title">
                                {{ $sm['name'] }}
                            </div>
                            <div class="shipping-date">
                                {{ $sm['days'] }} días · {{ $sm['estimatedDate'] }}
                            </div>
                        </div>
                        <div class="shipping-price">
                            {{ currencySymbol() }}{{ $sm['price'] }}
                        </div>
                    </div>
                </label>
            </div>
        </div>
    @empty
        <p>{{ __('No se encontraron direcciones de envio para tu zona') }}</p>
    @endforelse
    @error('shippingZoneId')
        <small class="form-text text-danger" role="alert">{{ $message }}</small>
    @enderror
@else
    <p>{{ __('Se necesita una dirección de envío') }}</p>
@endif
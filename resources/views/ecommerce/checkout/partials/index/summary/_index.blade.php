<div>
    <div class="summary-card">

        {{-- Subtotal --}}
        <div class="summary-row">
            <span>{{ __('Subtotal') }} <i class="fa-light fa-circle-question" title="{{ __('Tax') }} {{ Cart::instance('default')->tax() }} {{ currency() }}"></i></span>
            <strong>
                {{ currencySymbol() }}{{ Cart::instance('default')->subtotal() }} {{ currency() }}
            </strong>
        </div>

        {{-- Shipping --}}
        @if($shippingRequire)
            <div class="summary-row">
                <span>{{ __('Shipping price') }} <i class="fa-light fa-circle-question" title="{{ __('Tax') }} {{ $shippingPriceTax }} {{ currency() }}"></i></span>

                @if((count($shippingMethods) && !$shippingZoneId) || !count($shippingMethods))
                    <span class="summary-badge">
                        {{ __('On hold') }}
                    </span>
                @else
                    <strong>
                        {{ currencySymbol() }}{{ number_format(str_replace(config('cart.format.thousand_seperator'), '', $shippingPrice), 2) }} {{ currency() }}
                    </strong>
                @endif
            </div>

            @if(!config('services.odoo.status') || true)
                <div class="summary-row">
                    <span>{{ __('Estimated delivery date') }}</span>

                    @if((count($shippingMethods) && !$shippingZoneId) || !count($shippingMethods))
                        <span class="summary-badge">
                            {{ __('On hold') }}
                        </span>
                    @else
                        <span>{{ $shippingDays }}</span>
                    @endif
                </div>
            @endif
        @endif

        {{-- Tax --}}
        @if($tax)
            <div class="summary-row">
                <span>{{ __('Tax') }}</span>
                <strong>
                    {{ currencySymbol() }}{{ number_format($tax, 2) }} {{ currency() }}
                </strong>
            </div>
        @endif

        {{-- Coupon --}}
        @if($couponRequire && $coupon)
            <div class="summary-row text-success">
                <span>{{ __('Coupon') }}</span>

                @if($coupon->type_coupon == 'Fijo')
                    <strong>
                        -{{ currencySymbol() }}{{ number_format($coupon->fixed, 2) }} {{ currency() }}
                    </strong>
                @elseif($coupon->type_coupon == 'Porcentaje')
                    <strong>
                        -{{ $coupon->percentage }}%
                    </strong>
                @endif
            </div>
        @endif

        <div class="summary-divider"></div>

        {{-- Total --}}
        <div class="summary-row summary-total">
            <span>{{ __('Total') }}</span>

            @if(($shippingRequire && count($shippingMethods) && !$shippingZoneId) || ($shippingRequire && !count($shippingMethods)))
                <span class="summary-badge">
                    {{ __('On hold') }}
                </span>
            @else
                <strong>
                    {{ currencySymbol() }}{{ number_format(str_replace(config('cart.format.thousand_seperator'), '', $totalPrice), 2) }}
                    {{ currency() }}
                </strong>
            @endif
        </div>

        {{-- Button --}}
        <div class="mt-4">

            <button
                wire:click="createOrder"
                wire:target.prevent="createOrder"
                wire:loading.class="load-more-overlay loading"
                wire:loading.attr="disabled"
                type="submit"
                class="btn btn-primary btn-block btn-lg"

                {{ $shippingRequire && count($shippingMethods) && !$shippingZoneId ? 'disabled' : '' }}
                {{ $shippingRequire && !count($shippingMethods) ? 'disabled' : '' }}
            >

                <div wire:loading.remove wire:target="createOrder">
                    {{ __('Proceed to pay') }}
                    <i class="fa fa-arrow-right ms-2"></i>
                </div>

                <div wire:loading wire:target="createOrder">
                    {{ __('Please wait') }}...
                </div>

            </button>

        </div>

        @error('user')
            <small class="text-danger d-block mt-3">
                {{ $message }}
            </small>
        @enderror

    </div>
</div>
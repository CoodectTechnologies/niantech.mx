<div class="dropdown cart-dropdown me-3">
    <div class="cart-overlay"></div>
    <a href="{{ route('ecommerce.compare.index') }}" class="cart-toggle label-down link">
        <i class="fa-duotone fa-regular fa-scale-balanced"></i>
            @if($countCompare = Cart::instance('compare')->count())
                <span class="cart-count">{{ $countCompare }}</span>
            @endif
        </i>
        {{-- <span class="cart-label">{{ __('Compare') }}</span> --}}
    </a>
</div>

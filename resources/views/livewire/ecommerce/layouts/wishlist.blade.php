<div class="dropdown cart-dropdown me-3">
    <div class="cart-overlay"></div>
    <a href="{{ route('ecommerce.wishlist.index') }}" class="cart-toggle label-down link">
        <i class="fa-notdog fa-solid fa-heart">
            @if($countWishlist = Cart::instance('wishlist')->count())
                <span class="cart-count">{{ $countWishlist }}</span>
            @endif
        </i>
        {{-- <span class="cart-label">{{ __('Wishlist') }}</span> --}}
    </a>
</div>

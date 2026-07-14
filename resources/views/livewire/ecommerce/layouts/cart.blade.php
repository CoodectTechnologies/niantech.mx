<div class="dropdown cart-dropdown cart-offcanvas me-3 mr-lg-2" wire:ignore.self>
    <div class="cart-overlay"></div>
    <a href="{{ route('ecommerce.cart.index') }}" class="cart-toggle label-down link">
        <i class="fa-notdog fa-solid fa-cart-shopping">
            @if($countCart = Cart::instance('default')->count())
                <span class="cart-count">{{ $countCart }}</span>
            @endif
        </i>
        {{-- <span class="cart-label">{{ __('Cart') }}</span> --}}
    </a>
    <div class="dropdown-box">
        <div class="cart-header">
            <span>{{ __('Shopping cart') }}</span>
            <a href="#" class="header-btn-close">
                <i class="fa-jelly-fill fa-regular fa-circle-xmark"></i>
            </a>
        </div>
        <div class="products">
            @forelse (Cart::instance('default')->content() as $item)
                <div wire:key='{{ $item->rowId }}' class="product product-cart" style="border-top: 1px solid #eee;">
                    <div class="product-detail">
                        <a href="{{ route('ecommerce.product.show', $item->model) }}" class="product-name">
                            {{ $item->name }}
                        </a>
                        @if($item->options->type == 'Digital')
                            <span style="font-size: 1.2rem">{{ __('Type') }}: {{ $item->options->type }}</span
                                style="font-size: 1.2rem">
                        @endif
                        @if(isset($item->options['variant']))
                            @foreach($item->options['variant']['options'] as $option)
                                <span style="font-size: 1.2rem">{{ $option['option_name'] }}: {{ $option['option_value'] }}</span
                                    style="font-size: 1.2rem"> <br>
                            @endforeach
                        @endif
                        @if(!$item->model->getIsInStock())
                            <div class="price-box">
                                <h4 class="alert-title">{{ __('Out stock') }}</h4>
                            </div>
                        @else
                            <div class="price-box">
                                <span class="product-quantity">{{ $item->qty }}</span>
                                <span class="product-price">{{ currencySymbol() }}{{ $item->subtotal() }}
                                    {{ $item->options->currency }}</span>
                            </div>
                        @endif
                    </div>
                    <figure class="product-media">
                        <a href="{{ route('ecommerce.product.show', $item->model) }}">
                            <img loading="lazy" src="{{ $item->options->image }}" alt="{{ $item->name }}"
                                height="84" width="94" />
                        </a>
                    </figure>
                    <button wire:loading.class="load-more-overlay loading"
                        wire:click.prevent="removeProduct('{{ $item->rowId }}')" class="btn btn-link btn-close"
                        wire:target="removeProduct('{{ $item->rowId }}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @empty
                <div class="alert alert-success alert-button">
                    <a href="{{ route('ecommerce.product.index') }}" class="btn btn-success btn-rounded">
                        {{ __('Empty cart') }}
                    </a>
                </div>
            @endforelse
        </div>
        <div class="mt-2"></div>
        @if(floatval(str_replace(config('cart.format.thousand_seperator'), '', Cart::instance('default')->tax())))
            <div class="cart-subtotal d-flex align-items-center justify-content-between">
                <label class="ls-25">{{ __('Subtotal') }}</label>
                <span>{{ currencySymbol() }}{{ Cart::instance('default')->subtotal() }} {{ currency() }}</span>
            </div>
            <div class="cart-subtotal d-flex align-items-center justify-content-between">
                <label class="ls-25">{{ __('Tax') }}</label>
                <span>{{ currencySymbol() }}{{ Cart::instance('default')->tax() }} {{ currency() }}</span>
            </div>
        @endif
        <div class="cart-subtotal d-flex align-items-center justify-content-between">
            <label>{{ __('Total') }}</label>
            <span class="price">{{ currencySymbol() }}{{ Cart::instance('default')->total() }} {{ currency() }}
            </span>
        </div>
        @if(Cart::instance('default')->count())
            <div class="cart-action">
                @if(Route::has('ecommerce.cart.index'))
                    <a href="{{ route('ecommerce.cart.index') }}"
                        class="btn btn-dark btn-outline btn-rounded">{{ __('Show cart') }}</a>
                @endif
                @if(Route::has('ecommerce.checkout.index'))
                    <a href="{{ route('ecommerce.checkout.index') }}"
                        class="btn btn-primary  btn-rounded">{{ __('Checkout') }}</a>
                @endif
            </div>
        @endif
    </div>
    <!-- End of Dropdown Box -->
</div>

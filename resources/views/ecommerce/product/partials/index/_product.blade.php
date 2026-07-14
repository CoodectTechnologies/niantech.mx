@php
    $promotion = $product->getPromotion();
    $isInStock = $product->getIsInStock();
    $hasVariants = count($product->productVariants);
    if($promotion):
        $year = \Carbon\Carbon::parse($promotion->date_end)->format('Y');
        $month = \Carbon\Carbon::parse($promotion->date_end)->format('m');
        $day = \Carbon\Carbon::parse($promotion->date_end)->format('d');
        $maxPromotionPercentage = $product->getPromotionPercentageMax();
    endif;
@endphp
<div class="product-wrap">
    <div class="product text-left">
        <figure class="product-media">
            <a href="{{ route('ecommerce.product.show', $product) }}">
                <img loading="lazy"  src="{{ $product->imagePreview() }}" alt="{{ $product->getName() }}"
                    width="300" height="300" />
                {{-- @if(count($product->imagesPreview()))
                    <img loading="lazy"  src="{{ $product->imagesPreview()->first() }}"
                        alt="{{ $product->getName() }}" width="300" height="300">
                @endif --}}
            </a>
            @if($promotion)
                <div class="product-countdown-container">
                    <div class="product-countdown countdown-compact"
                        data-until="{{ $year }}, {{ $month }}, {{ $day }}" data-format="DHMS"
                        data-compact="false"
                        data-labels-short="{{ __('Years') }}, {{ __('Months') }}, {{ __('Weeks') }}, {{ __('Days') }}, {{ __('Hours') }}, {{ __('Mins') }}, {{ __('Secs') }}">
                        00:00:00:00
                    </div>
                </div>
            @endif
            <div class="product-action-horizontal">
                @livewire('ecommerce.wishlist.mini', ['product' => $product], key('wishlist-' . $product->id))
                @livewire('ecommerce.compare.mini', ['product' => $product], key('compare-' . $product->id))
            </div>
            <div class="product-label-group">
                @if($product->getIsNew())
                    <label class="product-label label-new">{{ __('New') }}</label>
                @endif
                @if($promotion && $maxPromotionPercentage > 0)
                    <label class="product-label label-discount">- {{ $maxPromotionPercentage }}%</label>
                @endif
            </div>
        </figure>
        <div class="product-details">
            <div class="product-cat">
                @foreach($product->productCategories as $productCategory)
                    <a href="{{ route('ecommerce.product.index', ['category' => $productCategory->slug]) }}">{{ $productCategory->name }}</a>
                @endforeach
            </div>
            <h3 class="product-name">
                <a href="{{ route('ecommerce.product.show', $product) }}">{{ $product->getName() }}</a>
            </h3>
            <div class="ratings-container">
                <div class="ratings-full">
                    <span class="ratings" style="width: {{ $product->getStarsPercentageAVG() }}%;"></span>
                    <span class="tooltiptext tooltip-top"></span>
                </div>
                <a href="{{ route('ecommerce.product.show', $product) }}#comments" class="rating-reviews">({{ count($product->comments) }} {{ __('Comments') }})</a>
            </div>
            <div class="product-pa-wrapper">
                <div class="product-price">
                    {!! $product->getPriceToString() !!}
                </div>
            </div>
            @if($isInStock && !$hasVariants)
                @livewire('ecommerce.cart.mini', ['product' => $product], key('cart-' . $product->id))
            @elseif($isInStock && $hasVariants)
                <div class="d-grid mt-5">
                    <button class="btn btn-primary btn-ellipse">{{ __('Ver detalle') }}</button>
                </div>
            @else
                <div class="d-grid mt-5">
                    <span class="btn btn-dark btn-ellipse disabled">{{ __('Out stock') }}</span>
                </div>    
            @endif
        </div>
    </div>
</div>

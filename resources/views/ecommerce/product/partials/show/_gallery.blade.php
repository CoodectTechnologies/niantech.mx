<div class="product-gallery product-gallery-sticky {{ count($gallery) >= 2 ? 'product-gallery-vertical' : '' }}">
    <div class="product-single-carousel owl-carousel owl-theme owl-nav-inner row cols-1 gutter-no">
        @foreach($gallery as $image)
            <div class="product-image">
                <img loading="lazy" src="{{ $image }}" data-zoom-image="{{ $image }}" alt="{{ $product->getName() }}" width="800" height="900"/>
            </div>
        @endforeach
    </div>
    <div class="product-thumbs-wrap">
        <div class="product-thumbs">
            @if(count($gallery) >= 2)
                @foreach($gallery as $index => $image)
                    <div class="product-thumb {{ $index === 0 ? 'active' : '' }}">
                        <img loading="lazy" src="{{ $image }}" alt="{{ $product->getName() }}" width="800" height="900"/>
                    </div>
                @endforeach
            @endif
        </div>
        <button class="thumb-up disabled">
            <i class="w-icon-angle-left"></i>
        </button>
        <button class="thumb-down disabled">
            <i class="w-icon-angle-right"></i>
        </button>
    </div>
</div>

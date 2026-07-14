@if($productsViewRecents)
    <div class="mt-10 d-flex justify-content-center">
        <h2 class="subtitle pt-1 ls-normal text-dark mb-5">
            {{ __('Recently viewed') }}
        </h2>
    </div>
    <div class="owl-carousel owl-theme viewed-products row cols-xl-8 cols-lg-6 cols-md-4 cols-2 mb-7"
        data-owl-options="{
        'nav': true,
        'dots': true,
        'margin': 20,
        'responsive': {
            '0': {
                'items': 2
            },
            '576': {
                'items': 3
            },
            '768': {
                'items': 5
            },
            '992': {
                'items': 6
            },
            '1200': {
                'items': 8,
                'dots': true
            }
        }
        }">
        @foreach($productsViewRecents as $productsViewRecent)
            <div class="product-wrap">
                <div class="product text-center product-absolute">
                    <figure class="product-media">
                        <a href="{{ route('ecommerce.product.show', $productsViewRecent) }}">
                            <img loading="lazy" src="{{ $productsViewRecent->imagePreview() }}"
                                alt="{{ $productsViewRecent->name }}" width="300" style="background-color: #fff" />
                        </a>
                    </figure>
                    <h4 class="product-name">
                        <a
                            href="{{ route('ecommerce.product.show', $productsViewRecent) }}">{{ $productsViewRecent->name }}</a>
                    </h4>
                </div>
            </div>
            <!-- End of Product Wrap -->
        @endforeach
    </div>
@endif

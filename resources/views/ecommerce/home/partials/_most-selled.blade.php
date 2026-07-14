@if(count($productsMostSelled))
    <div class="title-link-wrapper mb-3 mt-10">
        <h2 class="title title-deals mb-1">{{ __('Best sellers') }}</h2>
    </div>

    <div class="owl-carousel owl-theme row cols-lg-5 cols-md-4 cols-2 product-deals-wrapper mb-7"
        data-owl-options="{
        'nav': true,
        'dots': true,
        'items': 5,
        'autoplay': false,
        'margin': 20,
        'responsive': {
            '0': {
                'items': 2,
                'nav': false
            },
            '576': {
                'items': 3
            },
            '768': {
                'items': 4
            },
            '992': {
                'items': 5
            }
        }}">
        @foreach($productsMostSelled as $productMostSelled)
            @include('ecommerce.product.partials.index._product', ['product' => $productMostSelled])
        @endforeach
    </div>
    <!-- End of Product Deals Warpper -->
@endif

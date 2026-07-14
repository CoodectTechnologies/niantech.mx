@if(count($productsFeatured))
    <div class="title-link-wrapper mb-3 mt-10">
        <h2 class="subtitle title-deals mb-1">{{ __('Featured') }}</h2>
        <a href="{{ route('ecommerce.product.index') }}?featured=true" class="font-weight-bold ls-25">
            {{ __('More products') }}
            <i class="w-icon-long-arrow-right"></i>
        </a>
    </div>
    <!-- End of .title-link-wrapper -->
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
        @foreach($productsFeatured as $productFeatured)
            @include('ecommerce.product.partials.index._product', ['product' => $productFeatured])
        @endforeach
    </div>
@endif

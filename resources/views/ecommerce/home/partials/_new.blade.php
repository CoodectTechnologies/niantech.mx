@if(count($productsNew))
    <div class="title-link-wrapper mb-3 mt-10">
        <h2 class="title text-left text-capitalize mb-5">{{ __('New products') }}</h2>
        <a href="{{ route('ecommerce.product.index') }}?new=true" class="font-weight-bold ls-25">
            {{ __('More products') }}
            <i class="w-icon-long-arrow-right"></i>
        </a>
    </div>
    <div class="row">
        @foreach($productsNew as $productNew)
            <div class="col-lg-3 col-6">
                @include('ecommerce.product.partials.index._product', ['product' => $productNew])
            </div>
        @endforeach
    </div>
@endif

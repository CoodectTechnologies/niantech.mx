<div class="w-100 d-none d-lg-block">
    <form method="get" action="{{ route('ecommerce.product.index') }}"
        class="header-search hs-expanded hs-round d-none d-md-flex input-wrapper">
        <input wire:model.live.debounce.500ms="search" type="text" value="{{ request()->search }}" autocomplete="off"
            class="form-control" name="search" id="search" placeholder="{{ __('Search') }} ..." required />
        <button class="btn btn-search" type="submit">
            <i wire:loading.remove wire.target="search" class="fa-notdog fa-solid fa-magnifying-glass"></i>
            <span wire:loading.class="spinner-border spinner-border-md" wire:target="search"></span>
        </button>
        @if(strlen($search ?? '') >= $minCharacters)
            {{-- SEARCH DROPDOWN --}}
            <div class="search__dropdown suggestions search__dropdown--open">
                <div class="">
                    <div class="">{{ __('Products') }}</div>
                    @forelse ($products as $product)
                        <div onclick="location='{{ route('ecommerce.product.show', $product) }}'"
                            class="row d-flex align-items-center menu-result-search">
                            <div class="col-lg-3">
                                <img width="50" src="{{ $product->imagePreview() }}"
                                    alt="{{ $product->getName() }}">
                            </div>
                            <div class="col-lg-9">
                                <a href="{{ route('ecommerce.product.show', $product) }}">{{ $product->getName() }}
                                    {!! $product->getPriceToString() !!}</a>
                            </div>
                        </div>
                    @empty
                        <div class="">
                            <p class="">{{ __('No products found') }}</p>
                        </div>
                    @endforelse
                    @if(count($products) >= 5)
                        <div class="">
                            <p class=""><a
                                    href="{{ route('ecommerce.product.index', ['search' => $this->search]) }}">{{ __('See more products') }}</a>
                            </p>
                        </div>
                    @endif
                </div>
                <div class="">
                    <div class="mt-3">{{ __('Categories') }}</div>
                    @forelse ($categories as $category)
                        <div onclick="location='{{ route('ecommerce.product.index', ['category' => $category->slug]) }}'"
                            class="row d-flex align-items-center menu-result-search">
                            <div class="col-lg-3">
                                <img width="50" src="{{ $category->imagePreview() }}" alt="{{ $category->name }}">
                            </div>
                            <div class="col-lg-9">
                                <a href="{{ route('ecommerce.product.index', ['category' => $category->slug]) }}">
                                    {{ $category->name }}
                                    <span class="text-end">({{ count($category->allProductsByCategory()) }})</span>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="">
                            <p class="">{{ __('No categories found') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif
    </form>
</div>

<div wire:ignore.self class="product-details" data-sticky-options="{'minWidth': 767}">
    <span x-text="sku" class="product-category sku"></span>
    <h2 class="product-title">{{ $product->getName() }}</h2>
    <div class="product-price mb-2">
        {!! $priceToString !!}
        @if($product->featured)
            <label class="product-label label-hot ms-3">{{ __('Hot deal') }}</label>
        @endif
    </div>
    <div class="ratings-container">
        <div class="ratings-full">
            <span class="ratings" style="width: {{ $product->getStarsPercentageAVG() }}%;"></span>
            <span class="tooltiptext tooltip-top"></span>
        </div>
        <a href="#product-tab-reviews" class="rating-reviews scroll-to">({{ count($product->comments) }})
            {{ __('Comments') }}</a>
    </div>
    @if(count($product->productAttributes))
        <div class="title-detail mt-5 mb-0">
            {{ __('Specifications') }}
        </div>
    @endif
    <hr class="product-divider mt-2">
    <div class="product-short-desc">
        @if($product->detail)
            <div style="white-space: pre-line;">
                {!! $product->detail !!}
            </div>
        @else
            @foreach($product->productAttributes as $attribute)
                <li>{{ $attribute->value }}</li>
                @if($loop->iteration == 5)
                    @break
                @endif
            @endforeach
        @endif
    </div>
    @if($product->technical_datasheet)
        <a href="{{ Storage::url($product->technical_datasheet) }}" download="download"
            class="btn btn-link btn-primary btn-simple">{{ __('Download technical datasheet') }}</a>
    @endif
    @if($product->getIsDigital())
        <div class=""></div>
        <hr class="product-divider">
        <div class="product-form product-variation-form product-type-swatch">
            <label class="mb-1">{{ __('Type') }}: </label>
            <div class="flex-wrap d-flex align-items-center product-variations w-100">
                <select x-model="type" class="form-control type input-sm">
                    @foreach($this->getTypes() as $t)
                        <option value="{{ $t }}">{{ $t }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif
    
    {{-- Opciones de variantes --}}
    @if(count($this->allOptions) > 0)
        <hr class="product-divider">
        @foreach($this->allOptions as $option)
            <div class="product-form product-variation-form product-size-swatch">
                <label class="mb-1">{{ $option['name'] }}:</label>
                <div class="flex-wrap d-flex align-items-center product-variations">
                    @foreach($option['values'] as $value)
                        <a 
                            x-bind:class="{ 'active': selectedOptions[{{ $option['id'] }}] === {{ $value['id'] }} }"
                            x-on:click="selectOption({{ $option['id'] }}, {{ $value['id'] }}, '{{ $value['value'] }}')"
                            class="size"
                            href="javascript:void(0)"> 
                            {{ $value['value'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
        <button
            x-on:click="clearAllOptions()"
            x-show="Object.keys(selectedOptions).length > 0" 
            type="button" 
            class="product-variation-clean">
            <i class="w-icon-times-circle"></i>
            {{ __('All clear') }}
        </button>
    @endif

    <div x-show="isInStock()" x-cloak>
        <form wire:submit.prevent="saveCart">
            <div class="fix-bottom product-sticky-content sticky-content">
                <div class="product-form container">
                    <div wire:ignore.self x-show="type == 'Físico'" class="product-qty-form">
                        <div class="input-group">
                            <input x-model="quantitySelected" class="quantity form-control" type="number" min="1" x-bind:max="quantityTotal"/>
                            <button x-on:click="addQuantity()" type="button" class="quantity-plus w-icon-plus"></button>
                            <button x-on:click="decrementQuantity()" type="button" class="quantity-minus w-icon-minus"></button>
                        </div>
                        @error('quantitySelected') <small class="form-text text-danger" role="alert">{{ $message }}</small> @enderror
                    </div>
                    <button 
                        wire:target="saveCart" 
                        wire:loading.class="load-more-overlay loading"
                        wire:loading.attr="disabled" 
                        x-bind:disabled="buttonSaveCartDisabled()"
                        class="btn btn-primary btn-cart btn-block" 
                        type="submit">
                        <i class="w-icon-cart"></i>
                        <span>{{ __('Add') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div x-show="!isInStock()" x-cloak>
        <div class="product-stock out-stock">
            {{ __('OUT STOCK') }}
        </div>
    </div>
    <div class="social-links-wrapper">
        <div class="social-links">
            <div class="social-icons social-no-color border-thin">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('ecommerce.product.show', $product) }}"
                    class="social-icon social-facebook w-icon-facebook"></a>
                <a href="whatsapp://send?text={{ $product->slug }}" data-action="share/whatsapp/share"
                    class="social-icon social-whatsapp fab fa-whatsapp" target="_blank"> </a>
            </div>
        </div>
        <span class="divider d-xs-show"></span>
        <div class="product-link-wrapper d-flex">
            @livewire('ecommerce.wishlist.mini', ['product' => $product], key('wishlist-' . $product->id))
            @livewire('ecommerce.compare.mini', ['product' => $product], key('compare-' . $product->id))
        </div>
    </div>
    <div class="row mt-5 col-lg-7">
        @if($product->link_amazon)
            <div class="col-6">
                <a href="{{ $product->link_amazon }}" target="_blank" rel="noopener noreferrer">
                    <img class="img-fluid" src="{{ asset('assets/ecommerce/images/marketplace/amazon.png') }}"
                        alt="Amazon">
                </a>
            </div>
        @endif
        @if($product->link_mercadolibre)
            <div class="col-6">
                <a href="{{ $product->link_mercadolibre }}" target="_blank" rel="noopener noreferrer">
                    <img class="img-fluid" src="{{ asset('assets/ecommerce/images/marketplace/mercadolibre.png') }}"
                        alt="Mercado libre">
                </a>
            </div>
        @endif
    </div>
</div>

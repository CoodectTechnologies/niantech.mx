<ul x-show="open" x-transition class="categories">
    @foreach($productCategories as $productCategory)
        @if($productCategory->productsCount)
            <li style="">
                <div class="form-group d-flex justify-content-between align-items-center">
                    <input wire:model.live="categories" wire:loading.attr="disabled" wire:target="categories" type="checkbox"
                        class="custom-checkbox" id="categories-{{ $productCategory->id }}" multiple name="categories[]"
                        value="{{ $productCategory->slug }}">
                    <label class="{{ in_array($productCategory->slug, $categories) ? 'active' : '' }}"
                        for="categories-{{ $productCategory->id }}">{{ $productCategory->name }}</label>
                    @if(!isset($productCategory->childrens))
                        <span class="text-end">{{ $productCategory->productsCount }}</span>
                    @endif
                </div>
            </li>
            @if(isset($productCategory->childrens) && count($productCategory->childrens))
                @include('ecommerce.product.partials.index._category', [
                    'productCategories' => $productCategory->childrens,
                ])
            @endif
        @endif
    @endforeach
</ul>

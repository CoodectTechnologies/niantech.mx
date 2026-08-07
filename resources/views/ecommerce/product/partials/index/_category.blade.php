<ul x-show="open" x-transition class="categories">
    @foreach($productCategories as $productCategory)
        @if($productCategory->productsCount)
            <li x-data="{ open: false }">
                <div class="form-group d-flex justify-content-between align-items-center mb-3">
                    <input wire:model.live="categories" wire:loading.attr="disabled" wire:target="categories" type="checkbox"
                        class="custom-checkbox" id="categories-{{ $productCategory->id }}" multiple name="categories[]"
                        value="{{ $productCategory->slug }}">
                    <label class="{{ in_array($productCategory->slug, $categories) ? 'active' : '' }}"
                        for="categories-{{ $productCategory->id }}">{{ $productCategory->name }}</label>
                    @if(!isset($productCategory->childrens))
                        <span class="text-end">{{ $productCategory->productsCount }}</span>
                    @endif
                    @if(isset($productCategory->childrens) && count($productCategory->childrens))
                        <i x-on:click.prevent="open = !open" x-show="!open"
                            class="fa fa-chevron-down"></i>
                        <i x-on:click.prevent="open = !open" x-show="open"
                            class="fa fa-chevron-up"></i>
                    @endif
                </div>
                @if(isset($productCategory->childrens) && count($productCategory->childrens))
                    @include('ecommerce.product.partials.index._category', [
                        'productCategories' => $productCategory->childrens,
                    ])
                @endif
            </li>
        @endif
    @endforeach
</ul>

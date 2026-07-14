<div>
    @if (count($categoriesFhater))
        <div class="mt-10">
            <div class="d-flex justify-content-center">
                <h2 class="subtitle pt-1 ls-normal text-dark mb-0">{{ __('Explora nuestras categorías') }}</h2>
            </div>
            <p class="mx-auto text-center">{{ __('Encuentra todo lo que necesitas para equipar tu oficina o negocio') }}</p>
        </div>
        
        <section class="pt-0 pb-10">
            <div class="mt-1 mb-2">
                <div class="owl-carousel owl-theme" data-owl-options="{
                    'nav': true,
                    'dots': false,
                    'margin': 20,
                    'padding-bottom': 10,
                    'responsive': {
                        '0': {
                            'items': 2
                        },
                        '576': {
                            'items': 3
                        },
                        '768': {
                            'items': 4
                        },
                        '992': {
                            'items': 5
                        },
                        '1200': {
                            'items': 6
                        }
                    }
                }">
                    @foreach ($categoriesFhater as $category)
                        <div class="category category-classic category-absolute overlay-zoom br-xs shadow-sm">
                            <a href="{{ route('ecommerce.product.index', ['category' => $category->slug]) }}">
                                <figure class="category-media">
                                    <img src="{{ $category->image }}" alt="{{ $category->name }}"
                                        width="190" height="184" />
                                </figure>
                            </a>
                            <div class="category-content">
                                <h4 class="category-name">{{ $category->name }}</h4>
                                <a href="{{ route('ecommerce.product.index', ['category' => $category->slug]) }}" class="btn btn-primary btn-link btn-underline">{{ __('Ver productos') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>

<div>
    <!-- Start of Page Content -->
    <div class="page-content pb-10 mb-2">
        <!-- End of Category Section .category-with-icons -->
        <section class="category-masonry-section mb-10">
            <h4 class="title title-center mb-7">{{ __('All categories') }}</h4>
            <div class="category-grid">
                @foreach($categoriesFather as $category)
                    <div class="grid-item2">
                        <div class="category category-banner category-absolute overlay-zoom overlay-dark br-sm">
                            <a href="{{ route('ecommerce.product.index', ['category' => $category->slug]) }}">
                                <figure class="category-media">
                                    <img loading="lazy" src="{{ $category->image }}" alt="{{ $category->name }}" />
                                </figure>
                                <div class="category-content">
                                    <h4 class="category-name">{{ $category->name }}</h4>
                                </div>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
</div>

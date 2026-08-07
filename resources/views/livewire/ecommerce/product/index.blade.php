<div>
    <div class="page-content mb-10">
        <div class="container">
            <!-- Start of Shop Content -->
            <div class="shop-content row gutter-lg">
                <!-- Start of Sidebar, Shop Sidebar -->
                <aside class="sidebar shop-sidebar sticky-sidebar-wrapper sidebar-fixed">
                    <!-- Start of Sidebar Overlay -->
                    <div class="sidebar-overlay"></div>
                    <a class="sidebar-close" href="#"><i class="close-icon"></i></a>
                    <!-- Start of Sidebar Content -->
                    <div class="sidebar-content scrollable">
                        <!-- Start of Sticky Sidebar -->
                        <div class="sticky-sidebar">
                            <div class="filter-actions">
                                @if($this->existAnyFilter())
                                    <a wire:click.prevent="clearFilter" href="#"
                                        class="btn btn-dark btn-link filter-clean">{{ __('All clear') }}</a>
                                    <span wire:loading.class="spinner-border spinner-border-sm ms-3"
                                        wire:target="clearFilter"></span>
                                @endif
                            </div>
                            <div class="widget widget-search-form">
                                <div class="widget-body">
                                    <div class="input-wrapper input-wrapper-inline">
                                        <input wire:model.live.debounce.500ms="searchCustom" type="search" class="form-control"
                                            placeholder="{{ __('Search...') }}" autocomplete="off" required="">
                                        <button class="btn btn-search" type="button">
                                            <i wire:loading.remove wire:target="searchCustom" class="w-icon-search"></i>
                                            <span wire:loading.class="spinner-border spinner-border-sm ms-3"
                                                wire:target="searchCustom"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Start of Collapsible widget -->
                            <div class="widget widget-collapsible">
                                @if($category)
                                    <h3 wire:ignore.self class="widget-title">
                                        <span>{{ $category->name }}</span>
                                        <span wire:loading.class="spinner-border spinner-border-sm ms-3"
                                            wire:target="categories"></span>
                                    </h3>
                                @else
                                    <h3 class="widget-title">
                                        <span>{{ __('All categories') }}</span>
                                        <span wire:loading.class="spinner-border spinner-border-sm ms-3"
                                            wire:target="categories"></span>
                                    </h3>
                                @endif
                                <ul class="widget-body categories search-ul">
                                    @foreach($productCategories as $productCategory)
                                        @if($productCategory->productsCount)
                                            <li x-data="{ open: false }">
                                                <div
                                                    class="form-group d-flex justify-content-between align-items-center mb-3">
                                                    <input wire:key="{{ $productCategory->slug }}"
                                                        wire:model.live="categories" 
                                                        wire:loading.attr="disabled"
                                                        wire:target="categories" 
                                                        type="checkbox"
                                                        class="custom-checkbox"
                                                        id="categories-{{ $productCategory->id }}" 
                                                        multiple
                                                        name="categories[]" 
                                                        value="{{ $productCategory->slug }}">
                                                    <label
                                                        class="{{ in_array($productCategory->slug, $categories) ? 'active' : '' }}"
                                                        for="categories-{{ $productCategory->id }}">
                                                        {{ $productCategory->name }}
                                                    </label>
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
                            </div>
                            <!-- End of Collapsible Widget -->
                            <!-- Start of Collapsible Widget -->
                            <div class="widget widget-collapsible">
                                <h3 wire:ignore.self class="widget-title">
                                    <span>{{ __('Price') }}</span>
                                    <span wire:loading.class="spinner-grow spinner-grow-sm"
                                        wire:target="filterPrice"></span>
                                </h3>
                                <div class="widget-body">
                                    <ul class="filter-items search-ul">
                                        <li wire:click.prevent="filterPrice(0, 500)"><a href="#">$0.00 -
                                                $500.00</a></li>
                                        <li wire:click.prevent="filterPrice(500, 1000)"><a href="#">$500.00 -
                                                $1000.00</a></li>
                                        <li wire:click.prevent="filterPrice(1000, 2000)"><a href="#">$1000.00 -
                                                $2000.00</a></li>
                                        <li wire:click.prevent="filterPrice(2000, 5000)"><a href="#">$2000.00 -
                                                $5000.00</a></li>
                                        <li wire:click.prevent="filterPrice(5000, '')"><a href="#">$5000.00+</a>
                                        </li>
                                    </ul>
                                    <form class="price-range" action="{{ route('ecommerce.product.index') }}">
                                        <input wire:model="minPrice" type="number" name="minPrice"
                                            class="min_price text-center" placeholder="$min">
                                        <span class="delimiter">-</span>
                                        <input wire:model="maxPrice" type="number" name="maxPrice"
                                            class="max_price text-center" placeholder="$max">
                                        <button type="submit"
                                            class="btn btn-primary btn-rounded">{{ __('Apply') }}</button>
                                    </form>
                                </div>
                            </div>
                            <!-- End of Collapsible Widget -->
                            <!-- Start of Collapsible widget -->
                            <div class="widget widget-collapsible">
                                <h3 wire:ignore.self class="widget-title">
                                    <span>{{ __('Brand') }}</span>
                                    <span wire:loading.class="spinner-border spinner-border-sm ms-3"
                                        wire:target="brands"></span>
                                </h3>
                                <ul class="widget-body filter-items search-ul">
                                    @foreach($productBrands as $productBrand)
                                        <li>
                                            <div class="form-group">
                                                <input wire:key="{{ $productBrand->id }}" 
                                                    wire:model.live="brands"
                                                    wire:loading.attr="disabled" 
                                                    wire:target="brands" 
                                                    type="checkbox"
                                                    class="custom-checkbox" 
                                                    id="brands-{{ $productBrand->id }}"
                                                    multiple 
                                                    name="brands[]" 
                                                    value="{{ $productBrand->id }}">
                                                <label
                                                    class="{{ in_array($productBrand->id, $brands) ? 'active' : '' }}"
                                                    for="brands-{{ $productBrand->id }}">{{ $productBrand->name }}</label>
                                                <span class="text-end">{{ count($productBrand->products) }}</span>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <!-- End of Collapsible Widget -->
                            @if(count($productGenders))
                                <!-- Start of Collapsible widget -->
                                <div class="widget widget-collapsible">
                                    <h3 class="widget-title">
                                        <span>{{ __('Genders') }}</span>
                                    </h3>
                                    <ul class="widget-body filter-items search-ul">
                                        @foreach($productGenders as $productGender)
                                            <li>
                                                <a
                                                    href="{{ route('ecommerce.product.index', ['gender' => $productGender->slug]) }}">
                                                    {{ $productGender->name }}
                                                    <span
                                                        class="text-end">{{ count($productGender->products) }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <!-- End of Collapsible Widget -->
                            @endif
                            @foreach($bannersSidebar as $bannerSidebar)
                                <div class="widget widget-banner mb-9">
                                    <div class="banner banner-fixed br-sm">
                                        <figure>
                                            @if($bannerSidebar->btn_url)
                                                <a target="_blank" href="{{ $bannerSidebar->btn_url }}">
                                            @endif
                                            <img loading="lazy" src="{{ $bannerSidebar->imagePreview() }}"
                                                alt="{{ $bannerSidebar->title }}" width="266" height="220"
                                                style="background-color: #1D2D44;">
                                            @if($bannerSidebar->btn_url)
                                                </a>
                                            @endif
                                        </figure>
                                        <div class="banner-content">
                                            @if($bannerSidebar->title)
                                                <div
                                                    class="banner-price-info font-weight-bolder lh-1 ls-25">
                                                    {{ $bannerSidebar->title }}
                                                </div>
                                            @endif
                                            @if($bannerSidebar->subtitle)
                                                <h4
                                                    class="banner-subtitle font-weight-bolder text-uppercase mb-0">
                                                    {{ $bannerSidebar->subtitle }}
                                                </h4>
                                            @endif
                                            @if($bannerSidebar->btn_text)
                                                <a target="_blank" href="{{ $bannerSidebar->btn_url }}"
                                                    class="btn btn-primary mt-5">{{ $bannerSidebar->btn_text }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- End of Sidebar Content -->
                    </div>
                    <!-- End of Sidebar Content -->
                </aside>
                <!-- End of Shop Sidebar -->
                <!-- Start of Main Content -->
                <div class="main-content">
                    <nav wire:ignore.self class="mt-5 toolbox sticky-toolbox sticky-content fix-top">
                        <div wire:ignore.self class="toolbox-left">
                            <a wire:ignore href="#"
                                class="btn btn-primary btn-outline btn-rounded left-sidebar-toggle btn-icon-left d-block d-lg-none"><i
                                    class="w-icon-category"></i><span>{{ __('Filters') }}</span></a>
                            <div class="toolbox-item toolbox-sort select-box text-dark">
                                <label>{{ __('Sort by') }} :</label>
                                <select wire:model.live="orderBy" name="orderBy" class="form-control">
                                    <option value="">{{ __('Default sorting') }}</option>
                                    <option value="featured">{{ __('Featured') }}</option>
                                    <option value="name-asc">{{ __('A - Z') }}</option>
                                    <option value="name-desc">{{ __('Z - A') }}</option>
                                    <option value="price-low">{{ __('Price: low to high') }}</option>
                                    <option value="price-high">{{ __('Price: high to low') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="toolbox-right">
                            <div class="toolbox-item toolbox-show select-box">
                                <select wire:model.live="perPage" name="perPage" class="form-control">
                                    <option value="9">{{ __('Show') }} 9</option>
                                    <option value="12">{{ __('Show') }} 12</option>
                                    <option value="24">{{ __('Show') }} 24</option>
                                    <option value="36">{{ __('Show') }} 36</option>
                                    <option value="50">{{ __('Show') }} 50</option>
                                </select>
                            </div>
                        </div>
                    </nav>
                    <div class="product-wrapper row cols-md-3 cols-sm-2 cols-2">
                        @forelse ($products as $product)
                            @include('ecommerce.product.partials.index._product', ['product' => $product])
                        @empty
                            <div class="alert alert-warning alert-simple alert-inline">
                                <h4 class="alert-title">{{ __('No products found') }}</h4>
                                @if($searchCustom)
                                    {{ $searchCustom }}
                                @elseif(request()->search)
                                    {{ request()->search }}
                                @endif
                            </div>
                        @endforelse
                    </div>
                    <div class="toolbox toolbox-pagination justify-content-between">
                        <p class="showing-info mb-2 mb-sm-0">
                            {{ __('Showing') }}
                            <span>
                                {{ ($products->currentpage() - 1) * $products->perpage() + 1 }} -
                                {{ $products->currentpage() * $products->perpage() }} {{ __('of') }}
                                {{ $products->total() }}
                            </span>
                            {{ __('Products') }}
                        </p>
                        {{ $products->withQueryString()->links() }}
                    </div>
                </div>
                <!-- End of Main Content -->
            </div>
            <!-- End of Shop Content -->
        </div>
    </div>
    @push('footer')
        <script>
            document.addEventListener('livewire:update', function() {
                Coodect.sidebar('sidebar');
            });
        </script>
    @endpush
</div>

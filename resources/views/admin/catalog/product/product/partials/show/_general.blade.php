<!--begin::Card header-->
<div class="card-header cursor-pointer">
    <!--begin::Card title-->
    <div class="card-title m-0">
        <h3 class="fw-bolder m-0">{{ __('Details') }}</h3>
    </div>
    <!--end::Card title-->
</div>
<!--begin::Card header-->
<!--begin::Card body-->
<div class="card-body p-9">
    <!--begin::Input group-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">ID:</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bold fs-6 text-gray-800">{{ $product->id }}</span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Provider') }}:</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bold fs-6 text-gray-800">{{ $product->provider }} {{ $product->provider_id ? '('.$product->provider_id.')' : 'N/A' }}</span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('User who registered the product') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bold fs-6 text-gray-800">{{ $product->user ? $product->user->name : 'N/A' }}</span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    <!--begin::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Categories') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">
                @foreach($product->productCategories as $category)
                    <span class="badge badge-primary">{{ $category->name }}</span>
                @endforeach
            </span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Gender') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">
                @forelse ($product->productGenders as $productGender)
                    <a class="badge badge-success"
                        href="{{ route('admin.catalog.gender.index', ['search' => $productGender->name]) }}">{{ $productGender->name }}</a>
                @empty
                    N/A
                @endforelse
            </span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Brand') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">
                @if($product->productBrand)
                    <a class="badge badge-success"
                        href="{{ route('admin.catalog.brand.index', ['search' => $product->productBrand->name]) }}">{{ $product->productBrand->name }}</a>
                @else
                    N/A
                @endif
            </span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Shipping class') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">
                @if($product->shippingClass)
                    <a
                        href="{{ route('admin.setting.shipping-class', ['search' => $product->shippingClass->name]) }}">{{ $product->shippingClass->name }}</a>
                @else
                    N/A
                @endif
            </span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Unit type') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">
                @if($product->unitType)
                    <span>{{ $product->unitType->name }}</span>
                @else
                    N/A
                @endif
            </span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Price by session') }} ({{ Session::get('currency') }})
        </label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">
                {!! $product->getPriceToString() !!}
            </span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Price original') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">
                {{ $product->currency->symbol }}{{ number_format($product->price, 2) }}
                {{ $product->currency->code }}
            </span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Cost') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">
                {{ $product->currency->symbol }}{{ number_format($product->cost, 2) }} {{ $product->currency->code }}
            </span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
</div>
<!--end::Card body-->

<!--begin::Card header-->
<div class="card-header cursor-pointer">
    <!--begin::Card title-->
    <div class="card-title m-0">
        <h3 class="fw-bolder m-0">{{ __('Warehouse') }}</h3>
    </div>
    <!--end::Card title-->
</div>
<!--begin::Card header-->
<!--begin::Card body-->
<div class="card-body p-9">
    @foreach($product->productWarehouses as $warehouse)
        <!--begin::Input group-->
        <div class="row mb-7">
            <!--begin::Label-->
            <label class="col-lg-4 fw-bold text-muted">
                @if($warehouse->provider)
                    {{ $warehouse->provider }} -
                @endif
                {{ $warehouse->name }}
            </label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-8">
                <span class="fw-bold fs-6 text-gray-800">{{ $warehouse->pivot->quantity }}</span>
            </div>
            <!--end::Col-->
        </div>
        <!--end::Input group-->
    @endforeach
</div>

<!--begin::Card header-->
<div class="card-header cursor-pointer">
    <!--begin::Card title-->
    <div class="card-title m-0">
        <h3 class="fw-bolder m-0">{{ __('General data') }}</h3>
    </div>
    <!--end::Card title-->
</div>
<!--begin::Card header-->
<!--begin::Card body-->
<div class="card-body p-9">
    <!--begin::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">URL</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            @if(Route::has('ecommerce.product.index'))
                <span class="fw-bolder fs-6 text-gray-800"><a href="{{ route('ecommerce.product.show', $product) }}"
                        target="_blank"
                        rel="noopener noreferrer">{{ route('ecommerce.product.show', $product) }}</a></span>
            @endif
            @if(Route::has('web.product.index'))
                <span class="fw-bolder fs-6 text-gray-800"><a href="{{ route('web.product.show', $product) }}"
                        target="_blank" rel="noopener noreferrer">{{ route('web.product.show', $product) }}</a></span>
            @endif
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Name of product') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">{{ $product->name }}</span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Name commercial') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">{{ $product->name_commercial ?? 'N/A' }}</span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('SKU') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">{{ $product->sku }}</span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Advanced search phrases') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800" style="white-space: pre-line;">{!! $product->search_advanced !!}</span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Quantity') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">{{ $product->getQuantityTotal() }}</span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Input group-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Featured') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">{{ $product->featured ? __('Yes') : 'No' }}</span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Status') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">{{ $product->status }}</span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Type') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">{{ $product->type }}</span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Wholesale rule to which it applies') }}
        </label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            @if($wholesale = $product->getWholesale())
                <span class="fw-bolder fs-6 text-primary-800">
                    <a href="{{ route('admin.wholesale.edit', $wholesale) }}">{{ $wholesale->name }}</a>
                </span>
            @else
                <span class="fw-bolder fs-6 text-gray-800">N/A</span>
            @endif
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Promotion to which you apply') }}
        </label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            @if($promotion = $product->getPromotion())
                <span class="fw-bolder fs-6 text-primary-800">
                    <a href="{{ route('admin.promotion.edit', $promotion) }}">{{ $promotion->name }}
                        ({{ $promotion->percetage }}%)</a>
                </span>
            @else
                <span class="fw-bolder fs-6 text-gray-800">N/A</span>
            @endif
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">Link Amazon</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800"><a href="{{ $product->link_amazon }}" target="_blank"
                    rel="noopener noreferrer">{{ $product->link_amazon }}</a></span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">Link Mercado pago</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800"><a href="{{ $product->link_mercadolibre }}" target="_blank"
                    rel="noopener noreferrer">{{ $product->link_mercadolibre }}</a></span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Is it downloadable?') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">{{ $product->getIsDownloadable() ? __('Yes') : 'NO' }}</span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-10">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('File digital') }}</label>
        <!--begin::Label-->
        <!--begin::Label-->
        <div class="col-lg-8">
            <span class="fw-bold fs-6 text-gray-800">
                @if($product->file_digital)
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" width="100%" height="400px" allowfullscreen
                            src="{{ Storage::url($product->file_digital) }}"></iframe>
                    </div>
                @endif
            </span>
        </div>
        <!--begin::Label-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-10">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">Video Iframe</label>
        <!--begin::Label-->
        <!--begin::Label-->
        <div class="col-lg-8">
            <span class="fw-bold fs-6 text-gray-800">
                {!! $product->iframe_url !!}
            </span>
        </div>
        <!--begin::Label-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-10">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Data sheet') }}</label>
        <!--begin::Label-->
        <!--begin::Label-->
        <div class="col-lg-8">
            <span class="fw-bold fs-6 text-gray-800">
                @if($product->technical_datasheet)
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" width="100%" height="400px" allowfullscreen
                            src="{{ Storage::url($product->technical_datasheet) }}"></iframe>
                    </div>
                @endif
            </span>
        </div>
        <!--begin::Label-->
    </div>
    <!--end::Input group-->
    <!--begin::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Detail') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800" style="white-space: pre-line;">{!! $product->detail !!}</span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
    <!--begin::Row-->
    <div class="row mb-7">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Description') }}</label>
        <!--end::Label-->
        <!--begin::Col-->
        <div class="col-lg-8">
            <span class="fw-bolder fs-6 text-gray-800">{!! $product->description !!}</span>
        </div>
        <!--end::Col-->
    </div>
    <!--end::Row-->
</div>
<!--end::Card body-->

@if($product->productVariants->count() > 0)
    <!--begin::Card header-->
    <div class="card-header cursor-pointer">
        <!--begin::Card title-->
        <div class="card-title m-0">
            <h3 class="fw-bolder m-0">
                <i class="fa-light fa-boxes-stacked me-2"></i>{{ __('Product Variants') }}
                <span class="badge badge-primary ms-2">{{ $product->productVariants->count() }}</span>
            </h3>
        </div>
        <!--end::Card title-->
    </div>
    <!--begin::Card header-->
    <!--begin::Card body-->
    <div class="card-body p-9">
        <div class="row g-4">
            @foreach($product->productVariants->sortBy('position') as $variantIndex => $variant)
                <!--begin::Variant Card-->
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100 {{ !$variant->is_active ? 'opacity-75' : '' }}">
                        {{-- Header --}}
                        <div class="card-header bg-light-primary py-3">
                            <h5 class="card-title mb-0 text-primary fw-bold">
                                {{ $variant->getOptionValuesLabel() }}
                            </h5>
                            <div class="mt-2">
                                @if($variant->is_active)
                                    <span class="badge badge-success">{{ __('Active') }}</span>
                                @else
                                    <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Body --}}
                        <div class="card-body">
                            {{-- Galería --}}
                            @if($variant->images->count() > 0)
                                <div class="mb-4">
                                    <label class="text-muted fw-bold fs-7 mb-2">
                                        <i class="fa-light fa-images me-1"></i>{{ __('Gallery') }}
                                    </label>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($variant->images->take(4) as $galleryImage)
                                            <div class="symbol symbol-60px" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#galleryModal{{ $variant->id }}">
                                                <img src="{{ $galleryImage->imagePreview() }}" alt="{{ $variant->sku }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                            </div>
                                        @endforeach
                                        @if($variant->images->count() > 4)
                                            <div class="symbol symbol-60px" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#galleryModal{{ $variant->id }}">
                                                <span class="symbol-label bg-light-primary text-primary fw-bold fs-7">
                                                    +{{ $variant->images->count() - 4 }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    {{-- Modal Galería --}}
                                    <div class="modal fade" id="galleryModal{{ $variant->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-xl modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header bg-primary">
                                                    <h5 class="text-white mb-0">
                                                        <i class="fa-light fa-images me-2"></i>
                                                        {{ __('Gallery') }}: {{ $variant->getOptionValuesLabel() }}
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        @foreach($variant->images as $galleryImage)
                                                            <div class="col-md-3 col-sm-4 col-6">
                                                                <a href="{{ $galleryImage->imagePreview() }}" target="_blank">
                                                                    <img src="{{ $galleryImage->imagePreview() }}" 
                                                                         class="w-100 rounded shadow-sm" 
                                                                         style="aspect-ratio: 1; object-fit: cover;">
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- SKU --}}
                            <div class="mb-3">
                                <label class="text-muted fw-bold fs-7 mb-1">
                                    <i class="fa-light fa-barcode me-1"></i>{{ __('SKU') }}
                                </label>
                                <div class="text-gray-800 fw-bold">{{ $variant->sku }}</div>
                            </div>

                            {{-- Precio --}}
                            <div class="mb-3">
                                <label class="text-muted fw-bold fs-7 mb-1">
                                    <i class="fa-light fa-dollar-sign me-1"></i>{{ __('Price') }}
                                </label>
                                <div>
                                    <span class="text-gray-800 fw-bold fs-5">
                                        {{ $product->currency->symbol }}{{ number_format($variant->price, 2) }}
                                    </span>
                                    @if($variant->price_promotion)
                                        <div class="text-success fw-bold mt-1">
                                            <i class="fa-light fa-tag me-1"></i>
                                            {{ __('Promo') }}: {{ $product->currency->symbol }}{{ number_format($variant->price_promotion, 2) }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Costo --}}
                            <div class="mb-3">
                                <label class="text-muted fw-bold fs-7 mb-1">
                                    <i class="fa-light fa-coins me-1"></i>{{ __('Cost') }}
                                </label>
                                <div class="text-gray-800 fw-bold">
                                    {{ $product->currency->symbol }}{{ number_format($variant->cost, 2) }}
                                </div>
                            </div>

                            {{-- Stock --}}
                            <div class="mb-3">
                                <label class="text-muted fw-bold fs-7 mb-1">
                                    <i class="fa-light fa-boxes-stacked me-1"></i>{{ __('Stock Total') }}
                                </label>
                                <div>
                                    <span class="badge badge-light-primary fs-6">{{ $variant->getQuantityTotal() }}</span>
                                </div>
                            </div>

                            {{-- Warehouses --}}
                            @if($variant->productWarehouses->count() > 0)
                                <div class="mb-3">
                                    <label class="text-muted fw-bold fs-7 mb-2">
                                        <i class="fa-light fa-warehouse me-1"></i>{{ __('Warehouse') }}
                                    </label>
                                    <div class="d-flex flex-column gap-1">
                                        @foreach($variant->productWarehouses as $vw)
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-gray-700 fs-8">
                                                    {{ $vw->name }}
                                                </span>
                                                <span class="badge badge-light">{{ $vw->pivot->quantity }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Dimensiones --}}
                            @if($variant->height || $variant->width || $variant->length || $variant->weight_kl)
                                <div class="mb-3">
                                    <label class="text-muted fw-bold fs-7 mb-2">
                                        <i class="fa-light fa-ruler-combined me-1"></i>{{ __('Dimensions') }}
                                    </label>
                                    <div class="text-gray-800 fs-8">
                                        @if($variant->weight_kl)
                                            <div><strong>{{ __('Weight') }}:</strong> {{ $variant->weight_kl }} kg</div>
                                        @endif
                                        @if($variant->height)
                                            <div><strong>{{ __('Height') }}:</strong> {{ $variant->height }} cm</div>
                                        @endif
                                        @if($variant->width)
                                            <div><strong>{{ __('Width') }}:</strong> {{ $variant->width }} cm</div>
                                        @endif
                                        @if($variant->length)
                                            <div><strong>{{ __('Length') }}:</strong> {{ $variant->length }} cm</div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!--end::Variant Card-->
            @endforeach
        </div>
    </div>
    <!--end::Card body-->
@endif

<!--begin::Card header-->
<div class="card-header cursor-pointer">
    <!--begin::Card title-->
    <div class="card-title m-0">
        <h3 class="fw-bolder m-0">{{ __('Shipping information') }}</h3>
    </div>
    <!--end::Card title-->
</div>
<!--begin::Card header-->
<!--begin::Card body-->
<div class="card-body p-9">
    <!--begin::Input group-->
    <div class="row mb-10">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Weight') }} (KL)</label>
        <!--begin::Label-->
        <!--begin::Label-->
        <div class="col-lg-8">
            <span class="fw-bold fs-6 text-gray-800">{{ $product->weight_kl }}</span>
        </div>
        <!--begin::Label-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-10">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Height') }}</label>
        <!--begin::Label-->
        <!--begin::Label-->
        <div class="col-lg-8">
            <span class="fw-bold fs-6 text-gray-800">{{ $product->height }}</span>
        </div>
        <!--begin::Label-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-10">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Width') }}</label>
        <!--begin::Label-->
        <!--begin::Label-->
        <div class="col-lg-8">
            <span class="fw-bold fs-6 text-gray-800">{{ $product->width }}</span>
        </div>
        <!--begin::Label-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-10">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Length') }}</label>
        <!--begin::Label-->
        <!--begin::Label-->
        <div class="col-lg-8">
            <span class="fw-bold fs-6 text-gray-800">{{ $product->length }}</span>
        </div>
        <!--begin::Label-->
    </div>
    <!--end::Input group-->
</div>
<!--end::Card body-->

<!--begin::Card header-->
<div class="card-header cursor-pointer">
    <!--begin::Card title-->
    <div class="card-title m-0">
        <h3 class="fw-bolder m-0">{{ __('Meta tags') }}</h3>
    </div>
    <!--end::Card title-->
</div>
<!--begin::Card header-->
<!--begin::Card body-->
<div class="card-body p-9">
    <!--begin::Input group-->
    <div class="row mb-10">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Meta tag title') }}</label>
        <!--begin::Label-->
        <!--begin::Label-->
        <div class="col-lg-8">
            <span class="fw-bold fs-6 text-gray-800">{{ $product->meta_title }}</span>
        </div>
        <!--begin::Label-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-10">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Meta tag description') }}</label>
        <!--begin::Label-->
        <!--begin::Label-->
        <div class="col-lg-8">
            <span class="fw-bold fs-6 text-gray-800">{{ $product->meta_description }}</span>
        </div>
        <!--begin::Label-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row mb-10">
        <!--begin::Label-->
        <label class="col-lg-4 fw-bold text-muted">{{ __('Meta tag keywords') }}</label>
        <!--begin::Label-->
        <!--begin::Label-->
        <div class="col-lg-8">
            <span class="fw-bold fs-6 text-gray-800">{{ $product->meta_keywords }}</span>
        </div>
        <!--begin::Label-->
    </div>
    <!--end::Input group-->
</div>
<!--end::Card body-->

<!--begin::Card header-->
<div class="card-header cursor-pointer">
    <!--begin::Card title-->
    <div class="card-title m-0">
        <h3 class="fw-bolder m-0">{{ __('Gallery') }}</h3>
    </div>
    <!--end::Card title-->
</div>
<!--begin::Card header-->
<!--begin::Card body-->
<div class="card-body p-9">
    <!--end::Input group-->
    <div class="row">
        @foreach($product->imagesPreview() as $image)
            <div class="col-lg-6">
                <!--begin::Overlay-->
                <a class="d-block overlay m-5" data-fslightbox="lightbox-basic" href="{{ $image }}">
                    <!--begin::Image-->
                    <div class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px"
                        style="background-image:url('{{ $image }}')">
                    </div>
                    <!--end::Image-->

                    <!--begin::Action-->
                    <div class="overlay-layer card-rounded bg-dark bg-opacity-25 shadow">
                        <i class="bi bi-eye-fill text-white fs-3x"></i>
                    </div>
                    <!--end::Action-->
                </a>
                <!--end::Overlay-->
            </div>
        @endforeach
    </div>
</div>

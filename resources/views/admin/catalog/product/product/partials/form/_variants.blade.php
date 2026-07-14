<div class="card card-flush py-4">
    <div class="card-header">
        <div class="card-title">
            <h2>{{ __('Product Variants') }}</h2>
        </div>
    </div>
    <div class="card-body pt-0">
        {{-- SWITCH --}}
        <div class="form-check form-switch form-check-custom form-check-solid mb-5">
            <input x-on:click="toogleHasVariants" @checked($hasVariants) class="form-check-input" type="checkbox" id="hasVariants">
            <label class="form-check-label fw-bold text-gray-700" for="hasVariants">
                {{ __('This product has multiple options, like different sizes or colors') }}
            </label>
        </div>
        {{-- VARIANTS FORM --}}
        <div x-show="hasVariants" x-cloak x-transition>
            {{-- OPTIONS --}}
            <div class="mb-7">
                <h3 class="fw-bold mb-4">{{ __('Options') }}</h3>
                <template x-for="(option, optionIndex) in productOptions" :key="'option-' + optionIndex">
                    <div class="border border-gray-300 rounded p-5 mb-4">
                        <div class="d-flex justify-content-between mb-4">
                            <h4 class="fw-bold mb-0" x-text="'{{ __('Option') }} ' + (optionIndex + 1)"></h4>
                            <button x-on:click="removeOption(optionIndex)"
                                    x-show="productOptions.length > 1"
                                    type="button"
                                    class="btn btn-sm btn-light-danger">
                                <i class="fa-light fa-trash"></i>
                                {{ __('Remove') }}
                            </button>
                        </div>
                        <div class="mb-4">
                            <label class="form-label required">
                                {{ __('Option name') }}
                            </label>
                            <input
                                x-on:blur="generateVariantsDebounced()"
                                x-model="option.name"
                                type="text"
                                class="form-control form-control-sm"
                                placeholder="Color, Size...">
                        </div>
                        <div class="mb-2">
                            <label class="form-label required">
                                {{ __('Option values') }}
                            </label>
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <template x-for="(value, valueIndex) in option.values" :key="'value-' + optionIndex + '-' + valueIndex">
                                    <div class="badge badge-light-primary d-flex align-items-center gap-2 py-2 px-3">
                                        <input 
                                            x-on:input="updateValue(optionIndex, valueIndex, $event.target.value)"
                                            x-on:keydown.enter.prevent="focusNextValue(optionIndex, valueIndex, $event)"
                                            x-on:blur="generateVariantsDebounced()"
                                            x-bind:value="value"
                                            type="text"
                                            class="border-0 bg-transparent fw-bold text-primary"
                                            style="width: 90px; outline: none;"
                                            placeholder="Value">
                                        <button 
                                            x-on:click="removeValue(optionIndex, valueIndex)"
                                            type="button"
                                            class="btn btn-sm btn-icon btn-light-danger p-0">
                                            <i class="fa-light fa-trash"></i>
                                        </button>
                                    </div>
                                </template>
                                <button 
                                    x-on:click="addValue(optionIndex)"
                                    type="button"
                                    class="btn btn-sm btn-light-primary">
                                    <i class="fa-light fa-plus"></i>
                                    {{ __('Add value') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
                <button x-on:click="addOption()"
                        type="button"
                        class="btn btn-light-primary w-100">
                    <i class="fa-light fa-plus"></i>
                    {{ __('Add another option') }}
                </button>
            </div>
            {{-- VARIANTS --}}
            @if(count($productVariants))
                <div class="mb-7">
                    <h3 class="fw-bold mb-4">
                        {{ __('Variants') }} ({{ count($productVariants) }})
                    </h3>

                    <div class="table-responsive">
                        <table class="table table-row-bordered align-middle">
                            <thead>
                                <tr class="fw-bold text-muted">
                                    <th width="50">{{ __('Active') }}</th>
                                    <th>{{ __('Variant') }}</th>
                                    <th>{{ __('SKU') }}</th>
                                    <th>{{ __('Price') }}</th>
                                    <th>{{ __('Stock total') }}</th>
                                    <th width="150" class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($productVariants as $variantIndex => $variant)
                                    <tr wire:key="variant-{{ $variant['variant_key'] }}">

                                        {{-- ACTIVE --}}
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input"
                                                   wire:model="productVariants.{{ $variantIndex }}.is_active">
                                        </td>

                                        {{-- NAME --}}
                                        <td>
                                            <strong class="text-primary">
                                                {{ implode(' / ', $variant['option_values']) }}
                                            </strong>
                                        </td>

                                        {{-- SKU --}}
                                        <td>
                                            <span class="badge badge-light-info">{{ $variant['sku'] ?? __('Not set') }}</span>
                                        </td>

                                        {{-- PRICE --}}
                                        <td>
                                            <span class="fw-bold">{{ number_format($variant['price'], 2) }}</span>
                                            @if($variant['price_promotion'])
                                                <span class="badge badge-light-warning ms-1">{{ __('Promo') }} {{ $variant['price_promotion'] }}</span>
                                            @endif
                                        </td>

                                        {{-- STOCK TOTAL --}}
                                        <td>
                                            <span class="fw-bold">{{ number_format(array_sum($variant['warehouses'])) }}</span>
                                            <span class="text-muted ms-1">{{ __('units') }}</span>
                                        </td>

                                        {{-- ACTIONS --}}
                                        <td class="text-end">
                                            <button type="button"
                                                    class="btn btn-sm btn-light-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editVariantModal{{ $variantIndex }}">
                                                <i class="fa-light fa-pen-to-square"></i>
                                                {{ __('Edit') }}
                                            </button>
                                            <button wire:click="deleteVariant({{ $variantIndex }})"
                                                    type="button"
                                                    class="btn btn-sm btn-light-danger">
                                                <i class="fa-light fa-trash"></i>
                                            </button>

                                            {{-- MODAL DE EDICIÓN UNIFICADO --}}
                                            <div wire:ignore.self
                                                 class="modal fade"
                                                 id="editVariantModal{{ $variantIndex }}"
                                                 tabindex="-1">
                                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-primary">
                                                            <h5 class="text-white mb-0">
                                                                <i class="fa-light fa-pen-to-square me-2"></i>
                                                                {{ __('Edit Variant') }}: <strong>{{ implode(' / ', $variant['option_values']) }}</strong>
                                                            </h5>
                                                            <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            {{-- TABS --}}
                                                            <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6" role="tablist">
                                                                <li class="nav-item" role="presentation">
                                                                    <a wire:key='prices_tab_{{ $variantIndex }}' class="nav-link active" data-bs-toggle="tab" href="#prices_tab_{{ $variantIndex }}" role="tab">
                                                                        <i class="fa-light fa-dollar-sign me-2"></i>{{ __('Prices & SKU') }}
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <a wire:key='stock_tab_{{ $variantIndex }}' class="nav-link" data-bs-toggle="tab" href="#stock_tab_{{ $variantIndex }}" role="tab">
                                                                        <i class="fa-light fa-boxes-stacked me-2"></i>{{ __('Stock') }}
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <a wire:key='dimensions_tab_{{ $variantIndex }}' class="nav-link" data-bs-toggle="tab" href="#dimensions_tab_{{ $variantIndex }}" role="tab">
                                                                        <i class="fa-light fa-ruler-combined me-2"></i>{{ __('Dimensions') }}
                                                                    </a>
                                                                </li>
                                                                <li class="nav-item" role="presentation">
                                                                    <a wire:key='images_tab_{{ $variantIndex }}' class="nav-link" data-bs-toggle="tab" href="#images_tab_{{ $variantIndex }}" role="tab">
                                                                        <i class="fa-light fa-images me-2"></i>{{ __('Images') }}
                                                                    </a>
                                                                </li>
                                                            </ul>

                                                            {{-- TAB CONTENT --}}
                                                            <div wire:ignore.self class="tab-content">
                                                                {{-- PRECIOS --}}
                                                                <div wire:ignore.self class="tab-pane fade show active" id="prices_tab_{{ $variantIndex }}" role="tabpanel">
                                                                    <div class="row g-4">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-bold">
                                                                                <i class="fa-light fa-barcode me-1"></i>{{ __('SKU') }}
                                                                            </label>
                                                                            <input type="text"
                                                                                   class="form-control"
                                                                                   wire:model="productVariants.{{ $variantIndex }}.sku"
                                                                                   placeholder="SKU-001">
                                                                            <small class="text-muted">{{ __('Stock Keeping Unit - Unique identifier') }}</small>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-bold required">
                                                                                <i class="fa-light fa-dollar-sign me-1"></i>{{ __('Price') }}
                                                                            </label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-text">$</span> 
                                                                                <input type="number"
                                                                                       step="0.01"
                                                                                       min="0"
                                                                                       class="form-control"
                                                                                       wire:model="productVariants.{{ $variantIndex }}.price"
                                                                                       placeholder="0.00">
                                                                            </div>
                                                                            <small class="text-muted">{{ __('Regular selling price') }}</small>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-bold">
                                                                                <i class="fa-light fa-tag me-1"></i>{{ __('Price promotion') }}
                                                                                <span class="badge badge-warning ms-1">{{ __('Optional') }}</span>
                                                                            </label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-text">$</span>
                                                                                <input type="number"
                                                                                       step="0.01"
                                                                                       min="0"
                                                                                       class="form-control"
                                                                                       wire:model="productVariants.{{ $variantIndex }}.price_promotion"
                                                                                       placeholder="0.00">
                                                                            </div>
                                                                            <small class="text-muted">{{ __('Discounted price (leave empty if no promotion)') }}</small>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-bold">
                                                                                <i class="fa-light fa-coins me-1"></i>{{ __('Cost') }}
                                                                            </label>
                                                                            <div class="input-group">
                                                                                <span class="input-group-text">$</span>
                                                                                <input type="number"
                                                                                       step="0.01"
                                                                                       min="0"
                                                                                       class="form-control"
                                                                                       wire:model="productVariants.{{ $variantIndex }}.cost"
                                                                                       placeholder="0.00">
                                                                            </div>
                                                                            <small class="text-muted">{{ __('Cost per unit (for margin calculations)') }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- STOCK --}}
                                                                <div wire:ignore.self class="tab-pane fade" id="stock_tab_{{ $variantIndex }}" role="tabpanel">
                                                                    <div class="alert alert-primary d-flex align-items-center mb-4">
                                                                        <i class="fa-light fa-info-circle fs-2x me-3"></i>
                                                                        <div>{{ __('Manage stock for this variant across all warehouses') }}</div>
                                                                    </div>
                                                                    <div class="row g-4">
                                                                        @foreach($warehouses as $warehouse)
                                                                            <div class="col-md-6">
                                                                                <label class="form-label fw-bold">
                                                                                    <i class="fa-light fa-warehouse me-2"></i>{{ $warehouse->name }}
                                                                                </label>
                                                                                <input type="number"
                                                                                       class="form-control"
                                                                                       wire:model="productVariants.{{ $variantIndex }}.warehouses.{{ $warehouse->id }}"
                                                                                       placeholder="0">
                                                                                <small class="text-muted">{{ __('Available units') }}</small>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>

                                                                {{-- DIMENSIONES --}}
                                                                <div wire:ignore.self class="tab-pane fade" id="dimensions_tab_{{ $variantIndex }}" role="tabpanel">
                                                                    <div class="alert alert-info d-flex align-items-center mb-4">
                                                                        <i class="fa-light fa-info-circle fs-2x me-3"></i>
                                                                        <div>{{ __('Physical dimensions used for shipping calculations') }}</div>
                                                                    </div>
                                                                    <div class="row g-4">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-bold">
                                                                                <i class="fa-light fa-weight-hanging me-2"></i>{{ __('Weight (kg)') }}
                                                                            </label>
                                                                            <input type="number"
                                                                                   step="0.001"
                                                                                   class="form-control"
                                                                                   wire:model="productVariants.{{ $variantIndex }}.weight_kl"
                                                                                   placeholder="0.000">
                                                                            <small class="text-muted">{{ __('Example: 1.500 kg') }}</small>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-bold">
                                                                                <i class="fa-light fa-arrows-up-down me-2"></i>{{ __('Height (cm)') }}
                                                                            </label>
                                                                            <input type="number"
                                                                                   step="0.01"
                                                                                   class="form-control"
                                                                                   wire:model="productVariants.{{ $variantIndex }}.height"
                                                                                   placeholder="0.00">
                                                                            <small class="text-muted">{{ __('Example: 15.50 cm') }}</small>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-bold">
                                                                                <i class="fa-light fa-arrows-left-right me-2"></i>{{ __('Width (cm)') }}
                                                                            </label>
                                                                            <input type="number"
                                                                                   step="0.01"
                                                                                   class="form-control"
                                                                                   wire:model="productVariants.{{ $variantIndex }}.width"
                                                                                   placeholder="0.00">
                                                                            <small class="text-muted">{{ __('Example: 10.00 cm') }}</small>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label fw-bold">
                                                                                <i class="fa-light fa-ruler-horizontal me-2"></i>{{ __('Length (cm)') }}
                                                                            </label>
                                                                            <input type="number"
                                                                                   step="0.01"
                                                                                   class="form-control"
                                                                                   wire:model="productVariants.{{ $variantIndex }}.length"
                                                                                   placeholder="0.00">
                                                                            <small class="text-muted">{{ __('Example: 20.00 cm') }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- IMÁGENES --}}
                                                                <div wire:ignore.self class="tab-pane fade" id="images_tab_{{ $variantIndex }}" role="tabpanel">
                                                                    {{-- Upload --}}
                                                                    <div class="card mb-4 shadow-sm">
                                                                        <div class="card-body">
                                                                            <label class="form-label fw-bold mb-3">
                                                                                <i class="fa-light fa-cloud-arrow-up me-2"></i>{{ __('Upload images') }}
                                                                            </label>
                                                                            <div x-data="{ isUploading: false, progress: 0 }"
                                                                                 x-on:livewire-upload-start="isUploading = true"
                                                                                 x-on:livewire-upload-finish="isUploading = false"
                                                                                 x-on:livewire-upload-error="isUploading = false"
                                                                                 x-on:livewire-upload-progress="progress = $event.detail.progress">
                                                                                <input type="file"
                                                                                       multiple
                                                                                       class="form-control"
                                                                                       wire:model="productVariants.{{ $variantIndex }}.gallery_images_tmp"
                                                                                       accept="image/*">
                                                                                <div x-show="isUploading" class="progress h-6px w-100 mt-3">
                                                                                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated"
                                                                                         role="progressbar"
                                                                                         :style="`width: ${progress}%;`">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <small class="text-muted d-block mt-2">
                                                                                <i class="fa-light fa-circle-info me-1"></i>
                                                                                {{ __('Accepts: JPG, PNG, GIF, WEBP. Max size: 2MB per file') }}
                                                                            </small>
                                                                        </div>
                                                                    </div>

                                                                    {{-- Galería existente --}}
                                                                    @if(!empty($variant['gallery_images']))
                                                                        <div class="mb-4">
                                                                            <h6 class="fw-bold mb-3">
                                                                                <i class="fa-light fa-images me-2"></i>{{ __('Current images') }}
                                                                            </h6>
                                                                            <div class="row g-3">
                                                                                @foreach($variant['gallery_images'] as $imgIndex => $img)
                                                                                    <div class="col-md-3 col-sm-4">
                                                                                        <div class="position-relative">
                                                                                            <img src="{{ $img['url'] }}" class="img-fluid rounded border shadow-sm" alt="Image" style="aspect-ratio: 1/1; object-fit: cover;">
                                                                                            <button wire:click="removeVariantGalleryImage({{ $variantIndex }}, {{ $imgIndex }})"
                                                                                                    type="button"
                                                                                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle"
                                                                                                    style="width: 30px; height: 30px; padding: 0;">
                                                                                                <i class="fa-light fa-trash"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    {{-- Imágenes temporales --}}
                                                                    @if(!empty($variant['gallery_images_tmp']))
                                                                        <div class="mb-4">
                                                                            <h6 class="fw-bold mb-3">
                                                                                <i class="fa-light fa-clock me-2"></i>{{ __('New images') }}
                                                                                <span class="badge badge-warning">{{ __('Pending save') }}</span>
                                                                            </h6>
                                                                            <div class="row g-3">
                                                                                @foreach($variant['gallery_images_tmp'] as $tmpIndex => $tmpImg)
                                                                                    @if(is_object($tmpImg))
                                                                                        <div class="col-md-3 col-sm-4">
                                                                                            <div class="position-relative">
                                                                                                <img src="{{ $tmpImg->temporaryUrl() }}" class="img-fluid rounded border border-warning border-2 shadow-sm" alt="Temp Image" style="aspect-ratio: 1/1; object-fit: cover;">
                                                                                                <span class="badge bg-warning position-absolute top-0 start-0 m-2">{{ __('New') }}</span>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endif
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    @endif

                                                                    @if(empty($variant['gallery_images']) && empty($variant['gallery_images_tmp']))
                                                                        <div class="alert alert-light d-flex align-items-center">
                                                                            <i class="fa-light fa-image fs-2x text-muted me-3"></i>
                                                                            <div class="text-muted">{{ __('No images uploaded yet. Upload images to create a gallery for this variant.') }}</div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer bg-light">
                                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                                <i class="fa-light fa-times me-2"></i>{{ __('Close') }}
                                                            </button>
                                                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                                                                <i class="fa-light fa-check me-2"></i>{{ __('Ready') }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

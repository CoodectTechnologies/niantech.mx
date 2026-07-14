<div>

    @include('admin.components.errors')
    <form class="form" wire:submit.prevent="{{ $method }}">
        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h3>{{ __('General data') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold form-label mb-2">
                                <span class="">{{ __('Status') }}</span>
                            </label>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input wire:model="category.status" class="form-check-input" type="checkbox"
                                    value="" id="categoryStatus" {{ $category->status ? 'checked' : '' }} />
                                <label class="form-check-label" for="categoryStatus">
                                    @if($category->status)
                                        {{ __('Enabled') }}
                                    @else
                                        {{ __('Disabled') }}
                                    @endif
                                </label>
                            </div>
                            @error('category.status')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold form-label mb-2">
                                <span class="">{{ __('Include in menu') }}</span>
                            </label>
                            <div class="form-check form-switch form-check-custom form-check-solid">
                                <input wire:model="category.include_in_menu" class="form-check-input" type="checkbox"
                                    value="" id="categoryIncludeInMenu"
                                    {{ $category->include_in_menu ? 'checked' : '' }} />
                                <label class="form-check-label" for="categoryIncludeInMenu">
                                    @if($category->include_in_menu)
                                        {{ __('Enabled') }}
                                    @else
                                        {{ __('Disabled') }}
                                    @endif
                                </label>
                            </div>
                            @error('category.status')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold form-label mb-2">
                                <span class="required">{{ __('Name') }}</span>
                            </label>
                            <input type="text" required wire:model="translations.name.{{ translatable() }}"
                                class="form-control form-control-solid @error('translations.name.{{ translatable() }}') invalid-feedback @enderror"
                                placeholder="Ejem: {{ __('Beauty') }}" name="" />
                            @error('translations.name.{{ translatable() }}')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold form-label mb-2">
                                <span class="">{{ __('Description') }}</span>
                            </label>
                            <textarea wire:model="translations.description.{{ translatable() }}"
                                class="form-control form-control-solid @error('translations.description.{{ translatable() }}') invalid-feedback @enderror"
                                placeholder="..."></textarea>
                            @error('translations.description.{{ translatable() }}')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="fv-row mb-7">
                            <label class="fs-6 fw-bold form-label mb-2">
                                <span class="">{{ __('Key product or service') }} SAT</span>
                            </label>
                            <input type="text" wire:model="category.key_product_or_service"
                                class="form-control form-control-solid @error('category.key_product_or_service') invalid-feedback @enderror"
                                placeholder="Ejem: 01010101" name="" />
                            @error('category.key_product_or_service')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mt-5">
                            <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                                x-on:livewire-upload-finish="isUploading = false"
                                x-on:livewire-upload-error="isUploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">
                                    <span class="">{{ __('Banner') }}</span>
                                    <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                        title="Tipo de archivo permitido: png, jpg, jpeg. gif, .webp"></i>
                                </label>
                                <!--end::Label-->
                                <!--begin::Image input wrapper-->
                                <div class="mt-1">
                                    <!--begin::Image input-->
                                    <div class="image-input image-input-outline">
                                        <!--begin::Preview existing avatar-->
                                        <div class="image-input-wrapper w-350px h-100px"
                                            @if($bannerTmp) style="background-image: url('{{ $bannerTmp->temporaryUrl() }}'); background-size: contain;"
                                            @else
                                                style="background-image: url('{{ $category->bannerPreview() }}'); background-size: contain;" @endif>
                                        </div>
                                        <!--end::Preview existing avatar-->
                                        <!--begin::Edit-->
                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow image-input"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            title="{{ __('Change image') }}">
                                            <i class="fa-utility fa-semibold fa-pen-to-square fs-7"></i>
                                            <!--begin::Inputs-->
                                            <input wire:model="bannerTmp" class="d-none" type="file" name=""
                                                accept=".png, .jpg, .jpeg, .gif, .webp" />
                                            <!--end::Inputs-->
                                        </label>
                                        <!--end::Edit-->
                                        @if($bannerTmp || $category->image)
                                            <!--begin::Remove-->
                                            <span wire:click.prevent="removeBanner()"
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                title="">
                                                <i class="fa-light fa-circle-trash fs-2"></i>
                                            </span>
                                            <!--end::Remove-->
                                        @endif
                                    </div>
                                    <!--end::Image input-->
                                </div>
                                @error('bannerTmp')
                                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                                @enderror
                                <!-- Progress Bar -->
                                <div x-show="isUploading" class="progress h-6px w-100">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        :style="`width: ${progress}%;`" :aria-valuenow="`${progress}`" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>
                            <!--end::Image input wrapper-->
                        </div>
                        <div class="mt-5">
                            <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                                x-on:livewire-upload-finish="isUploading = false"
                                x-on:livewire-upload-error="isUploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">
                                    <span class="">{{ __('Image') }}</span>
                                    <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                        title="Tipo de archivo permitido: png, jpg, jpeg. gif, .webp"></i>
                                </label>
                                <!--end::Label-->
                                <!--begin::Image input wrapper-->
                                <div class="mt-1">
                                    <!--begin::Image input-->
                                    <div class="image-input image-input-outline">
                                        <!--begin::Preview existing avatar-->
                                        <div class="image-input-wrapper w-350px h-200px"
                                            @if($imageTmp) style="background-image: url('{{ $imageTmp->temporaryUrl() }}'); background-size: contain;"
                                            @else
                                                style="background-image: url('{{ $category->imagePreview() }}'); background-size: contain;" @endif>
                                        </div>
                                        <!--end::Preview existing avatar-->
                                        <!--begin::Edit-->
                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow image-input"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            title="{{ __('Change image') }}">
                                            <i class="fa-utility fa-semibold fa-pen-to-square fs-7"></i>
                                            <!--begin::Inputs-->
                                            <input wire:model="imageTmp" class="d-none" type="file"
                                                name="" accept=".png, .jpg, .jpeg, .gif, .webp" />
                                            <!--end::Inputs-->
                                        </label>
                                        <!--end::Edit-->
                                        @if($imageTmp || $category->image)
                                            <!--begin::Remove-->
                                            <span wire:click.prevent="removeImage()"
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                title="">
                                                <i class="ki-outline ki-pencil fs-2"></i>
                                            </span>
                                            <!--end::Remove-->
                                        @endif
                                    </div>
                                    <!--end::Image input-->
                                </div>
                                @error('imageTmp')
                                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                                @enderror
                                <!-- Progress Bar -->
                                <div x-show="isUploading" class="progress h-6px w-100">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        :style="`width: ${progress}%;`" :aria-valuenow="`${progress}`"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <!--end::Image input wrapper-->
                        </div>
                        <div class="mt-5">
                            <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                                x-on:livewire-upload-finish="isUploading = false"
                                x-on:livewire-upload-error="isUploading = false"
                                x-on:livewire-upload-progress="progress = $event.detail.progress">
                                <!--begin::Label-->
                                <label class="fs-6 fw-bold mb-2">
                                    <span class="">{{ __('Icon') }}</span>
                                    <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                                        title="Tipo de archivo permitido: png, jpg, jpeg. gif, .webp"></i>
                                </label>
                                <!--end::Label-->
                                <!--begin::Image input wrapper-->
                                <div class="mt-1">
                                    <!--begin::Image input-->
                                    <div class="image-input image-input-outline">
                                        <!--begin::Preview existing avatar-->
                                        <div class="image-input-wrapper w-200px h-200px"
                                            @if($imageIconTmp) style="background-image: url('{{ $imageIconTmp->temporaryUrl() }}'); background-size: contain;"
                                        @else
                                            style="background-image: url('{{ $category->imageIconPreview() }}'); background-size: contain;" @endif>
                                        </div>
                                        <!--end::Preview existing avatar-->
                                        <!--begin::Edit-->
                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow image-input"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            title="{{ __('Change image') }}">
                                            <i class="fa-utility fa-semibold fa-pen-to-square fs-7"></i>
                                            <!--begin::Inputs-->
                                            <input wire:model="imageIconTmp" class="d-none" type="file"
                                                name="" accept=".png, .jpg, .jpeg, .gif, .webp" />
                                            <!--end::Inputs-->
                                        </label>
                                        <!--end::Edit-->
                                        @if($imageIconTmp || $category->imageIcon)
                                            <!--begin::Remove-->
                                            <span wire:click.prevent="removeImageIcon()"
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                title="">
                                                <i class="ki-outline ki-pencil fs-2"></i>
                                            </span>
                                            <!--end::Remove-->
                                        @endif
                                    </div>
                                    <!--end::Image input-->
                                </div>
                                @error('imageIconTmp')
                                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                                @enderror
                                <!-- Progress Bar -->
                                <div x-show="isUploading" class="progress h-6px w-100">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        :style="`width: ${progress}%;`" :aria-valuenow="`${progress}`"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <!--end::Image input wrapper-->
                        </div>
                    </div>
                </div>
                @if($category->exists)
                    <div class="card mt-5">
                        <div class="card-header">
                            <div class="card-title">
                                <h3>{{ __('URL') }}</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('ecommerce.product.index', ['category' => $category->slug]) }}"
                                target="_blank"
                                rel="noopener noreferrer">{{ route('ecommerce.product.index', ['category' => $category->slug]) }}</a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h3>{{ __('Parent category') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="fv-row">
                            <div>
                                <select wire:model="categoryParentArray" multiple="multiple" name="parentId"
                                    class="form-select mb-2 @error('categoryParentArray') invalid-feedback @enderror"
                                    style="height: 553px;">
                                    <option value="0">{{ __('No parent category') }}</option>
                                    @foreach(json_decode(json_encode($categories)) as $categoryFhater)
                                        <option {{ $categoryFhater->id == $category->id ? 'disabled' : '' }}
                                            value="{{ $categoryFhater->id }}" style="font-weight: bold;">
                                            {{ $categoryFhater->name }}</option>
                                        @include('admin.catalog.category.partials.form._category', [
                                            'categoryFhater' => $categoryFhater,
                                            'style' => 'padding-left: 15px;',
                                        ])
                                    @endforeach
                                </select>
                            </div>
                            @error('categoryParentArray')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                            <div class="text-muted fs-7 mb-7">
                                {{ __('You can select the parent category if required.') }}</div>
                        </div>
                    </div>
                </div>
                <div class="card mt-5">
                    <div class="card-header">
                        <div class="card-title">
                            <h3>{{ __('Products') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-xl-8 mt-xl-8">
                            <div class="border-0">
                                <h4 class="card-title fw-bolder text-dark">{{ __('Select products') }}</h4>
                            </div>
                        </div>
                        <div class="d-flex flex-column gap-5">
                            <div class="d-flex align-items-center position-relative mb-n7">
                                <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none">
                                        <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2"
                                            rx="1" transform="rotate(45 17.0365 15.1223)" fill="gray" />
                                        <path
                                            d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                            fill="gray" />
                                    </svg>
                                </span>
                                <input wire:model.live.debounce.500ms="search" type="search"
                                    class="form-control w-100 ps-14" placeholder="{{ __('Search') }}" />
                            </div>
                            <div class="overflow">
                                <table class="table align-middle table-row-dashed fs-6 gy-5">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                                            <th class="min-w-200px">{{ __('Product') }}</th>
                                            <th class="min-w-100px pe-5">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fw-bold text-gray-600">
                                        @foreach($products as $product)
                                            <div class="card card-flush">
                                                <tr class="">
                                                    <td class="pe-5">
                                                        <div class="d-flex align-items-center">
                                                            <a href="{{ route('admin.catalog.product.show', $product) }}"
                                                                class="symbol symbol-50px">
                                                                <span class="symbol-label"
                                                                    style="background-image:url({{ $product->imagePreview() }});"></span>
                                                            </a>
                                                            <div class="ms-5">
                                                                <a href="{{ route('admin.catalog.product.show', $product) }}"
                                                                    class="text-gray-800 text-hover-primary fs-5 fw-bolder">{{ $product->name }}</a>
                                                                <div class="text-muted fs-7">SKU:
                                                                    <span class="ms-5"> {{ $product->sku }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        @if(isset($productsInCategoryTmp[$product->id]))
                                                            <button class="btn btn-primary" type="button">
                                                                <span>{{ __('Added') }}</span>
                                                            </button>
                                                        @else
                                                            <button
                                                                wire:click.prevent="addProduct({{ $product->id }})"
                                                                class="btn btn-light-primary" type="button">
                                                                <span wire:loading.remove
                                                                    wire:target="addProduct({{ $product->id }})">{{ __('Add') }}</span>
                                                                <span wire:loading
                                                                    wire:target="addProduct({{ $product->id }})"
                                                                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                            </button>
                                                        @endif

                                                    </td>
                                                </tr>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $products->links() }}
                            </div>
                            <div class="separator"></div>
                        </div>
                        <div class="mb-xl-8 mt-xl-8">
                            <div class="border-0">
                                <h4 class="card-title fw-bolder text-dark">{{ __('Products selected') }}</h4>
                            </div>
                        </div>
                        <div
                            class="row row-cols-1 row-cols-xl-2 row-cols-md-2 border border-dashed rounded pt-3 pb-1 mb-5 mh-300px overflow-scroll">
                            @if(count($category->products))
                                @foreach($category->products as $productInCategory)
                                    <div class="col my-2">
                                        <div
                                            class="d-flex align-items-center border border-dashed rounded p-3 bg-white">
                                            <a href="{{ route('admin.catalog.product.show', $productInCategory) }}"
                                                class="symbol symbol-50px">
                                                <span class="symbol-label"
                                                    style="background-image:url({{ $productInCategory->imagePreview() }});"></span>
                                            </a>
                                            <div class="ms-5">
                                                <a href="{{ route('admin.catalog.product.show', $productInCategory) }}"
                                                    class="text-gray-800 text-hover-primary fs-5 fw-bolder">{{ $productInCategory->name }}</a>
                                                <div class="text-muted fs-7">SKU: {{ $productInCategory->sku }}</div>
                                                @if($productInCategory->type)
                                                    <div class="text-muted fs-7">{{ __('Type') }}:
                                                        {{ $productInCategory->type }}</div>
                                                @endif
                                            </div>
                                            <span
                                                wire:click.prevent="deleteInCategory('{{ $productInCategory->id }}')"
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                title="">
                                                <i wire:loading.remove class="ki-outline ki-pencil fs-2"></i>
                                                <span wire:loading
                                                    wire:target="deleteInCategory('{{ $productInCategory->id }}')"
                                                    class="spinner-border spinner-border-sm align-middle"></span>
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            @if(count($productsInCategoryTmp))
                                @foreach($productsInCategoryTmp as $productIdInCategory => $productInCategory)
                                    <div class="col my-2">
                                        <div
                                            class="d-flex align-items-center border border-dashed rounded p-3 bg-white">
                                            <a target="_blank"
                                                href="{{ route('admin.catalog.product.show', $productInCategory['slug']) }}"
                                                class="symbol symbol-50px">
                                                <span class="symbol-label"
                                                    style="background-image:url({{ $productInCategory['image'] }});"></span>
                                            </a>
                                            <div class="ms-5">
                                                <a target="_blank"
                                                    href="{{ route('admin.catalog.product.show', $productInCategory['slug']) }}"
                                                    class="text-gray-800 text-hover-primary fs-5 fw-bolder">{{ $productInCategory['name'] }}</a>
                                                <div class="text-muted fs-7">SKU: {{ $productInCategory['sku'] }}
                                                </div>
                                                @if($productInCategory['type'])
                                                    <div class="text-muted fs-7">{{ __('Type') }}:
                                                        {{ $productInCategory['type'] }}</div>
                                                @endif
                                            </div>
                                            <span
                                                wire:click.prevent="deleteInCategoryTmp('{{ $productIdInCategory }}')"
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                title="">
                                                <i wire:loading.remove class="ki-outline ki-pencil fs-2"></i>
                                                <span wire:loading
                                                    wire:target="deleteInCategoryTmp('{{ $productIdInCategory }}')"
                                                    class="spinner-border spinner-border-sm align-middle"></span>
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-end pt-15">
            <a href="{{ route('admin.catalog.category.index') }}" class="btn btn-light me-3"><i
                    class="fa fa-arrow-left"></i></a>
            <button wire:loading.attr="disabled" wire:target="{{ $method }}" type="submit"
                class="btn btn-primary">
                <span class="indicator-label">{{ __('Save changes') }}</span>
                <span wire:loading wire:target="{{ $method }}"
                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </button>
        </div>
    </form>
</div>

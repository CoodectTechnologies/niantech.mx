<div class="card mb-4">
    <div class="card-header d-flex align-items-center">
        <i class="fa fa-info-circle fa-2x me-2"></i>
        <span>{{ __('Detalles') }}</span>
    </div>
    <div class="card-body">
        <div class="mb-2">
            <label class="form-label">{{ __('Provider') }} {{ $product->provider }} {{ __('id') }}</label>
            <input wire:model="product.provider_id" class="form-control form-control-sm mb-2 @error('product.provider') invalid-feedback @enderror"/>
            @error('product.provider')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-2">
            <label class="form-label">{{ __('Featured') }}</label>
            <select wire:model="product.featured" class="form-select form-select-sm mb-2 @error('product.featured') invalid-feedback @enderror">
                <option value="0">No</option>
                <option value="1">{{ __('Yes') }}</option>
            </select>
            @error('product.featured')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-2">
            <label class="form-label">{{ __('Categories') }}</label>
            <select wire:model="catalogCategoryArray" multiple="multiple" class="form-select form-select-sm mb-2 @error('catalogCategoryArray') invalid-feedback @enderror" style="height: 200px;">
                <option value="">{{ __('Without categories') }}</option>
                @foreach(json_decode(json_encode($categories)) as $categoryFhater)
                    <option value="{{ $categoryFhater->id }}" style="font-weight: bold;">{{ $categoryFhater->name }}</option>
                    @include('admin.catalog.category.partials.form._category', [
                        'categoryFhater' => $categoryFhater,
                        'style' => 'padding-left: 15px;',
                    ])
                @endforeach
            </select>
            @error('catalogCategoryArray')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-2">
            <label class="form-label">{{ __('Gender') }}</label>
            <select wire:model="catalogGenderArray" multiple="multiple" class="form-select form-select-sm mb-2 @error('catalogGenderArray') invalid-feedback @enderror" style="height: 200px;">
                <option value="">{{ __('Without gender') }}</option>
                @foreach($genders as $gender)
                    <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                @endforeach
            </select>
            @error('catalogGenderArray')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_catalog_gender" class="btn btn-light-primary btn-sm mb-3">
                <span class="svg-icon svg-icon-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="gray" />
                        <rect x="6" y="11" width="12" height="2" rx="1" fill="gray" />
                    </svg>
                </span>
                {{ __('New gender') }}
            </a>
        </div>
        <div class="mb-2">
            <label class="form-label">{{ __('Brand') }}</label>
            <select wire:model="product.product_brand_id" class="form-control mb-2 @error('product.product_brand_id') invalid-feedback @enderror" >
                <option value="">{{ __('Select a option') }}</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
            @error('product.product_brand_id')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_add_catalog_brand" class="btn btn-light-primary btn-sm mb-3">
                <span class="svg-icon svg-icon-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="gray" />
                        <rect x="6" y="11" width="12" height="2" rx="1" fill="gray" />
                    </svg>
                </span>
                {{ __('New brand') }}
            </a>
        </div>
        <div class="mb-2">
            <label class="form-label">{{ __('Status') }}</label>
            <select required wire:model="product.status" class="form-select form-select-sm mb-2">
                <option value="">{{ __('Select a option') }}</option>
                <option value="Publicado">{{ __('Published') }}</option>
                <option value="Borrador">{{ __('Draft') }}</option>
            </select>
            @error('product.status')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-2">
            <label class="form-label">{{ __('Type') }}</label>
            <select required wire:model="product.type" class="form-select form-select-sm mb-2">
                <option value="">{{ __('Select a option') }}</option>
                <option value="Físico">{{ __('Physical') }}</option>
                <option value="Digital">{{ __('Digital') }}</option>
                <option value="Físico y Digital">{{ __('Physical and digital') }}</option>
            </select>
            @error('product.type')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <div class=" {{ $product->getIsDigital() ? 'd-block' : 'd-none' }}">
            <div class="mb-2">
                <label class="form-label required">{{ __('File digital') }}</label>
                <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                    x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-error="isUploading = false"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <div class="mt-1">
                        <div class="image-input image-input-outline">
                            @if($fileDigitalTmp)
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" width="100%" height="400px" allowfullscreen
                                        src="{{ $fileDigitalTmp->temporaryUrl() }}"></iframe>
                                </div>
                            @elseif ($product->file_digital && Storage::exists($product->file_digital))
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" width="100%" height="400px" allowfullscreen
                                        src="{{ Storage::url($product->file_digital) }}"></iframe>
                                </div>
                            @else
                                <div class="image-input-wrapper w-125px h-125px"
                                    style="background-image: url('{{ asset('assets/admin/media/icons/pdf.png') }}')">
                                </div>
                            @endif
                            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow image-input"
                                data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                title="{{ __('Change data sheet') }}">
                                <i class="fa-utility fa-semibold fa-pen-to-square fs-7"></i>
                                <input wire:model="fileDigitalTmp" class="d-none" type="file" name="" accept=".pdf" />
                            </label>
                            @if($fileDigitalTmp || $product->file_digital)
                                <span wire:click.prevent="removeFileDigital()" class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="">
                                    <i class="fa-light fa-circle-trash fs-2"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div x-show="isUploading" class="progress h-6px w-100">
                        <div class="progress-bar bg-primary" role="progressbar" :style="`width: ${progress}%;`"
                            :aria-valuenow="`${progress}`" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="text-muted fs-7">{{ __('Set the digital file. Only .pdf files are accepted') }}</div>
                @error('fileDigitalTmp')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-2">
                <label class="form-label">{{ __('Downloadable') }}?</label>
                <div class="form-check form-switch form-check-custom form-check-solid">
                    <input wire:model="product.downloadable" class="form-check-input" type="checkbox" value="" id="downloadable" />
                    <label class="form-check-label" for="downloadable">
                        {{ $product->downloadable ? __('Downloadable') : __('No download capability') }}
                    </label>
                </div>
                @error('product.downloadable')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
</div>

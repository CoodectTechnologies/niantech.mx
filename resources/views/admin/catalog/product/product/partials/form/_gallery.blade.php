<div class="card card-flush py-4">
    <!-- Header -->
    <div class="card-header">
        <div class="card-title">
            <h2 class="fw-bold">{{ __('Gallery') }}</h2>
        </div>
    </div>

    <!-- Body -->
    <div class="card-body pt-0">
        <!-- Area Dropzone / Upload -->
        <div class="mb-6">
            <div 
                x-data="{ isDragging: false }"
                class="dropzone border-2 border-dashed rounded-3 p-6 text-center transition-all cursor-pointer"
                :class="isDragging ? 'border-primary bg-light-primary shadow-sm' : 'border-gray-300 bg-light-soft'"
                @dragover.prevent="isDragging = true"
                @dragleave.prevent="isDragging = false"
                @drop.prevent="
                    isDragging = false;
                    const files = $event.dataTransfer.files;
                    if (!files.length) return;
                    const dataTransfer = new DataTransfer();
                    Array.from(files).forEach(file => dataTransfer.items.add(file));
                    $refs.imagesInput.files = dataTransfer.files;
                    $refs.imagesInput.dispatchEvent(new Event('change', { bubbles: true }));
                "
                @click="$refs.imagesInput.click()">

                <input x-ref="imagesInput" 
                       wire:model="imagesTmp" 
                       type="file" 
                       class="d-none"
                       accept=".png, .jpg, .jpeg, .gif, .webp" 
                       multiple
                       id="imagesTmp-{{ $imagesTmpInputId }}"
                       @click.stop>

                <div class="py-2">
                    <i class="fa-light fa-cloud-arrow-up fs-2hx text-primary mb-3"></i>
                    <div class="fw-bold text-gray-800 fs-6">{{ __('Drop images here or click to browse') }}</div>
                    <div class="text-muted fs-7 mt-1">{{ __('Supports PNG, JPG, GIF or WEBP') }}</div>
                </div>

                <div wire:loading wire:target="imagesTmp" class="mt-3">
                    <span class="spinner-border spinner-border-sm text-primary align-middle me-2"></span>
                    <span class="text-gray-600 fs-7 fw-semibold">{{ __('Uploading images...') }}</span>
                </div>
            </div>

            @if(config('services.vadeto_brands.status') && config('services.vadeto_brands.download_image_product'))
                <div class="mt-3 text-end">
                    <button type="button" 
                            wire:click="loadProductImagesBrands"
                            class="btn btn-sm btn-light-primary fw-bold"
                            wire:loading.attr="disabled"
                            wire:target="loadProductImagesBrands">
                        <i class="fa-light fa-download me-1"></i>
                        {{ __('Load images vadeto brands') }}
                        <span wire:loading 
                              wire:target="loadProductImagesBrands" 
                              class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </button>
                </div>
            @endif
        </div>

        <!-- Rejilla de Imágenes Unificada -->
        <div class="row g-4">
            
            {{-- 1. IMÁGENES TEMPORALES RECIÉN SUBIDAS --}}
            @foreach($imagesTmp as $key => $imageTmp)
                <div wire:key="images-tmp-{{ $key }}" class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="gallery-card group position-relative rounded-3 overflow-hidden border border-warning shadow-sm">
                        <!-- Badge Estado -->
                        <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-3 z-index-2 fw-bold">
                            <i class="fa-light fa-clock me-1 text-dark"></i>{{ __('New') }}
                        </span>

                        <!-- Contenedor Imagen Aspect 1:1 -->
                        <div class="ratio ratio-1x1 bg-light">
                            <img src="{{ $imageTmp->temporaryUrl() }}" 
                                 class="object-fit-contain p-2" 
                                 alt="Temp Preview">
                        </div>

                        <!-- Overlay On-Hover con Acciones -->
                        <div class="gallery-overlay position-absolute inset-0 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0 group-hover-opacity-100 transition-all z-index-3">
                            <button wire:click.prevent="removeImageTemp('{{ $key }}')"
                                    type="button"
                                    class="btn btn-icon btn-danger btn-sm rounded-circle shadow"
                                    title="{{ __('Remove') }}">
                                <i wire:loading.remove 
                                   wire:target="removeImageTemp('{{ $key }}')" 
                                   class="fa-light fa-trash fs-6"></i>
                                <span wire:loading 
                                      wire:target="removeImageTemp('{{ $key }}')" 
                                      class="spinner-border spinner-border-sm"></span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- 2. IMÁGENES TEMPORALES MARCAS (VADETO) --}}
            @foreach($imagesTmpBrands as $key => $imageTmp)
                <div wire:key="images-tmp-brands-{{ $key }}" class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="gallery-card group position-relative rounded-3 overflow-hidden border border-info shadow-sm">
                        <span class="badge bg-info text-white position-absolute top-0 start-0 m-3 z-index-2 fw-bold">
                            {{ __('Brand') }}
                        </span>

                        <div class="ratio ratio-1x1 bg-light">
                            <img src="{{ $imageTmp }}" class="object-fit-contain p-2" alt="Brand Image">
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- 3. IMÁGENES GUARDADAS EN BD --}}
            @foreach($productImages as $image)
                <div wire:key="product-image-{{ $image->id }}" class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="gallery-card group position-relative rounded-3 overflow-hidden border border-gray-200 shadow-sm">
                        <!-- Contenedor Imagen Aspect 1:1 -->
                        <div class="ratio ratio-1x1 bg-light">
                            <img src="{{ $image->imagePreview() }}" 
                                 class="object-fit-contain p-2" 
                                 alt="Product Image">
                        </div>

                        <!-- Overlay On-Hover -->
                        <div class="gallery-overlay position-absolute inset-0 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0 group-hover-opacity-100 transition-all z-index-3">
                            <button wire:click.prevent="removeImage('{{ $image->id }}')"
                                    type="button"
                                    class="btn btn-icon btn-danger btn-sm rounded-circle shadow"
                                    title="{{ __('Delete') }}">
                                <i wire:loading.remove 
                                   wire:target="removeImage('{{ $image->id }}')" 
                                   class="fa-light fa-trash fs-6"></i>
                                <span wire:loading 
                                      wire:target="removeImage('{{ $image->id }}')" 
                                      class="spinner-border spinner-border-sm"></span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</div>

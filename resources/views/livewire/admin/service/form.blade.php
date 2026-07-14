<div>
    <div x-data="form"> 
        @include('admin.components.errors')
        <form wire:submit.prevent="{{ $method }}" class="form d-flex flex-column flex-lg-row">
            <!--begin::Aside column-->
            <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
                <!--begin::Card -->
                <div class="card card-flush py-4">
                    <!--begin::Card body-->
                    <div class="card-body text-start pt-0">
                        <div x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true"
                            x-on:livewire-upload-finish="isUploading = false"
                            x-on:livewire-upload-error="isUploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                            <!--begin::Label-->
                            <label class="fs-6 fw-bold mb-2">
                                <span class="required">{{ __('Image') }}</span>
                            </label>
                            <!--end::Label-->
                            <!--begin::Image input wrapper-->
                            <div class="mt-1">
                                <!--begin::Image input-->
                                <div class="image-input image-input-outline">
                                    <!--begin::Preview existing avatar-->
                                    <div class="image-input-wrapper w-200px h-125px"
                                        @if($imageTmp) style="background-image: url('{{ $imageTmp->temporaryUrl() }}')"
                                        @else
                                            style="background-image: url('{{ $service->imagePreview() }}')" @endif>
                                    </div>
                                    <!--end::Preview existing avatar-->
                                    <!--begin::Edit-->
                                    <label
                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow image-input"
                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                        title="{{ __('Change image') }}">
                                        <i class="fa-utility fa-semibold fa-pen-to-square fs-7"></i>
                                        <!--begin::Inputs-->
                                        <input wire:model="imageTmp" class="d-none" type="file" name=""
                                            accept=".png, .jpg, .jpeg, .gif, .webp" />
                                        <!--end::Inputs-->
                                    </label>
                                    <!--end::Edit-->
                                    @if($imageTmp || $service->image)
                                        <!--begin::Remove-->
                                        <span wire:click.prevent="removeImageMain()"
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="">
                                            <i class="fa-light fa-circle-trash fs-2"></i>
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
                                <div class="progress-bar bg-primary" role="progressbar" :style="`width: ${progress}%;`"
                                    :aria-valuenow="`${progress}`" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <!--begin::Description-->
                        <div class="text-muted fs-7">
                            {{ __('Set the main image. Only *.png, *.jpg, *.jpeg, *gif image files are accepted') }}</div>
                        <!--end::Description-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card -->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title">
                            <h2>{{ __('Details') }}</h2>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">{{ __('Order') }}</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input wire:model="service.order" required type="number"
                                class="form-control mb-2 @error('service.order') invalid-feedback @enderror"
                                placeholder="Número de ordenamiento" />
                            <!--end::Input-->
                            @error('service.order')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
            <!--end::Aside column-->
            <!--begin::Main column-->
            <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                <!--begin::General options-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>{{ __('General') }}</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row">
                            <!--begin::Label-->
                            <label class="required form-label">{{ __('Name') }}</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input wire:model="translations.name.{{ translatable() }}" required type="text"
                                class="form-control mb-2 @error('translations.name.{{ translatable() }}') invalid-feedback @enderror"
                                placeholder="Titulo del servicio" />
                            <!--end::Input-->
                            @error('translations.name.{{ translatable() }}')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                            <!--begin::Description-->
                            <div class="text-muted fs-7">
                                {{ __('A service name is required and is recommended to be unique.') }}</div>
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10 fv-row">
                            <!--begin::Label-->
                            <label class="form-label required">{{ __('Fragment') }}</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <textarea wire:model="translations.fragment.{{ translatable() }}" required cols="10" rows="5"
                                class="form-control @error('translations.fragment.{{ translatable() }}') invalid-feedback @enderror">{{ $service->fragment }}</textarea>
                            <!--end::Editor-->
                            @error('translations.fragment.{{ translatable() }}')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                            <!--begin::Description-->
                            <div class="text-muted fs-7">
                                {{ __('Attractive little description of the service.Attractive little description of the service.') }}
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card header-->
                </div>
                <!--end::General options-->
                <!--begin::General options-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>{{ __('Content') }}</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div wire:ignore wire:key="translations.body.{{ translatable() }}">
                            <!--begin::Label-->
                            <label class="form-label required">{{ __('Content') }}</label> 
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <textarea wire:model="translations.body.{{ translatable() }}" required cols="10" rows="5"
                                class="form-control body @error('translations.body.{{ translatable() }}') invalid-feedback @enderror">{{ $service->body }}</textarea>
                            <!--end::Editor-->
                        </div>
                        <!--end::Input group-->
                        @error('translations.body.{{ translatable() }}')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <!--end::Card header-->
                </div>
                <!--end::General options-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>{{ __('Gallery') }}</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <div class="mb-7">
                            <!--begin::Input group-->
                            <div class="form-group row">
                                <!--begin::Label-->
                                <label class="col-lg-2 col-form-label text-lg-right">{{ __('Images') }}</label>
                                <!--end::Label-->
                                <!--begin::Col-->
                                <div class="col-lg-10">
                                    <!--begin::File-->
                                    <div class="dropzone dropzone-queue mb-2">
                                        <!--begin::Controls-->
                                        <div class="dropzone-panel mb-lg-0 mb-2">
                                            <label class="dropzone-select btn btn-sm btn-primary me-2"
                                                for="imagesTmp-{{ $imagesTmpInputId }}">
                                                {{ __('Choose images') }}
                                                <span wire:loading.attr="disabled"
                                                    wire:loading.class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </label>
                                            <input wire:model="imagesTmp" type="file" class="d-none" accept=".png, .jpg, .jpeg, .gif, .webp" multiple
                                                id="imagesTmp-{{ $imagesTmpInputId }}">
                                        </div>
                                        <!--end::Controls-->
                                    </div>
                                    <!--end::File-->
                                    <!--begin::Hint-->
                                    <span
                                        class="form-text text-muted">{{ __('Try to upload no more than 10 images.') }}</span>
                                    <!--end::Hint-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Input group-->
                        </div>
                        <!--begin::Input group-->
                        <div class="mb-7 row">
                            @foreach($imagesTmp as $key => $imageTmp)
                                <div class="col-lg-4 col-sm-4 col-12" wire:key="imageTmp-{{ $key }}">
                                    <!--begin::Image input wrapper-->
                                    <div class="mt-1">
                                        <!--begin::Image input-->
                                        <div class="image-input image-input-outline">
                                            <!--begin::Preview existing avatar-->
                                            <div class="image-input-wrapper w-200px h-125px"
                                                style="background-image: url('{{ $imageTmp->temporaryUrl() }}')"></div>
                                            <!--end::Preview existing avatar-->
                                            <!--begin::Remove-->
                                            <span wire:click.prevent="removeImageTemp('{{ $key }}')"
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                title="">
                                                <i wire:loading.remove
                                                    wire:target="removeImageTemp('{{ $key }}')"
                                                    class="fa-light fa-circle-trash fs-2"></i>
                                                <div wire:loading wire:target="removeImageTemp('{{ $key }}')"
                                                    class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </span>
                                            <!--end::Remove-->
                                        </div>
                                        <!--end::Image input-->
                                    </div>
                                </div>
                            @endforeach
                            @foreach($serviceImages as $image)
                                <div class="col-lg-4 col-sm-4 col-12" wire:key='service-image-{{ $image->id }}'>
                                    <!--begin::Image input wrapper-->
                                    <div class="mt-1">
                                        <!--begin::Image input-->
                                        <div class="image-input image-input-outline">
                                            <!--begin::Preview existing avatar-->
                                            <div class="image-input-wrapper w-200px h-125px"
                                                style="background-image: url('{{ $image->imagePreview() }}')"></div>
                                            <!--end::Preview existing avatar-->
                                            <!--begin::Remove-->
                                            <span wire:click.prevent="removeImage('{{ $image->id }}')"
                                                class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                                title="">
                                                <i wire:loading.remove wire:target="removeImage('{{ $image->id }}')"
                                                    class="fa-light fa-circle-trash fs-2"></i>
                                                <div wire:loading wire:target="removeImage('{{ $image->id }}')"
                                                    class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </span>
                                            <!--end::Remove-->
                                        </div>
                                        <!--end::Image input-->
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card header-->
                </div>
                <!--begin::Meta options-->
                <div class="card card-flush py-4">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <div class="card-title">
                            <h2>{{ __('Meta options') }}</h2>
                        </div>
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <!--begin::Label-->
                            <label class="form-label">{{ __('Meta tag title') }}</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input wire:model="translations.meta_title.{{ translatable() }}" type="text"
                                class="form-control mb-2 @error('translations.meta_title.{{ translatable() }}') invalid-feedback @enderror"
                                placeholder="" />
                            <!--end::Input-->
                            @error('translations.meta_title.{{ translatable() }}')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                            <!--begin::Description-->
                            <div class="text-muted fs-7">
                                {{ __('Set a meta tag title. It is recommended that they be simple and precise keywords.') }}
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <!--begin::Label-->
                            <label class="form-label">{{ __('Meta tag description') }}</label>
                            <!--end::Label-->
                            <!--begin::Editor-->
                            <textarea wire:model="translations.meta_description.{{ translatable() }}" cols="10" rows="5"
                                class="form-control @error('translations.meta_description.{{ translatable() }}') invalid-feedback @enderror">{{ $service->meta_description }}</textarea>
                            <!--end::Editor-->
                            @error('translations.meta_description.{{ translatable() }}')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-10">
                            <!--begin::Label-->
                            <label class="form-label">{{ __('Meta tag keywords') }}</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input wire:model="translations.meta_keywords.{{ translatable() }}" type="text"
                                class="form-control mb-2 @error('translations.meta_keywords.{{ translatable() }}') invalid-feedback @enderror" />
                            <!--end::Input-->
                            @error('translations.meta_keywords.{{ translatable() }}')
                                <small class="form-text text-danger" role="alert">{{ $message }}</small>
                            @enderror
                            <!--begin::Description-->
                            <div class="text-muted fs-7">
                                {{ __('These phrases will be used to find this service in the online store search engine. Separate key phrases by adding a comma') }}
                                <code>,</code>{{ __('between each key phrase.') }}
                            </div>
                            <!--end::Description-->
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Card header-->
                </div>
                <!--end::Meta options-->
                <div class="d-flex justify-content-end">
                    <!--begin::Button-->
                    <a href="{{ route('admin.service.index') }}" class="btn btn-light me-5">{{ __('Cancel') }}</a>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button wire:loading.attr="disabled" wire:target="{{ $method }}" type="submit"
                        class="btn btn-primary">
                        <span class="indicator-label">{{ __('Save changes') }}</span>
                        <span wire:loading wire:target="{{ $method }}"
                            class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </button>
                    <!--end::Button-->
                </div>
            </div>
            <!--end::Main column-->
        </form>
    </div>
</div>

@assets
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/custom/summernote/summernote-lite.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/plugins/custom/summernote/summernote-lite-custom.css') }}">
    <script defer src="{{ asset('assets/admin/plugins/custom/summernote/summernote-lite.js') }}"></script>
@endassets

@script
    <script>
        Alpine.data('form', () => ({
            init(){
                $('.body').summernote({
                    height: 400,
                    callbacks: {
                        onBlur: function(contents) {
                            let contentHTML = $('.body').summernote('code');
                            @this.set('translations.body.{{ translatable() }}', contentHTML);
                        }
                    }
                });
            }
        }));
    </script>
@endscript
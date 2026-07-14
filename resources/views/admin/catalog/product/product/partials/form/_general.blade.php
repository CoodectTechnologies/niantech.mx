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
        <!--end::Input group-->
        <div class="row">
            <!--begin::Input group-->
            <div class="mb-0 fv-row col-lg-12">
                <!--begin::Label-->
                <label class="required form-label">{{ __('Name') }}</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input wire:model="translations.name.{{ translatable() }}" required type="text"
                    class="form-control form-control-sm mb-2 @error('translations.name.'.translatable()) invalid-feedback @enderror"
                    placeholder="{{ __('Name of product') }}" />
                <!--end::Input-->
                @error('translations.name.'.translatable() )
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
                <!--begin::Description-->
                <div class="text-muted fs-7">{{ __('A product name is required and is recommended to be unique.') }}
                </div>
                <!--end::Description-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-0 fv-row col-lg-6">
                <label class="form-label">
                    {{ __('Name commercial') }}
                    <i class="fa fa-info-circle text-muted" data-bs-toggle="tooltip" title="Nombre comercial alternativo para búsquedas o marketing"></i>
                </label>
                <div class="position-relative">
                    <input wire:model="translations.name_commercial.{{ translatable() }}" type="text"
                        class="form-control form-control-sm mb-2 @error('translations.name_commercial.'.translatable()) is-invalid @enderror"
                        placeholder="{{ __('Name commercial') }}" />
                    @error('translations.name_commercial.'.translatable())
                        <span class="position-absolute end-0 top-0 mt-2 me-3 text-danger"><i class="fa fa-exclamation-circle"></i></span>
                    @enderror
                </div>
                @error('translations.name_commercial.'.translatable())
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="mb-0 fv-row col-lg-6">
                <label class="form-label">
                    {{ __('SKU') }}
                    <i class="fa fa-info-circle text-muted" data-bs-toggle="tooltip" title="Código único de inventario"></i>
                </label>
                <div class="position-relative">
                    <input wire:model="product.sku" type="text"
                        class="form-control form-control-sm mb-2 @error('product.sku') is-invalid @enderror" placeholder="SKU" />
                    @error('product.sku')
                        <span class="position-absolute end-0 top-0 mt-2 me-3 text-danger"><i class="fa fa-exclamation-circle"></i></span>
                    @enderror
                </div>
                @error('product.sku')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
            </div>
            <!--end::Input group-->
        </div>
        <!--begin::Input group-->
        <!--begin::Input group-->
        <div class="mb-0 fv-row col-lg-12">
            <!--begin::Label-->
            <label class="form-label">Iframe YouTube</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input wire:model="product.iframe_url" type="text"
                class="form-control form-control-sm mb-2 @error('product.iframe_url') invalid-feedback @enderror"
                placeholder="iframe_url" />
            <!--end::Input-->
            @error('product.iframe_url')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <!--end::Input group-->
        <div class="mb-0 fv-row col-lg-12">
            <!--begin::Label-->
            <label class="form-label">{{ __('Little description') }}</label>
            <!--end::Label-->
            <!--begin::Editor-->
            <textarea wire:model="translations.detail.{{ translatable() }}" cols="10" rows="5"
                class="form-control form-control-sm @error('translations.detail.'.translatable()) invalid-feedback @enderror">{{ $product->detail }}</textarea>
            <!--end::Editor-->
            @error('translations.detail.{{ translatable() }}')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
        </div>
        <!--end::Input group-->
        <div class="mb-0 fv-row col-lg-12">
            <!--begin::Label-->
            <label class="form-label">{{ __('Advanced search phrases') }}</label>
            <!--end::Label-->
            <!--begin::Editor-->
            <textarea wire:model="translations.search_advanced.{{ translatable() }}" cols="10" rows="5"
                class="form-control form-control-sm @error('translations.search_advanced.'.translatable()) invalid-feedback @enderror">{{ $product->search_advanced }}</textarea>
            <!--end::Editor-->
            @error('translations.search_advanced.{{ translatable() }}')
                <small class="form-text text-danger" role="alert">{{ $message }}</small>
            @enderror
            <!--begin::Description-->
            <div class="text-muted fs-7">
                {{ __('These phrases will be used to find this product in the online store search engine. Separate key phrases by adding a comma') }}
                <code>,</code>{{ __('between each key phrase.') }}
            </div>
            <!--end::Description-->
        </div>
        <!--end::Input group-->
    </div>
    <!--end::Card header-->
</div>

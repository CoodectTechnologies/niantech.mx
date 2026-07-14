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
        <div wire:ignore>
            <!--begin::Editor-->
            <textarea wire:model="translations.description.{{ translatable() }}" cols="10" rows="5"
                class="form-control form-control-sm description bg-light border border-primary rounded shadow-sm @error('translations.description.{{ translatable() }}') is-invalid @enderror"
                maxlength="1000"
                placeholder="{{ __('Describe your product in detail...') }}">{!! $product->description !!}</textarea>
            <div class="d-flex justify-content-between align-items-center mt-1">
                <small class="text-muted">{{ __('Recommended: 200-600 characters') }}</small>
            </div>
            <!--end::Editor-->
        </div>
        <!--end::Input group-->
        @error('translations.description.{{ translatable() }}')
            <small class="form-text text-danger" role="alert"><i class="fa fa-exclamation-circle"></i> {{ $message }}</small>
        @enderror
    </div>
    <!--end::Card header-->
</div>

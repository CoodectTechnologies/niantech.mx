<div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
    @push('head')
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/custom/summernote/summernote-lite.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/admin/plugins/custom/summernote/summernote-bs5.min.css') }}">
    @endpush

    @include('admin.components.errors')
    <!--begin::Form-->
    <form class="form" wire:submit.prevent="{{ $method }}">
        <div class="card card-flush my-4">
            <!--begin::Card header-->
            <div class="card-header">
                <div class="card-title">
                    <h2>{{ __('General') }}</h2>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
                <div class="me-n7 pe-7">
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-bolder form-label mb-2">
                            <span class="required">{{ __('Name') }}</span>
                        </label>
                        <input wire:model="translations.name.{{ translatable() }}"
                            class="form-control form-control-solid @error('translations.name.{{ translatable() }}') invalid-feedback @enderror"
                            placeholder="Ejem: Politica de privacidad" />
                        @error('translations.name.{{ translatable() }}')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-bolder form-label mb-2">
                            <span class="required">{{ __('Order') }}</span>
                        </label>
                        <input wire:model="privacyNotice.order" type="number"
                            class="form-control form-control-solid @error('privacyNotice.order') invalid-feedback @enderror"
                            placeholder="Ejem: 1 (Primer lugar en visualización)" />
                        @error('privacyNotice.order')
                            <small class="form-text text-danger" role="alert">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-flush my-4">
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
                    <textarea wire:model="translations.content.{{ translatable() }}" cols="10" rows="5"
                        class="form-control summernote @error('translations.content.{{ translatable() }}') invalid-feedback @enderror">{{ $privacyNotice->content }}</textarea>
                    <!--end::Editor-->
                </div>
                <!--end::Input group-->
                @error('translations.content.{{ translatable() }}')
                    <small class="form-text text-danger" role="alert">{{ $message }}</small>
                @enderror
            </div>
            <!--end::Card header-->
        </div>

        <!--begin::Actions-->
        <div class="text-center pt-15">
            <a href="{{ route('admin.setting.privacy-notice.index') }}" class="btn btn-light me-3"><i
                    class="fa fa-arrow-left"></i></a>
            <button wire:loading.attr="disabled" wire:target="{{ $method }}" type="submit"
                class="btn btn-primary">
                <span class="indicator-label">{{ __('Save changes') }}</span>
                <span wire:loading wire:target="{{ $method }}"
                    class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </button>
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->
    @push('footer')
        <script src="{{ asset('assets/admin/plugins/custom/summernote/summernote-lite.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('.summernote').summernote({
                    height: 400,
                    callbacks: {
                        onBlur: function(contents) {
                            let contentHTML = $('.summernote').summernote('code');
                            @this.set('translations.content.{{ translatable() }}', contentHTML);
                        }
                    }
                });
            });
        </script>
    @endpush
</div>

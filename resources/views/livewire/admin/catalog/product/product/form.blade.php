<div>    
    <div x-data="form">
        @include('admin.components.errors')
        <form class="form" wire:submit.prevent="{{ $method }}">
            <div class="d-flex flex-column flex-lg-row pt-5">
                <!--begin::Aside column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
                    <!--begin::General-->
                    @include('admin.catalog.product.product.partials.form._general')
                    <!--end::General-->
                    <!--begin::Price-->
                    @include('admin.catalog.product.product.partials.form._price')
                    <!--end::Price-->
                    <!--begin::Gallery-->
                    @include('admin.catalog.product.product.partials.form._gallery')
                    <!--end::Gallery-->
                    <!--begin::Variants-->
                    @include('admin.catalog.product.product.partials.form._variants')
                    <!--end::Variants-->
                    <!--begin::Dimension-->
                    @include('admin.catalog.product.product.partials.form._warehouse')
                    <!--end::Dimension-->
                    <!--begin::Description-->
                    @include('admin.catalog.product.product.partials.form._description')
                    <!--end::Description-->
                    <!--begin::Shipping class-->
                    @include('admin.catalog.product.product.partials.form._shipping-class')
                    <!--end::Shipping class-->
                    <!--begin::Dimension-->
                    @include('admin.catalog.product.product.partials.form._dimension')
                    <!--end::Dimension-->
                    <!--begin::Meta options-->
                    @include('admin.catalog.product.product.partials.form._meta-tag')
                    <!--end::Meta options-->
                </div>
                <!--end::Aside column-->
                <!--begin::Main column-->
                <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 ms-lg-10">
                    <!--begin::Image-->
                    @include('admin.catalog.product.product.partials.form._image')
                    <!--end::Image-->
                    <!--begin::Technical datasheet-->
                    @include('admin.catalog.product.product.partials.form._technical-datasheet')
                    <!--end::Technical datasheet-->
                    <!--begin::Marketplaces-->
                    @include('admin.catalog.product.product.partials.form._marketplace')
                    <!--end::Marketplaces-->
                    <!--begin:: Details-->
                    @include('admin.catalog.product.product.partials.form._detail')
                    <!--end:: Details-->
                </div>
                <!--end::Main column-->
            </div>
            <!--end:: Save changes-->
            <div class="d-block">
                <div class="d-flex justify-content-start py-5">
                    <!--begin::Button-->
                    <a href="{{ route('admin.catalog.product.index') }}" class="btn btn-light me-5">{{ __('Cancel') }}</a>
                    <!--end::Button-->
                    <!--begin::Button-->
                    <button type="submit" wire:loading.attr="disabled" wire:target="{{ $method }}" class="btn btn-primary">
                        <span class="indicator-label">{{ __('Save changes') }}</span>
                        <span wire:loading wire:target="{{ $method }}" class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </button>
                    <!--end::Button-->
                </div>
            </div>
            <!--end:: Save changes-->
        </form>
        @include('admin.catalog.product.product.partials.form._modal')
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
        init() {
            // Cleanup nativo para Summernote
            const $summernote = $('.description').summernote({
                height: 400,
                callbacks: {
                    onBlur: () => {
                        let contentHTML = $('.description').summernote('code');
                        $wire.translations.description.{{ translatable() }} = contentHTML;
                    }
                }
            });
            $wire.$on('render', () => {
                $('.modal').modal('hide');
            });
        },

        // Debounce nativo usando Alpine
        generateVariantsDebounced: Alpine.debounce(function () {
            $wire.generateVariants(); 
        }, 300),

        toogleHasVariants() {
            if (!$wire.hasVariants) {
                $wire.productOptions = [];
                $wire.generateVariants(); // 1 sola petición
            } else if ($wire.productOptions.length === 0) {
                this.addOption();
            }
        },

        addOption() {
            // Reasignar el arreglo completo fuerza a Alpine y Livewire a detectar la mutación
            $wire.productOptions = [
                ...$wire.productOptions,
                { name: '', type: 'button', values: [{ value: '', metadata: null }] }
            ];
        },

        removeOption(optionIndex) {
            let options = [...$wire.productOptions];
            options.splice(optionIndex, 1);
            $wire.productOptions = options;

            this.generateVariantsDebounced();
        },

        addValue(optionIndex) {
            let options = [...$wire.productOptions];
            const defaultMeta = options[optionIndex].type === 'color' ? '#000000' : null;
            
            options[optionIndex].values.push({ value: '', metadata: defaultMeta });
            $wire.productOptions = options;
        },

        updateValue(optionIndex, valueIndex, newValue) {
            $wire.productOptions[optionIndex].values[valueIndex].value = newValue;
            
            if (valueIndex === ($wire.productOptions[optionIndex].values.length - 1) && newValue.trim()) {
                this.addValue(optionIndex);
            }
        },

        removeValue(optionIndex, valueIndex) {
            let options = [...$wire.productOptions];
            options[optionIndex].values.splice(valueIndex, 1);
            $wire.productOptions = options;

            this.generateVariantsDebounced();
        },

        uploadSwatchImage(event, optionIndex, valueIndex, uploadScope) {
            const file = event.target.files[0];
            if (!file) return;

            // Muestra de previsualización local instantánea
            $wire.productOptions[optionIndex].values[valueIndex].metadata_image_preview = URL.createObjectURL(file);

            uploadScope.isUploading = true;
            uploadScope.progress = 0;

            // $wire.upload gestiona la transferencia temporal de forma aislada
            $wire.upload(`productOptions.${optionIndex}.values.${valueIndex}.metadata_image`, file,
                () => {
                    uploadScope.isUploading = false;
                    uploadScope.progress = 100;
                },
                () => {
                    uploadScope.isUploading = false;
                },
                (progressEvent) => {
                    uploadScope.progress = progressEvent.detail.progress;
                }
            );
        },

        focusNextValue(optionIndex, valueIndex, event) {
                const currentValue = $wire.productOptions[optionIndex].values[valueIndex].value;  
                if (!currentValue.trim()) return;
                const nextValueIndex = valueIndex + 1;
                if (nextValueIndex >= $wire.productOptions[optionIndex].values.length) {
                    this.addValue(optionIndex);
                }
                this.$nextTick(() => {
                    const currentInput = event.target;
                    const allInputs = Array.from(currentInput.closest('.mb-2').querySelectorAll('input[type="text"]'));
                    const currentInputIndex = allInputs.indexOf(currentInput);
                    const nextInput = allInputs[currentInputIndex + 1];   
                    if (nextInput) {
                        nextInput.focus();
                    }
                });
            },
    }));
</script>
@endscript
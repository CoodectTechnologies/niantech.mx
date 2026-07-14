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
            productOptions: $wire.entangle('productOptions'),
            hasVariants: $wire.entangle('hasVariants'),
            generateVariantsDebounced: null,

            init(){
                $('.description').summernote({
                    height: 400,
                    callbacks: {
                        onBlur: function(contents) {
                            let contentHTML = $('.description').summernote('code');
                            @this.set('translations.description.{{ translatable() }}', contentHTML);
                        }
                    }
                });
                this.generateVariantsDebounced = this.debounce(() => {
                    $wire.generateVariants();
                }, 800);
                Livewire.on('render', function() {
                    $('.modal').modal('hide');
                });
            },
            toogleHasVariants(){
                this.hasVariants = !this.hasVariants;
                if(!this.hasVariants){
                    this.productOptions = [];
                    $wire.generateVariants();
                }else{
                    if(this.productOptions.length === 0){
                        this.addOption();
                    }
                }
                console.log('toogleHasVariants()');
            },
            addOption(){
                this.productOptions.push({ name: '', values: [] });
                console.log('addOption()');
            },
            removeOption(optionIndex){
                this.productOptions.splice(optionIndex, 1);
                this.$nextTick(() => {
                    $wire.generateVariants();
                });
                console.log('removeOption()');
            },
            addValue(optionIndex){
                this.productOptions[optionIndex].values.push('');
                console.log('addValue()');
            },
            updateValue(optionIndex, valueIndex, newValue){
                this.productOptions[optionIndex].values[valueIndex] = newValue;
                // Si es el último campo y no está vacío, agregar uno nuevo
                if(valueIndex === (this.productOptions[optionIndex].values.length - 1) && newValue.trim()){
                    this.addValue(optionIndex);
                }
                console.log('updateValue()');
            },
            removeValue(optionIndex, valueIndex){
                this.productOptions[optionIndex].values.splice(valueIndex, 1);
                this.$nextTick(() => {
                    $wire.generateVariants();
                });
                console.log('removeValue()');
            },
            focusNextValue(optionIndex, valueIndex, event){
                const currentValue = this.productOptions[optionIndex].values[valueIndex];  
                if(!currentValue.trim()) return;
                const nextValueIndex = valueIndex + 1;
                if(nextValueIndex >= this.productOptions[optionIndex].values.length){
                    this.addValue(optionIndex);
                }
                this.$nextTick(() => {
                    const currentInput = event.target;
                    const allInputs = Array.from(currentInput.closest('.mb-2').querySelectorAll('input[type=\"text\"]'));
                    const currentInputIndex = allInputs.indexOf(currentInput);
                    const nextInput = allInputs[currentInputIndex + 1];   
                    if(nextInput){
                        nextInput.focus();
                    }
                });
                console.log('focusNextValue()');
            },
            debounce(func, wait){
                let timeout;
                console.log('debounce()');
                return function executedFunction(...args){
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
        }));
    </script>
@endscript
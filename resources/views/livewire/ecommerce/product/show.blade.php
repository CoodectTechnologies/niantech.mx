<div>
    <div x-data="app" class="mb-10 pb-1">
        <!-- Start of Page Content -->
        <div class="page-content">
            <div class="container">
                @include('ecommerce.components.alert')
                {{-- <div class="row gutter-lg pb-5"> --}}
                <div class="main-content">
                    <div class="product-single row">
                        <div class="col-md-7 mb-6 tab-content">
                            <div wire:loading.class='overlay' wire:loading.target='selectVariant'>
                                @include('ecommerce.product.partials.show._gallery')
                            </div>
                        </div>
                        <div class="col-md-5 mb-4 mb-md-6 tab-content">
                            @include('ecommerce.product.partials.show._product')
                        </div>
                    </div>
                    <div class="tab tab-nav-boxed tab-nav-underline product-tabs">
                        <ul class="nav nav-tabs" role="tablist">
                            @include('ecommerce.product.partials.show._menu')
                        </ul>
                        <div wire:ignore.self class="tab-content">
                            
                            <div wire:ignore.self class="tab-pane active" id="product-tab-description">
                                @include('ecommerce.product.partials.show._description')
                            </div>
                            <div wire:ignore.self class="tab-pane" id="product-tab-characteristic">
                                @include('ecommerce.product.partials.show._characteristics')
                            </div>
                            <div wire:ignore.self class="tab-pane" id="product-tab-video">
                                {!! $product->iframe_url !!}
                            </div>
                            <div wire:ignore.self class="tab-pane" id="product-tab-resources">
                                @include('ecommerce.product.partials.show._resource')
                            </div>
                            <div wire:ignore.self class="tab-pane" id="product-tab-reviews">
                                @livewire('ecommerce.comment.form', ['model' => $product])
                                @livewire('ecommerce.comment.index', ['model' => $product])
                            </div>
                        </div>
                    </div>
                    <div wire:ignore>
                        @include('ecommerce.product.partials.show._similars')
                    </div>
                </div>
                {{-- @include('ecommerce.product.partials.show._sidebar') --}}
                {{-- </div> --}}
            </div>
        </div>

        <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="pswp__bg"></div>
            <div class="pswp__scroll-wrap">
                <div class="pswp__container">
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                    <div class="pswp__item"></div>
                </div>
                <div class="pswp__ui pswp__ui--hidden">
                    <div class="pswp__top-bar">
                        <div class="pswp__counter"></div>
                        <button class="pswp__button pswp__button--close" aria-label="Close (Esc)"></button>
                        <button class="pswp__button pswp__button--zoom" aria-label="Zoom in/out"></button>
                        <div class="pswp__preloader">
                            <div class="loading-spin"></div>
                        </div>
                    </div>
                    <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                        <div class="pswp__share-tooltip"></div>
                    </div>
                    <button class="pswp__button--arrow--left" aria-label="Previous (arrow left)"></button>
                    <button class="pswp__button--arrow--right" aria-label="Next (arrow right)"></button>
                    <div class="pswp__caption">
                        <div class="pswp__caption__center"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        Alpine.data('app', () => ({
            type: $wire.entangle('type'),
            quantitySelected: $wire.entangle('quantitySelected'),
            quantityTotal: $wire.entangle('quantityTotal'),
            variants: $wire.entangle('variants'),
            sku: $wire.entangle('sku'),
            selectedVariant: null,
            selectedOptions: {},
            allOptions: @this.allOptions,

            init(){
                this.loadGallery();
                this.buttonSaveCartDisabled();
                // Escuchar evento para recargar galería
                window.addEventListener('galleryUpdated', () => {
                    this.reloadGallery();
                });
            },
            clearAllOptions(){
                this.selectedOptions = {};
                this.selectedVariant = null;
                $wire.selectVariant();
            },
            selectOption(optionId, valueId, valueName) {
                // Si ya está seleccionado el mismo valor, deseleccionar
                if(this.selectedOptions[optionId] === valueId){
                    delete this.selectedOptions[optionId];
                }else{
                    this.selectedOptions[optionId] = valueId;
                }
                // Determinar la variante
                this.selectVariant();
            },
            selectVariant() {
                const selectedValues = Object.values(this.selectedOptions);
                const totalOptionsCount = Object.keys(this.allOptions).length;
                
                // Verificar si ya seleccionaron todas las opciones necesarias
                const allOptionsSelected = Object.keys(this.selectedOptions).length === totalOptionsCount;
                
                // Buscar variante que coincida con todas las opciones seleccionadas
                const matchedVariant = this.variants.find(variant => {
                    // Verificar que tenga la misma cantidad de opciones
                    if(variant.option_values.length !== selectedValues.length){
                        return false;
                    }
                    // Verificar que todas las opciones seleccionadas estén en la variante
                    return selectedValues.every(valueId => variant.option_values.includes(valueId));
                });
                
                if(matchedVariant){
                    this.selectedVariant = matchedVariant;
                    this.quantityTotal = this.selectedVariant.quantity_total;
                    this.sku = this.selectedVariant.sku;
                    $wire.selectVariant(matchedVariant.id);
                }else{
                    if(this.selectedVariant){
                        $wire.selectVariant();
                    }
                    if(allOptionsSelected){
                        this.selectedVariant = null;
                        this.quantityTotal = 0;
                    }
                }
            },
            addQuantity(){
                if(this.quantitySelected < this.quantityTotal){
                    this.quantitySelected++;
                }
            },
            decrementQuantity(){
                if(this.quantitySelected > 1){
                    this.quantitySelected--;
                }
            },
            isInStock(){
                return this.quantityTotal > 0;
            },
            loadGallery(){
                this.$nextTick(() => {
                    Coodect.productSingle('.product-single');
                    Coodect.initProductSinglePage();
                });
            },
            reloadGallery(){
                this.$nextTick(() => {
                    Coodect.reloadCarouselProductSingle();
                });
            },
            buttonSaveCartDisabled(){
                // Si hay variantes pero no se ha seleccionado ninguna, deshabilitar
                if(this.variants.length > 0 && !this.selectedVariant){
                    return true;
                }
                // Si no hay stock, deshabilitar
                if(!this.isInStock()){
                    return true;
                }
                return false;
            }
        }));
    </script>
@endscript

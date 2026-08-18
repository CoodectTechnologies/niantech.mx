<?php

namespace App\Livewire\Admin\Catalog\Product\Product;

use App\Models\Currency;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductGender;
use App\Models\ProductWarehouse;
use App\Models\ShippingClass;
use App\Models\UnitType;
use App\Services\Product\ProductService;
use App\Services\Product\ProductVariantService;
use App\Traits\LivewireTranslatable;
use Exception;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use LivewireTranslatable;
    use WithFileUploads;

    // Model
    public $product;

    // Tools
    public $method;

    // Data Selects/Lists
    public $shippingClasses = [];
    public $categories = [];
    public $currencies = [];
    public $warehouses = [];
    public $unitTypes = [];
    public $genders = [];
    public $brands = [];

    // Data array
    public $catalogProductWarehousesArray = [];
    public $catalogCategoryArray = [];
    public $catalogGenderArray = [];
    public $providersErpCode = [];
    public $productImages = [];

    // Files
    public $imageTmp;
    public $imagesTmp = [];
    public $imagesTmpBrands = [];
    public $imagesTmpInputId;
    public $technicalDatasheetTmp;
    public $fileDigitalTmp;

    // Variantes
    public $hasVariants = false;
    public $productOptions = [];
    public $productVariants = [];

    public function rules() {
        return [
            'product.product_brand_id' => 'nullable',
            'product.shipping_class_id' => 'nullable',
            'product.unit_type_id' => 'nullable',
            'product.currency_id' => 'required',
            'translations.name.'.translatable() => 'required',
            'translations.name_commercial.'.translatable() => 'nullable',
            'product.price' => 'required',
            'product.price_promotion' => 'nullable',
            'product.cost' => 'nullable',
            'translations.detail.'.translatable() => 'nullable',
            'translations.description.'.translatable() => 'nullable',
            'translations.search_advanced.'.translatable() => 'nullable',
            'product.sku' => 'nullable|unique:products,sku,'.$this->product->id,
            'product.provider_id' => 'nullable',
            'product.featured' => 'nullable',
            'product.status' => 'required',
            'product.iframe_url' => 'nullable',
            'product.type' => 'required',
            'product.downloadable' => 'nullable',
            'product.link_amazon' => 'nullable',
            'product.link_mercadolibre' => 'nullable',
            'product.weight_kl' => 'nullable',
            'product.height' => 'nullable',
            'product.width' => 'nullable',
            'product.length' => 'nullable',
            'translations.meta_title.'.translatable() => 'nullable',
            'translations.meta_description.'.translatable() => 'nullable',
            'translations.meta_keywords.'.translatable() => 'nullable',
            'technicalDatasheetTmp' => 'nullable',
            'fileDigitalTmp' => ($this->product->getIsDigital() && ! $this->product->file_digital) ? 'required' : 'nullable',
        ];
    }

    public function mount(Product $product, $method, ProductVariantService $variantService) {
        $this->product = $product;
        $this->method = $method;

        $this->product->load([
            'productCategories',
            'productGenders',
            'productWarehouses',
            'productVariants.productOptionValues.productOption',
        ]);

        $this->loadRandomImagesTmpInputId();
        $this->loadCatalogData();
        
        // Cargar variantes usando el servicio
        $variantData = $variantService->getOptionsForForm($this->product);
        $this->hasVariants = $variantData['hasVariants'];
        $this->productOptions = $variantData['productOptions'];

        $this->generateVariants($variantService);
        $this->loadTranslations($this->product);
    }

    public function render() {
        return view('livewire.admin.catalog.product.product.form');
    }

    public function store(ProductService $productService) {
        $this->validate();
        $this->saveProduct($productService);

        Session::flash('alert', __('Registration successfully added'));
        Session::flash('alert-type', 'success');
        Redirect::route('admin.catalog.product.show', $this->product);
    }

    public function update(ProductService $productService) {
        $this->validate();
        $this->saveProduct($productService);

        Session::flash('alert', __('Registration successfully updated'));
        Session::flash('alert-type', 'success');
        Redirect::route('admin.catalog.product.show', $this->product);
    }

    private function saveProduct(ProductService $productService) {
        $productService->sanitizeData($this->product, $this->catalogCategoryArray, $this->catalogGenderArray);

        $dtoData = [
            'technicalDatasheetTmp' => $this->technicalDatasheetTmp,
            'fileDigitalTmp' => $this->fileDigitalTmp,
            'catalogProductWarehousesArray' => $this->catalogProductWarehousesArray,
            'catalogCategoryArray' => $this->catalogCategoryArray,
            'catalogGenderArray' => $this->catalogGenderArray,
            'imageTmp' => $this->imageTmp,
            'imagesTmp' => $this->imagesTmp,
            'imagesTmpBrands' => $this->imagesTmpBrands,
            'hasVariants' => $this->hasVariants,
            'productOptions' => $this->productOptions,
            'productVariants' => $this->productVariants,
        ];

        $productService->saveProduct(
            $this->product,
            $dtoData,
            fn($prod) => $this->saveTranslations($prod)
        );

        $this->loadRandomImagesTmpInputId();
        $this->reset('imagesTmp');
    }

    public function generateVariants(ProductVariantService $variantService) {
        $this->productVariants = $variantService->generateVariants(
            $this->product,
            $this->productOptions,
            $this->productVariants
        );
    }

    public function deleteVariant($variantIndex, ProductVariantService $variantService) {
        $this->productVariants = $variantService->deleteVariant($this->product, $this->productVariants, $variantIndex);
        
        $variantData = $variantService->getOptionsForForm($this->product);
        $this->hasVariants = $variantData['hasVariants'];
        $this->productOptions = $variantData['productOptions'];
        $this->generateVariants($variantService);
    }

    public function removeVariantGalleryImage($variantIndex, $imageIndex, ProductVariantService $variantService) {
        if (isset($this->productVariants[$variantIndex])) {
            $this->productVariants[$variantIndex] = $variantService->removeVariantGalleryImage(
                $this->productVariants[$variantIndex],
                $imageIndex
            );
        }
    }

    public function loadProductImagesBrands(ProductService $productService) {
        $this->reset('imageTmp', 'imagesTmp', 'imagesTmpBrands');
        $files = $productService->fetchBrandImages($this->product);

        if (count($files)) {
            $this->imagesTmpBrands = $files;
        } else {
            $this->dispatch('alert', 'warning', __('Not found images'));
        }
    }

    public function removeImageTemp($variantKey) {
        if (array_splice($this->imagesTmp, $variantKey, 1)) {
            $this->dispatch('alert', 'success', __('Image successfully deleted'));
        }
    }

    public function removeImageMain(ProductService $productService) {
        $productService->removeMainImage($this->product);
        $this->reset('imageTmp');
        $this->dispatch('alert', 'success', __('Image successfully deleted'));
    }

    public function removeImage(Image $image) {
        try {
            $image->delete();
            $this->dispatch('alert', 'success', __('Image successfully deleted'));
        } catch (Exception $e) {
            $this->dispatch('alert', 'warning', $e->getMessage());
        }
    }

    public function removeTechnicalDatasheet(ProductService $productService) {
        $productService->removeTechnicalDatasheet($this->product);
        $this->reset('technicalDatasheetTmp');
        $this->dispatch('alert', 'success', __('Successful elimination'));
    }

    public function removeFileDigital(ProductService $productService) {
        $productService->removeFileDigital($this->product);
        $this->reset('fileDigitalTmp');
        $this->dispatch('alert', 'success', __('Successful elimination'));
    }

    private function loadCatalogData() {
        $this->categories = json_decode(ProductCategory::getCache(), true);
        $this->catalogCategoryArray = $this->product->productCategories->pluck('id')->toArray();

        $this->genders = ProductGender::orderBy('name')->get();
        $this->catalogGenderArray = $this->product->productGenders->pluck('id')->toArray();

        $this->product->status = $this->product->status ?? Product::STATUS_PUBLISHED;
        $this->unitTypes = UnitType::orderBy('name')->get();
        $this->product->type = $this->product->type ?? Product::TYPE_PHYSICAL;
        $this->currencies = Currency::getCache();
        $this->brands = ProductBrand::orderBy('name')->get();
        $this->shippingClasses = ShippingClass::orderBy('id', 'desc')->get();
        $this->productImages = $this->product->images->sortBy('id');

        $this->warehouses = ProductWarehouse::get();
        foreach ($this->product->productWarehouses as $warehouse) {
            $this->catalogProductWarehousesArray[$warehouse->id] = $warehouse->pivot->quantity;
        }
    }

    private function loadRandomImagesTmpInputId() {
        $this->imagesTmpInputId = rand(1, 1000).'-'.$this->product->id;
    }
}
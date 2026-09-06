<?php

namespace App\Livewire\Ecommerce\Product;

use App\Models\File;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Cart\CartService;
use App\Integrations\VadetoBrands\Resources\Catalog\CloudResourceService;
use App\Services\Product\ProductVariantService;
use Exception;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    public $product;
    public $type;
    #[Locked] 
    public $price;
    public $sku;
    #[Locked] 
    public $quantityTotal = 0;
    public $quantitySelected = 1;
    public $variantSelected;
    public $gallery = [];
    #[Locked] 
    public $allOptions = [];
    public $variants = [];
    #[Locked] 
    public $priceToString;
    public $productsSimilars = [];
    public $productsViewRecents = [];
    public $cloudResources = [];

    protected function rules() {
        return [
            'quantitySelected' => 'required|min:1',
        ];
    }
    public function mount(Product $product) {
        $this->product = $product;
        $this->loadType();
        $this->loadAllOptions();
        $this->loadVariants();
        $this->loadData();
        $this->loadCloudResources();
        $this->loadProductsSimilars();
        $this->loadProductsViewRecents();
    }
    public function render() {
        return view('livewire.ecommerce.product.show');
    }
    public function saveCart() {
        $this->validate();
        if ($this->type == Product::TYPE_DIGITAL) {
            $this->quantitySelected = 1;
        }
        $options = [
            'type' => $this->type,
            'price' => $this->price,
            'currency' => currency(),
            'image' => $this->product->imagePreview(),
        ];
        if ($this->variantSelected) {
            $options['image'] = $this->variantSelected->imagePreview();
            $options['variant'] = [
                'id' => $this->variantSelected->id,
                'sku' => $this->variantSelected->sku,
                'options' => $this->getOptionFormat($this->variantSelected),
            ];
        }
        try {
            CartService::add($this->product, $this->quantitySelected, $this->price, $options);
            $this->dispatch('render')->to('ecommerce.layouts.cart');
            $this->dispatch('notify-add-cart', $this->product->name, route('ecommerce.product.show', $this->product), $options['image']);
            $this->reset('quantitySelected');
        } catch (Exception $e) {
            $this->dispatch('alert', 'warning', __($e->getMessage()));
        }
    }
    // LOADS
    private function loadType() {
        $this->type = $this->product->getType();
        if ($this->type == Product::TYPE_PHYSICAL_AND_DIGITAL) {
            $this->type = Product::TYPE_PHYSICAL;
        }
    }
    private function loadVariants() {
        $variantService = new ProductVariantService();
        $this->variants = $variantService->getVariantsSummary($this->product);
    }
    private function loadData(): void {
        if ($this->variantSelected) {
            $this->price = $this->variantSelected->getPriceFinal();
            $this->priceToString = $this->variantSelected->getPriceToString();
            $this->sku = $this->variantSelected->sku;
            $this->quantityTotal = ($this->type == Product::TYPE_PHYSICAL) ? $this->variantSelected->getQuantityTotal() : 1;
        } else {
            $this->price = $this->product->getPriceFinal();
            $this->priceToString = $this->product->getPriceToString();
            $this->sku = $this->product->sku;
            $this->quantityTotal = ($this->type == Product::TYPE_PHYSICAL) ? $this->product->getQuantityTotal() : 1;
        }

        $variantService = new ProductVariantService();
        $this->gallery = $variantService->getGallery($this->product, $this->variantSelected);
        $this->dispatch('galleryUpdated');
    }
    private function loadProductsSimilars() {
        if (count($this->product->productSimilars)) {
            $ids = $this->product->productSimilars->pluck('product_similar_id');
            $this->productsSimilars = Product::query()->withRelations()->validateProduct()->whereIn('id', $ids)->where('id', '<>', $this->product->id)->get();
        } else {
            if ($category = $this->product->productCategories->first()) {
                $this->productsSimilars = Product::query()->withRelations()->validateProduct()->inRandomOrder()->whereHas('productCategories', function ($query) use ($category) {
                    $query->whereIn('product_category_id', [$category->id]);
                })->where('id', '<>', $this->product->id)->take(5)->get();
            }
        }
    }
    public function loadCloudResources() {
        $cloudResourceService = new CloudResourceService;
        $brand = $this->product->productBrand->name ?? '';
        $sku = $this->product->sku;
        $language = explode('_', language());
        $language = $language[0];
        $this->cloudResources = $cloudResourceService->find($brand, $language, $sku);
    }
    public function loadProductsViewRecents() {
        $this->productsViewRecents = Product::getViewRecents();
    }
    public function loadAllOptions() {
        $variantService = new ProductVariantService();
        $this->allOptions = $variantService->getOptionsCatalog($this->product);
    }
    // GETS
    private function getOptionFormat($variant) {
        $variantService = new ProductVariantService();
        return $variantService->formatForCart($variant);
    }
    public function getTypes() {
        $types = [];
        if ($this->product->getIsPhysical()) {
            $types[Product::TYPE_PHYSICAL] = Product::TYPE_PHYSICAL;
        }
        if ($this->product->getIsDigital()) {
            $types[Product::TYPE_DIGITAL] = Product::TYPE_DIGITAL;
        }

        return $types;
    }
    public function getFileImg($urlFile) {
        $file = new File;
        $extension = pathinfo($urlFile, PATHINFO_EXTENSION);

        return $file->iconPreview($extension);
    }
    public function getFileName($urlFile) {
        $parsedUrl = parse_url($urlFile);
        $queryString = $parsedUrl['query'];
        parse_str($queryString, $query_params);
        $fileName = $query_params['file'];

        return $fileName;
    }
    // SELECT
    public function selectVariant($variantId = null) {
       $this->variantSelected = $variantId 
            ? ProductVariant::with(['product.currency', 'productImages', 'productWarehouses'])->find($variantId)
            : null;
        $this->loadData();
    }
}

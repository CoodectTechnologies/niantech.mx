<?php

namespace App\Livewire\Ecommerce\Product;

use App\Models\File;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Cart\CartService;
use App\Services\Integrations\VadetoBrands\Product\CloudResourceService;
use Exception;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Show extends Component
{
    public $product;
    public $type;
    public $price;
    public $sku;
    public $quantityTotal = 0;
    public $quantitySelected = 1;
    public $variantSelected;
    public $gallery = [];
    public $allOptions = [];
    public $variants = [];
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
        $this->loadPrice();
        $this->loadSku();
        $this->loadGallery();
        $this->loadQuantityTotal();
        $this->loadAllOptions();
        $this->loadVariants();
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
            $this->dispatch('notify-add-cart', $this->product->name, route('ecommerce.product.show', $this->product), $this->product->imagePreview());
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
    private function loadPrice() {
        if ($this->variantSelected) {
            $this->price = $this->variantSelected->getPriceFinal();
            $this->priceToString = $this->variantSelected->getPriceToString();
        } else {
            $this->price = $this->product->getPriceFinal();
            $this->priceToString = $this->product->getPriceToString();
        }
    }
    private function loadSku() {
        if ($this->variantSelected) {
            $this->sku = $this->variantSelected->sku;
        } else {
            $this->sku = $this->product->sku;
        }
    }
    public function loadGallery() {
        $this->gallery = [];
        if (! $this->variantSelected) {
            $this->gallery = array_merge([$this->product->imagePreview()], $this->product->imagesPreview()->toArray());
        } else {
            if ($this->variantSelected && $this->variantSelected->images->count() > 0) {
                $gallery = $this->variantSelected->images->pluck('url')->toArray();
                foreach ($gallery as $key => $image) {
                    $this->gallery[] = Storage::url($image);
                }
            } else {
                $this->gallery = array_merge([$this->product->imagePreview()], $this->product->imagesPreview()->toArray());
            }
        }
        $this->dispatch('galleryUpdated');
    }
    private function loadQuantityTotal() {
        if ($this->type == Product::TYPE_PHYSICAL) {
            if ($this->variantSelected) {
                $this->quantityTotal = $this->variantSelected->getQuantityTotal();
            } elseif (count($this->product->productVariants)) {
                $this->quantityTotal = 0;
                foreach ($this->product->productVariants as $variant) {
                    $this->quantityTotal += $variant->getQuantityTotal();
                }
            } else {
                $this->quantityTotal = $this->product->getQuantityTotal();
            }
        } elseif ($this->type == Product::TYPE_DIGITAL) {
            $this->quantityTotal = 1;
        } else {
            $this->quantityTotal = 0;
        }
    }
    private function loadVariants() {
        $this->variants = $this->product->productVariants
            ->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'variant_key' => $variant->variant_key,
                    'sku' => $variant->sku,
                    'price' => $variant->price,
                    'price_promotion' => $variant->price_promotion,
                    'quantity_total' => $variant->getQuantityTotal(),
                    'option_values' => $variant->productOptionValues->pluck('id')->toArray(),
                ];
            })->toArray();
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
        // Obtener todas las opciones únicas de las variantes del producto
        $this->allOptions = [];
        $variants = $this->product->productVariants()
            ->with('productOptionValues.productOption')
            ->validateVariant()
            ->get();
        foreach ($variants as $variant) {
            foreach ($variant->productOptionValues as $optionValue) {
                $optionId = $optionValue->productOption->id;
                $valueId = $optionValue->id;
                if (! isset($this->allOptions[$optionId])) {
                    $this->allOptions[$optionId] = [
                        'id' => $optionId,
                        'name' => $optionValue->productOption->name,
                        'slug' => $optionValue->productOption->slug,
                        'values' => [],
                    ];
                }
                // Agregar valor si no existe
                if (! isset($this->allOptions[$optionId]['values'][$valueId])) {
                    $this->allOptions[$optionId]['values'][$valueId] = [
                        'id' => $valueId,
                        'value' => $optionValue->value,
                        'slug' => $optionValue->slug,
                    ];
                }
            }
        }
        $this->allOptions = array_values($this->allOptions);
    }

    // GETS
    private function getOptionFormat($variant) {
        $options = [];
        foreach ($variant->productOptionValues as $optionValue) {
            $optionId = $optionValue->productOption->id;
            if (! isset($options[$optionId])) {
                $options[$optionId] = [
                    'id' => $optionId,
                    'option_name' => $optionValue->productOption->name,
                    'option_value' => $optionValue->value,
                ];
            }
        }

        return $options;
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
        $this->variantSelected = null;
        if ($variantId) {
            $this->variantSelected = ProductVariant::with(['product.currency', 'images', 'productWarehouses'])->find($variantId);
        }
        $this->loadPrice();
        $this->loadGallery();
        $this->loadQuantityTotal();
        $this->loadSku();
    }
}

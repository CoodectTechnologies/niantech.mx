<?php

namespace App\Livewire\Admin\Catalog\Product\Product;

use App\Models\Currency;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ProductGender;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use App\Models\ProductWarehouse;
use App\Models\ShippingClass;
use App\Models\UnitType;
use App\Services\Integrations\VadetoBrands\Product\ImageService;
use App\Services\Synchronizers\Catalog\ProductController;
use App\Traits\LivewireTranslatable;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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

    // Data
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
    public function mount(Product $product, $method) {
        $this->product = $product;
        $this->method = $method;
        $this->product->load([
            'productCategories',
            'productGenders',
            'productWarehouses',
            'productVariants.productOptionValues.productOption',
        ]);
        $this->loadRandomImagesTmpInputId();
        $this->loadProductCategories();
        $this->loadProductWarehouses();
        $this->loadShippingClasses();
        $this->loadProductGenders();
        $this->loadProductStatus();
        $this->loadProductImages();
        $this->loadProductBrands();
        $this->loadProductType();
        $this->loadCurrencies();
        $this->loadVariantsOptions();
        $this->loadTranslations($this->product);
    }
    public function render() {
        return view('livewire.admin.catalog.product.product.form');
    }
    public function store() {
        $this->validate();
        $this->validateCleanData();
        $this->product->user_id = Auth::id();
        $this->_saveTechnicalDatasheet();
        $this->_saveFileDigital();
        $this->saveTranslations($this->product);
        $this->product->save();
        $this->save();
        Session::flash('alert', __('Registration successfully added'));
        Session::flash('alert-type', 'success');
        Redirect::route('admin.catalog.product.show', $this->product);
    }
    public function update() {
        $this->validate();
        $this->validateCleanData();
        $this->_saveTechnicalDatasheet();
        $this->_saveFileDigital();
        $this->saveTranslations($this->product);
        $this->product->update();
        $this->save();
        Session::flash('alert', __('Registration successfully updated'));
        Session::flash('alert-type', 'success');
        Redirect::route('admin.catalog.product.show', $this->product);
    }
    private function save() {
        $this->saveWarehouses();
        $this->saveImage();
        $this->saveImages();
        $this->saveImagesBrands();
        $this->saveCategories();
        $this->saveGenders();
        $this->saveVariants();
        $this->loadRandomImagesTmpInputId();
        $this->reset('imagesTmp');
    }

    /*  ========================================================================= */
    /*  PRODUCTS GENERAL */
    /*  ========================================================================= */
    private function saveWarehouses() {
        $catalogProductWarehousesArray = [];
        foreach ($this->catalogProductWarehousesArray as $warehouseId => $quantity) {
            if ($quantity) {
                $catalogProductWarehousesArray[$warehouseId] = ['quantity' => $quantity];
            }
        }
        $this->product->productWarehouses()->sync($catalogProductWarehousesArray);
    }
    private function saveImage() {
        if ($this->imageTmp) {
            $url = $this->imageTmp->store('catalog/product');
            imageManager($url, 800, $this->product);
        }
    }
    private function saveImages() {
        if ($this->imagesTmp) {
            foreach ($this->imagesTmp as $imgTmp) {
                $url = $imgTmp->store('catalog/product/gallery');
                imagesManager($url, 800, $this->product);
            }
        }
    }
    private function saveImagesBrands() {
        if (count($this->imagesTmpBrands)) {
            $productController = new ProductController;
            $productController->image(product: $this->product, onlyProvider: 'imagesVadetoBrands');
        }
    }
    private function saveCategories() {
        // Filtrar solo IDs válidos que existan en la BD
        $validCategoryIds = array_filter($this->catalogCategoryArray, function ($id) {
            return is_numeric($id) && $id > 0 && ProductCategory::where('id', $id)->exists();
        });

        $this->product->productCategories()->sync($validCategoryIds);
    }
    private function saveGenders() {
        $this->product->productGenders()->sync($this->catalogGenderArray);
    }
    private function _saveTechnicalDatasheet() {
        if ($this->technicalDatasheetTmp) {
            $url = $this->technicalDatasheetTmp->store('product/technical-datasheet');
            if ($this->product->technical_datasheet) {
                if (Storage::exists($this->product->technical_datasheet)) {
                    Storage::delete($this->product->technical_datasheet);
                }
            }
            $this->product->technical_datasheet = $url;
        }
    }
    private function _saveFileDigital() {
        if ($this->fileDigitalTmp) {
            $url = $this->fileDigitalTmp->store('product/file-digital');
            if ($this->product->file_digital) {
                if (Storage::exists($this->product->file_digital)) {
                    Storage::delete($this->product->file_digital);
                }
            }
            $this->product->file_digital = $url;
        }
    }
    public function removeImageTemp($variantKey) {
        if (array_splice($this->imagesTmp, $variantKey, 1)) {
            $this->dispatch('alert', 'success', __('Image successfully deleted'));
        }
    }
    public function removeImageMain() {
        if ($this->product->image) {
            if (Storage::exists($this->product->image->url)) {
                Storage::delete($this->product->image->url);
            }
            $this->product->image()->delete();
            $this->product->image = null;
        }
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
    public function removeTechnicalDatasheet() {
        if ($this->product->technical_datasheet) {
            if (Storage::exists($this->product->technical_datasheet)) {
                Storage::delete($this->product->technical_datasheet);
            }
            $this->product->technical_datasheet = null;
            $this->product->update();
        }
        $this->reset('technicalDatasheetTmp');
        $this->dispatch('alert', 'success', __('Successful elimination'));
    }
    public function removeFileDigital() {
        if ($this->product->file_digital) {
            if (Storage::exists($this->product->file_digital)) {
                Storage::delete($this->product->file_digital);
            }
            $this->product->file_digital = null;
            $this->product->update();
        }
        $this->reset('fileDigitalTmp');
        $this->dispatch('alert', 'success', __('Successful elimination'));
    }
    private function loadProductCategories() {
        $this->categories = json_decode(ProductCategory::getCache(), true);
        $this->catalogCategoryArray = $this->product->productCategories->pluck('id')->toArray();
    }
    private function loadProductGenders() {
        $this->genders = ProductGender::orderBy('name')->get();
        $this->catalogGenderArray = $this->product->productGenders->pluck('id')->toArray();
    }
    private function loadProductStatus() {
        $this->product->status = $this->product->status ?? Product::STATUS_PUBLISHED;
    }
    private function loadProductType() {
        $this->unitTypes = UnitType::orderBy('name')->get();
        $this->product->type = $this->product->type ?? Product::TYPE_PHYSICAL;
    }
    private function loadCurrencies() {
        $this->currencies = Currency::getCache();
    }
    private function loadProductBrands() {
        $this->brands = ProductBrand::orderBy('name')->get();
    }
    private function loadShippingClasses() {
        $this->shippingClasses = ShippingClass::orderBy('id', 'desc')->get();
    }
    private function loadProductImages() {
        $this->productImages = $this->product->images->sortBy('id');
    }
    public function loadProductImagesBrands() {
        if (
            config('services.vadeto_brands.status') &&
            config('services.vadeto_brands.download_image_product') &&
            ($this->product->sku && $this->product->provider_id)
        ) {
            $this->reset('imageTmp', 'imagesTmp', 'imagesTmpBrands');
            $brand = $this->product->productBrand->name ?? null;
            $sku = $this->product->sku ?? null;
            $imageService = new ImageService;
            $imagesBrands = $imageService->getAll($brand, language(), $sku);
            $files = [];
            foreach ($imagesBrands as $imagesBySku) {
                if (isset($imagesBySku[$sku]) && count($imagesBySku[$sku])) {
                    $files = array_merge($files, $imagesBySku[$sku]);
                }
            }
            $files = array_values(array_unique($files));
            if (count($files)) {
                $this->imagesTmpBrands = $files;
            } else {
                $this->dispatch('alert', 'warning', __('Not found images'));
            }
        }
    }
    private function loadProductWarehouses() {
        $this->warehouses = ProductWarehouse::get();
        $catalogProductWarehousesArray = $this->product->productWarehouses;
        foreach ($catalogProductWarehousesArray as $warehouse) {
            $this->catalogProductWarehousesArray[$warehouse->id] = $warehouse->pivot->quantity;
        }
    }
    private function loadRandomImagesTmpInputId() {
        $this->imagesTmpInputId = rand(1, 1000).'-'.$this->product->id;
    }
    private function validateCleanData() {
        if ($this->product->weight_kl == '') {
            $this->product->weight_kl = null;
        }
        if ($this->product->height == '') {
            $this->product->height = null;
        }
        if ($this->product->width == '') {
            $this->product->width = null;
        }
        if ($this->product->length == '') {
            $this->product->length = null;
        }
        if ($this->product->product_brand_id == '') {
            $this->product->product_brand_id = null;
        }
        if ($this->product->shipping_class_id == '') {
            $this->product->shipping_class_id = null;
        }
        if ($this->product->downloadable == '') {
            $this->product->downloadable = null;
        }
        if ($this->product->price == '') {
            $this->product->price = null;
        }
        if ($this->product->price_promotion == '') {
            $this->product->price_promotion = null;
        }
        if ($this->product->cost == '') {
            $this->product->cost = null;
        }
        if (
            isset($this->catalogCategoryArray[0]) &&
            $this->catalogCategoryArray[0] == __('Without categories')
        ) {
            $this->catalogCategoryArray = [];
        }
        if (
            isset($this->catalogGenderArray[0]) &&
            $this->catalogGenderArray[0] == __('Without gender')
        ) {
            $this->catalogGenderArray = [];
        }
    }

    /*  ========================================================================= */
    /*  PRODUCTS VARIANTS */
    /*  ========================================================================= */
    private function saveVariants() {
        if (! $this->hasVariants) {
            // Si no tiene variantes, eliminar cualquier variante existente
            $this->product->productVariants()->delete();

            return;
        }

        /** SALVAR CAMBIOS EN CATALOGO DE OPCIONES Y VALORES DE OPCIONES **/
        $valueMap = [];
        foreach ($this->productOptions as $option) {
            $name = $option['name'] ?? '';
            $values = $option['values'] ?? [];
            if (trim($name) === '' || empty($values)) {
                continue;
            }
            // Buscar o crear opción en el catálogo global
            $catalogOption = ProductOption::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
            $valueMap[$name] = [];
            // Crear valores en el catálogo global
            foreach ($values as $vIndex => $value) {
                if (trim($value) === '') {
                    continue;
                }
                $valueRecord = ProductOptionValue::firstOrCreate(
                    [
                        'product_option_id' => $catalogOption->id,
                        'value' => $value,
                    ],
                    ['position' => $vIndex + 1]
                );
                $valueMap[$name][$value] = $valueRecord->id;
            }
        }

        /** VARIANTES **/
        $savedVariantIds = [];
        foreach ($this->productVariants as $index => $variantData) {
            // Buscar variante existente por ID o variant_key + product_id
            $variant = null;
            if ($variantData['id']) {
                $variant = ProductVariant::find($variantData['id']);
            }
            if (! $variant && $variantData['variant_key']) {
                $variant = ProductVariant::where('variant_key', $variantData['variant_key'])->where('product_id', $this->product->id)->first();
            }
            // Si no existe, crear nueva variante
            if (! $variant) {
                $variant = new ProductVariant;
                $variant->product_id = $this->product->id;
                $finalSku = $variantData['sku'];
                if ($this->skuExistsVariant($finalSku, null)) {
                    $finalSku = $this->generateUniqueVariantSku($variantData['option_values'], null);
                }
                $variant->sku = $finalSku;
            } else {
                if ($variant->sku !== $variantData['sku']) {
                    if (! $this->skuExistsVariant($variantData['sku'], $variant->id)) {
                        $variant->sku = $variantData['sku'];
                    }
                }
            }
            // Actualizar datos
            $variant->variant_key = $variantData['variant_key'];
            $variant->price = $variantData['price'];
            $variant->price_promotion = $variantData['price_promotion'] ? $variantData['price_promotion'] : null;
            $variant->cost = $variantData['cost'] ? $variantData['cost'] : null;
            $variant->weight_kl = $variantData['weight_kl'] ? $variantData['weight_kl'] : null;
            $variant->height = $variantData['height'] ? $variantData['height'] : null;
            $variant->width = $variantData['width'] ? $variantData['width'] : null;
            $variant->length = $variantData['length'] ? $variantData['length'] : null;
            $variant->is_active = $variantData['is_active'];
            $variant->position = $index + 1;
            $variant->save();
            $savedVariantIds[] = $variant->id;
            // Obtener IDs de valores buscando en todas las opciones
            $valueIds = [];
            foreach ($variantData['option_values'] as $value) {
                foreach ($valueMap as $optionName => $values) {
                    if (isset($values[$value])) {
                        $valueIds[] = $values[$value];
                        break;
                    }
                }
            }
            // Sincronizar los valores de opciones usando la tabla pivot product_variant_options
            $variant->productOptionValues()->sync($valueIds);
            // Guardar galería múltiple
            if (! empty($variantData['gallery_images_tmp'])) {
                foreach ($variantData['gallery_images_tmp'] as $imageTmp) {
                    $url = $imageTmp->store('catalog/product/variant/gallery');
                    imagesManager($url, 800, $variant);
                }
            }
            // Guardar warehouses usando sync
            $warehousesData = [];
            foreach ($variantData['warehouses'] as $warehouseId => $qty) {
                $warehousesData[$warehouseId] = ['quantity' => $qty];
            }
            $variant->productWarehouses()->sync($warehousesData);
        }
        // Eliminar variantes que ya no existen
        $this->product->productVariants()->whereNotIn('id', $savedVariantIds)->delete();
    }
    private function loadVariantsOptions() {
        $this->hasVariants = $this->product->productVariants()->where('variant_key', '!=', 'default')->exists();
        if ($this->hasVariants) {
            $productOptions = [];
            foreach ($this->product->productVariants as $variant) {
                foreach ($variant->productOptionValues as $pov) {
                    $name = $pov->productOption->name;
                    $value = $pov->value;
                    if (! isset($productOptions[$name])) {
                        $productOptions[$name] = [
                            'name' => $name,
                            'values' => [],
                        ];
                    }
                    if (! in_array($value, $productOptions[$name]['values'])) {
                        $productOptions[$name]['values'][] = $value;
                    }
                }
            }
            $this->productOptions = array_values($productOptions);
        }
        $this->generateVariants();
    }
    public function generateVariants() {
        if (empty($this->productOptions)) {
            $this->productVariants = [];

            return;
        }

        // Convertir a formato asociativo para el generador cartesiano
        $optionsAssoc = [];
        foreach ($this->productOptions as $option) {
            $name = $option['name'] ?? '';
            $values = array_filter($option['values'], fn ($v) => trim($v) !== '');
            if (trim($name) === '' || empty($values)) {
                continue;
            }
            $optionsAssoc[$name] = $values;
        }

        $combinations = $this->generateVariationCartesian($optionsAssoc);

        // Construir mapa de variantes existentes (Aseguramos que sea una colección base)
        $existing = collect($this->productVariants)->keyBy('variant_key');

        // Si el producto ya existe en BD, cargar variantes de BD también
        if ($this->product->exists) {
            $dbVariants = $this->product->productVariants()
                ->with(['productOptionValues', 'productWarehouses', 'images'])
                ->get()
                ->toBase() // <--- ¡AGREGA ESTO AQUÍ para convertir la colección Eloquent a una Colección Base!
                ->map(function ($variant) {
                    $combo = $variant->productOptionValues->pluck('value')->values()->toArray();
                    $variant = $this->buildVariantArray($variant, $combo);

                    return $variant;
                })
                ->keyBy('variant_key');

            $existing = $dbVariants->merge($existing);
        }

        // Generar array final de variantes
        $variants = [];
        foreach ($combinations as $index => $combo) {
            $variantKey = collect(array_values($combo))
                ->map(fn ($v) => strtolower(trim($v)))
                ->filter()
                ->sort()
                ->values()
                ->implode('|');

            if ($existing->has($variantKey)) {
                // Reutilizar variante existente
                $variants[] = $existing[$variantKey];
            } else {
                // Crear nueva variante, pero intentar heredar datos de variantes antiguas
                $inheritedData = $this->findBestMatchForInheritanceVariant($combo, $existing);
                $variant = array_merge([
                    'variant_key' => $variantKey,
                    'sku' => $this->generateUniqueVariantSku($combo),
                ], $inheritedData);
                $variant = $this->buildVariantArray($variant, $combo);
                $variants[] = $variant;
            }
        }

        $this->productVariants = $variants;
    }
    private function buildVariantArray($variant, $combo): array {
        $variant = collect($variant);

        return [
            'id' => $variant['id'] ?? null,
            'variant_key' => $variant['variant_key'],
            'option_values' => $combo,
            'sku' => $variant['sku'] ?? null,
            'price' => $variant['price'] ?? $this->product->price,
            'price_promotion' => $variant['price_promotion'] ?? null,
            'cost' => $variant['cost'] ?? $this->product->cost,
            'weight_kl' => $variant['weight_kl'] ?? $this->product->weight_kl,
            'height' => $variant['height'] ?? $this->product->height,
            'width' => $variant['width'] ?? $this->product->width,
            'length' => $variant['length'] ?? $this->product->length,
            'is_active' => $variant['is_active'] ?? true,
            'position' => $variant['position'] ?? 1,
            'warehouses' => isset($variant['product_variant_warehouses'])
                ? collect($variant['product_variant_warehouses'])->pluck('quantity', 'product_warehouse_id')->toArray()
                : [],
            'gallery_images' => isset($variant['images'])
                ? collect($variant['images'])->map(fn ($img) => ['id' => $img['id'], 'url' => Storage::url($img['url'])])->toArray()
                : [],
            'gallery_images_tmp' => [],
        ];
    }
    private function findBestMatchForInheritanceVariant($newCombo, $existingVariants) {
        $newValues = array_values($newCombo);
        $bestMatch = null;
        $maxMatches = 0;
        foreach ($existingVariants as $variant) {
            // Contar cuántos valores coinciden
            $matches = 0;
            foreach ($newValues as $newValue) {
                if (in_array($newValue, $variant['option_values'])) {
                    $matches++;
                }
            }
            // Si tiene más coincidencias que el mejor match actual, lo guardamos
            if ($matches > $maxMatches && $matches > 0) {
                $maxMatches = $matches;
                $bestMatch = $variant;
            }
        }
        // Si encontramos un match, devolver sus datos (sin ID ni key)
        if ($bestMatch) {
            return [
                'price' => $bestMatch['price'],
                'price_promotion' => $bestMatch['price_promotion'] ?? null,
                'cost' => $bestMatch['cost'],
                'weight_kl' => $bestMatch['weight_kl'] ?? null,
                'height' => $bestMatch['height'] ?? null,
                'width' => $bestMatch['width'] ?? null,
                'length' => $bestMatch['length'] ?? null,
                'is_active' => $bestMatch['is_active'],
                'warehouses' => $bestMatch['warehouses'] ?? [],
                'gallery_images' => $bestMatch['gallery_images'] ?? [],
            ];
        }

        return [];
    }
    private function skuExistsVariant($sku, $excludeId = null) {
        $query = ProductVariant::where('sku', $sku);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
    private function generateUniqueVariantSku(array $combo, ?int $excludeId = null): string {
        $variantSkuBase = $this->product->sku ?? 'PROD';
        $variantSkuSuffix = collect($combo)->map(fn ($v) => strtoupper(substr($v, 0, 3)))->implode('-');
        $variantSku = "{$variantSkuBase}-{$variantSkuSuffix}";
        // Verificar si el SKU ya existe y agregar sufijo numérico
        $originalSku = $variantSku;
        $counter = 1;
        while ($this->skuExistsVariant($variantSku, $excludeId)) {
            $variantSku = "{$originalSku}-{$counter}";
            $counter++;
        }

        return $variantSku;
    }
    private function generateVariationCartesian(array $options): array {
        $result = [[]];
        foreach ($options as $name => $values) {
            if (trim($name) === '' || empty($values)) {
                continue;
            }
            $tmp = [];
            foreach ($result as $combination) {
                foreach ($values as $value) {
                    if (trim($value) === '') {
                        continue;
                    }

                    $tmp[] = $combination + [
                        $name => $value,
                    ];
                }
            }
            $result = $tmp;
        }

        return $result;
    }
    public function removeVariantGalleryImage($variantIndex, $imageIndex) {
        $imageData = $this->productVariants[$variantIndex]['gallery_images'][$imageIndex] ?? null;
        if ($imageData && ! empty($imageData['id'])) {
            $image = Image::find($imageData['id']);
            if ($image) {
                Storage::delete($image->url);
                $image->delete();
            }
        }
        unset($this->productVariants[$variantIndex]['gallery_images'][$imageIndex]);
        $this->productVariants[$variantIndex]['gallery_images'] = array_values($this->productVariants[$variantIndex]['gallery_images']);
    }
    public function deleteVariant($variantIndex) {
        // Si la variante existe en BD, eliminar con sus imágenes
        if (! empty($this->productVariants[$variantIndex]['id'])) {
            $variant = ProductVariant::find($this->productVariants[$variantIndex]['id']);
            if ($variant) {
                // Eliminar galería
                foreach ($variant->images as $image) {
                    Storage::delete($image->url);
                    $image->delete();
                }
                $variant->delete();
            }
        }
        // Eliminar del array local
        unset($this->productVariants[$variantIndex]);
        $this->productVariants = array_values($this->productVariants);
        // Recargar variantes desde BD para mantener sincronización
        // if($this->product->exists):
        $this->loadVariantsOptions();
        // endif;
    }
}

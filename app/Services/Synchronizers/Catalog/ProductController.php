<?php

namespace App\Services\Synchronizers\Catalog;

use App\Http\Controllers\Controller;
use App\Integrations\Odoo;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductCategory;
use App\Models\ProductCharacteristic;
use App\Models\UnitType;
use App\Services\Integrations\Odoo\Product\ProductService;
use App\Services\Integrations\Odoo\Product\WarehouseService;
use App\Services\Integrations\VadetoBrands\Product\ImageService;
use App\Services\Integrations\VadetoBrands\Product\ProductService as VadetoBrandsProductService;
use App\Services\Synchronizers\Currency\CurrencyController;

class ProductController extends Controller
{
    public $productService;
    public $warehouseService;
    public $contentService;
    public $imageService;

    public function __construct() {
        $this->productService = new ProductService;
        $this->warehouseService = new WarehouseService;
        $this->contentService = new VadetoBrandsProductService;
        $this->imageService = new ImageService;
    }

    /*  ========================================================================= */
    /*  GENERAL */
    /*  ========================================================================= */
    public function save() {
        return activity()->withoutLogs(function () {
            $startTime = microtime(true);
            $result = [
                'products' => ['created' => 0, 'updated' => 0],
                'categories' => ['attached' => 0, 'detached' => 0, 'updated' => 0],
            ];
            $products = Product::with('productCategories')->where('provider', Odoo::$code)->whereNotNull('provider_id')->get()->keyBy('provider_id');
            foreach ($this->productService->getAll() as $productsProvider) {
                foreach ($productsProvider as $productProvider) {
                    if (! isset($products[$productProvider['provider_id']])) {
                        $product = $this->create($productProvider);
                        $result['products']['created'] += 1;
                    } else {
                        $product = $products[$productProvider['provider_id']];
                        $isUpdate = $this->update($product, $productProvider);
                        if ($isUpdate) {
                            $result['products']['updated'] += 1;
                        }
                    }
                    $result = $this->categories($product, $productProvider, $result);
                }
            }
            if (
                $result['products']['created'] ||
                $result['categories']['attached'] ||
                $result['categories']['detached'] ||
                $result['categories']['updated']
            ) {
                ProductCategory::regenerateCache();
            }
            $endTime = microtime(true);
            $result['time'] = $endTime - $startTime;

            return $result;
        });
    }
    protected function create($productProvider) {
        $brandId = BrandController::save($productProvider['brand']);
        $currencyId = CurrencyController::save($productProvider['currency']);
        $unitTypeId = UnitType::getUnitTypeIdByCode('H87'); // Pieza
        $product = Product::create([
            'product_brand_id' => $brandId,
            'currency_id' => $currencyId,
            'unit_type_id' => $unitTypeId,
            'sku' => strval($productProvider['sku']),
            'provider' => $productProvider['provider'],
            'provider_id' => $productProvider['provider_id'],
            'name' => $productProvider['name'],
            'name_commercial' => $productProvider['name_commercial'],
            'cost' => $productProvider['cost'],
            'price' => $productProvider['price'],
            'width' => $productProvider['width'],
            'weight_kl' => $productProvider['weight'],
            'height' => $productProvider['height'],
            'detail' => $productProvider['detail'],
            'volume' => $productProvider['volume'],
            'description' => $productProvider['description'],
            'status' => Product::STATUS_PUBLISHED,
        ]);

        return $product;
    }
    protected function update($product, $productProvider) {
        $isUpdate = false;
        foreach (languages() as $languageCode => $language) {
            if (isset($productProvider['name'][$languageCode]) && $productProvider['name'][$languageCode] && $product->getTranslation('name', $languageCode, false) != $productProvider['name'][$languageCode]) {
                $isUpdate = true;
                $product->setTranslation('name', $languageCode, $productProvider['name'][$languageCode]);
            }
            if (isset($productProvider['name_commercial'][$languageCode]) && $productProvider['name_commercial'][$languageCode] && $product->getTranslation('name_commercial', $languageCode, false) != $productProvider['name_commercial'][$languageCode]) {
                $isUpdate = true;
                $product->setTranslation('name_commercial', $languageCode, $productProvider['name_commercial'][$languageCode]);
            }
            if (isset($productProvider['detail'][$languageCode]) && $productProvider['detail'][$languageCode] && $product->getTranslation('detail', $languageCode, false) != $productProvider['detail'][$languageCode]) {
                $isUpdate = true;
                $product->setTranslation('detail', $languageCode, $productProvider['detail'][$languageCode]);
            }
            if (isset($productProvider['description'][$languageCode]) && $productProvider['description'][$languageCode] && $product->getTranslation('description', $languageCode, false) != $productProvider['description'][$languageCode]) {
                $isUpdate = true;
                $product->setTranslation('description', $languageCode, $productProvider['description'][$languageCode]);
            }
        }
        $brandId = BrandController::save($productProvider['brand']);
        if ($brandId != $product->product_brand_id) {
            $product->product_brand_id = $brandId;
            $isUpdate = true;
        }
        $currencyId = CurrencyController::save($productProvider['currency']);
        if ($currencyId != $product->currency_id) {
            $product->currency_id = $currencyId;
            $isUpdate = true;
        }
        // if($product->unit_type_id != UnitType::getUnitTypeIdByCode('H87')):
        //     $product->unit_type_id = UnitType::getUnitTypeIdByCode('H87');
        //     $isUpdate = true;
        // endif;
        if ($product->provider != $productProvider['provider']) {
            $product->provider = $productProvider['provider'];
            $isUpdate = true;
        }
        if ($product->provider_id != $productProvider['provider_id']) {
            $product->provider_id = $productProvider['provider_id'];
            $isUpdate = true;
        }
        if (abs($product->price - $productProvider['price']) > 0.00001 && $productProvider['price']) {
            $product->price = $productProvider['price'];
            $isUpdate = true;
        }
        if (abs($product->cost - $productProvider['cost']) > 0.00001 && $productProvider['cost']) {
            $product->cost = $productProvider['cost'];
            $isUpdate = true;
        }
        if (abs($product->width - $productProvider['width']) > 0.00001 && $productProvider['width']) {
            $product->width = $productProvider['width'];
            $isUpdate = true;
        }
        if (abs($product->weight_kl - $productProvider['weight']) > 0.00001 && $productProvider['weight']) {
            $product->weight_kl = $productProvider['weight'];
            $isUpdate = true;
        }
        if (abs($product->height - $productProvider['height']) > 0.00001 && $productProvider['height']) {
            $product->height = $productProvider['height'];
            $isUpdate = true;
        }
        if (abs($product->volume - $productProvider['volume']) > 0.00001 && $productProvider['volume']) {
            $product->volume = $productProvider['volume'];
            $isUpdate = true;
        }
        if ($isUpdate) {
            $product->update();
        }

        return $isUpdate;
    }

    /*  ========================================================================= */
    /*  CATEGORIES */
    /*  ========================================================================= */
    protected function categories($product, $productProvider, $result) {
        if (isset($productProvider['categories']) && count($productProvider['categories'])) {
            $syncCategories = CategoryController::save($productProvider['categories']);
            $currentSyncData = $product->productCategories->pluck('product_category_id')->toArray();
            if (array_diff($currentSyncData, $syncCategories) || array_diff($syncCategories, $currentSyncData)) {
                $resultSync = $product->productCategories()->sync($syncCategories);
                $result['categories']['attached'] += count($resultSync['attached']);
                $result['categories']['detached'] += count($resultSync['detached']);
                $result['categories']['updated'] += count($resultSync['updated']);
            }
        }

        return $result;
    }

    /*  ========================================================================= */
    /*  STATUS */
    /*  ========================================================================= */
    public function status() {
        return activity()->withoutLogs(function () {
            $startTime = microtime(true);
            $products = Product::where('provider', Odoo::$code)->whereNotNull('provider_id')->get()->keyBy('provider_id');
            $toPublish = [];
            foreach ($this->productService->getAll() as $productsProvider) {
                foreach ($productsProvider as $productProvider) {
                    $providerId = $productProvider['provider_id'];
                    if (isset($products[$providerId])) {
                        $product = $products[$providerId];
                        if ($product->status == Product::STATUS_DRAFT) {
                            $toPublish[] = ['provider_id' => $product->provider_id, 'status' => Product::STATUS_PUBLISHED];
                        }
                        unset($products[$providerId]);
                    }
                }
            }
            $toDraft = [];
            foreach ($products as $product) {
                if ($product->status == Product::STATUS_PUBLISHED) {
                    $toDraft[] = ['provider_id' => $product->provider_id, 'status' => Product::STATUS_DRAFT];
                }
            }
            if (! empty($toDraft)) {
                Product::batchUpdate($toDraft, 'provider_id');
            }
            if (! empty($toPublish)) {
                Product::batchUpdate($toPublish, 'provider_id');
            }
            if (! empty($toDraft) || ! empty($toPublish)) {
                ProductCategory::regenerateCache();
            }

            return [
                'draft' => count($toDraft),
                'published' => count($toPublish),
                'time' => microtime(true) - $startTime,
            ];
        });
    }

    /*  ========================================================================= */
    /*  WAREHOUSES */
    /*  ========================================================================= */
    public function warehouses() {
        return activity()->withoutLogs(function () {
            $result = ['attached' => 0, 'detached' => 0, 'updated' => 0];
            $products = Product::with('productWarehouses')
                ->whereNotNull('provider_id')
                ->get()
                ->keyBy('provider_id');
            $seen = [];
            foreach ($this->warehouseService->getAll() as $warehousesByProduct) {
                foreach ($warehousesByProduct as $providerProductId => $warehouses) {
                    $seen[$providerProductId] = true;
                    if (! isset($products[$providerProductId])) {
                        continue;
                    }
                    $product = $products[$providerProductId];
                    $syncWarehouses = WarehouseController::save($warehouses);
                    $currentSyncData = $product->productWarehouses->mapWithKeys(function ($item) {
                        return [$item->id => ['quantity' => floatval($item->pivot->quantity)]];
                    })->toArray();
                    if (count($currentSyncData) != count($syncWarehouses) || $currentSyncData != $syncWarehouses) {
                        $resultSync = $product->productWarehouses()->sync($syncWarehouses);
                        $result['attached'] += count($resultSync['attached']);
                        $result['detached'] += count($resultSync['detached']);
                        $result['updated'] += count($resultSync['updated']);
                    }
                }
            }
            foreach ($products as $providerProductId => $product) {
                if (! isset($seen[$providerProductId])) {
                    if ($product->productWarehouses->isNotEmpty()) {
                        $product->productWarehouses()->sync([]);
                        $result['detached'] += 1;
                    }
                }
            }

            return $result;
        });
    }

    /*  ========================================================================= */
    /*  CONTENT */
    /*  ========================================================================= */
    public function content() {
        return activity()->withoutLogs(function () {
            $startTime = microtime(true);
            $result = [
                'attributes' => ['created' => 0, 'updated' => 0, 'deleted' => 0],
                'characteristics' => ['created' => 0, 'updated' => 0, 'deleted' => 0],
                'description' => ['updated' => 0],
            ];
            $products = Product::query()
                ->with(['productAttributes', 'productCharacteristics'])
                ->where('provider', Odoo::$code)
                ->whereNotNull('provider_id')
                ->whereNotNull('sku')
                ->get()
                ->keyBy('sku');
            $productsBrands = $this->contentService->getAllContent();
            if (count($products) && count($productsBrands)) {
                foreach ($products as $sku => $product) {
                    $sku = strval(trim($sku));
                    $productProvider = $productsBrands[$sku] ?? [];
                    if (! count($productProvider)) {
                        continue;
                    }
                    // Save description
                    if (isset($productProvider['description']) && count($productProvider['description'])) {
                        $isUpdate = false;
                        foreach (languages() as $languageCode => $language) {
                            if (isset($productProvider['description'][$languageCode]) && $productProvider['description'][$languageCode] && ($product->getTranslation('description', $languageCode, false) != $productProvider['description'][$languageCode])) {
                                $isUpdate = true;
                                $product->setTranslation('description', $languageCode, $productProvider['description'][$languageCode]);
                            }
                        }
                        if ($isUpdate) {
                            $result['description']['updated'] += 1;
                            $product->update();
                        }
                    }
                    // Save attributes
                    if (isset($productProvider['attributes']) && count($productProvider['attributes'])) {
                        $productAttributes = [];
                        $attributesToAdd = [];
                        $attributesToUpdate = [];
                        $attributesToDelete = [];
                        foreach ($product->productAttributes->sortBy('order') as $productAttribute) {
                            $productAttributes[$productAttribute->order] = [
                                'id' => $productAttribute->id,
                                'key' => $productAttribute->getTranslations('key'),
                                'value' => $productAttribute->getTranslations('value'),
                            ];
                        }
                        foreach ($productProvider['attributes'] as $order => $attributeProviderErp) {
                            if (! isset($productAttributes[$order])) {
                                $attributesToAdd[] = [
                                    'order' => $order,
                                    'key' => $attributeProviderErp['key'],
                                    'value' => $attributeProviderErp['value'],
                                ];
                            } else {
                                $languages = array_keys($attributeProviderErp['key']);
                                foreach ($languages as $language) {
                                    $key = $attributeProviderErp['key'][$language];
                                    $value = $attributeProviderErp['value'][$language];
                                    $existingKey = $productAttributes[$order]['key'][$language] ?? null;
                                    $existingValue = $productAttributes[$order]['value'][$language] ?? null;
                                    if ($existingKey != $key || $existingValue != $value) {
                                        $attributesToUpdate[] = [
                                            'id' => $productAttributes[$order]['id'],
                                            'order' => $order,
                                            'key' => $attributeProviderErp['key'],
                                            'value' => $attributeProviderErp['value'],
                                        ];
                                    }
                                }
                            }
                        }
                        foreach ($productAttributes as $order => $productAttribute) {
                            if (! isset($productProvider['attributes'][$order])) {
                                $attributesToDelete[] = $productAttribute['id'];
                            }
                        }
                        if (count($attributesToAdd)) {
                            foreach ($attributesToAdd as $attribute) {
                                $product->productAttributes()->create($attribute);
                                $result['attributes']['created'] += 1;
                            }
                        }
                        if (count($attributesToUpdate)) {
                            foreach ($attributesToUpdate as $attribute) {
                                ProductAttribute::where('id', $attribute['id'])->update([
                                    'order' => $attribute['order'],
                                    'key' => $attribute['key'],
                                    'value' => $attribute['value'],
                                ]);
                                $result['attributes']['updated'] += 1;
                            }
                        }
                        if (count($attributesToDelete)) {
                            ProductAttribute::destroy($attributesToDelete);
                            $result['attributes']['deleted'] += 1;
                        }
                    }
                    /* Save characteristics */
                    if (isset($productProvider['characteristics']) && count($productProvider['characteristics'])) {
                        $productCharacteristics = [];
                        $characteristicsToAdd = [];
                        $characteristicsToUpdate = [];
                        $characteristicsToDelete = [];
                        foreach ($product->productCharacteristics->sortBy('order') as $productCharacteristic) {
                            $productCharacteristics[$productCharacteristic->order] = [
                                'id' => $productCharacteristic->id,
                                'key' => $productCharacteristic->getTranslations('key'),
                                'value' => $productCharacteristic->getTranslations('value'),
                            ];
                        }
                        foreach ($productProvider['characteristics'] as $order => $characteristicProvider) {
                            if (! isset($productCharacteristics[$order])) {
                                $characteristicsToAdd[] = [
                                    'order' => $order,
                                    'key' => $characteristicProvider['key'],
                                    'value' => $characteristicProvider['value'],
                                ];
                            } else {
                                $languages = array_keys($characteristicProvider['key']);
                                foreach ($languages as $language) {
                                    $key = $characteristicProvider['key'][$language];
                                    $value = $characteristicProvider['value'][$language];
                                    $existingKey = $productCharacteristics[$order]['key'][$language] ?? null;
                                    $existingValue = $productCharacteristics[$order]['value'][$language] ?? null;
                                    if ($existingKey != $key || $existingValue != $value) {
                                        $characteristicsToUpdate[] = [
                                            'id' => $productCharacteristics[$order]['id'],
                                            'order' => $order,
                                            'key' => $characteristicProvider['key'],
                                            'value' => $characteristicProvider['value'],
                                        ];
                                    }
                                }
                            }
                        }
                        foreach ($productCharacteristics as $order => $productCharacteristic) {
                            if (! isset($productProvider['characteristics'][$order])) {
                                $characteristicsToDelete[] = $productCharacteristic['id'];
                            }
                        }
                        if (count($characteristicsToAdd)) {
                            foreach ($characteristicsToAdd as $characteristic) {
                                $product->productCharacteristics()->create($characteristic);
                                $result['characteristics']['created'] += 1;
                            }
                        }
                        if (count($characteristicsToUpdate)) {
                            foreach ($characteristicsToUpdate as $characteristic) {
                                ProductCharacteristic::where('id', $characteristic['id'])->update([
                                    'order' => $characteristic['order'],
                                    'key' => $characteristic['key'],
                                    'value' => $characteristic['value'],
                                ]);
                                $result['characteristics']['updated'] += 1;
                            }
                        }
                        if (count($characteristicsToDelete)) {
                            ProductCharacteristic::destroy($characteristicsToDelete);
                            $result['characteristics']['deleted'] += 1;
                        }
                    }
                }
            }
            $endTime = microtime(true);
            $result['time'] = $endTime - $startTime;

            return $result;
        });
    }

    /*  ========================================================================= */
    /*  IMAGES */
    /*  ========================================================================= */
    public function images() {
        return activity()->withoutLogs(function () {
            $result = ['created' => 0];
            Product::query()
                ->where('provider', Odoo::$code)
                ->whereNotNull('provider_id')
                ->whereNotNull('sku')
                ->whereDoesntHave('image')
                ->with('productBrand')
                ->has('productBrand')
                ->chunk(1000, function ($products) use (&$result) {
                    foreach ($products as $product) {
                        $result = $this->image($product, $result);
                    }
                });

            return $result;
        });
    }
    public function image($product, $result = [], $onlyProvider = null) {
        if (! isset($result['created'])) {
            $result['created'] = 0;
        }
        $sku = strval(trim($product->sku));
        $brand = strtolower(trim($product->productBrand->name));
        $images = [];
        if (config('services.vadeto_brands.status') && config('services.vadeto_brands.download_image_product')) {
            $images = [
                'imagesVadetoBrands' => $this->imageService->getAll($brand, config('translatable.fallback'), $sku),
            ];
        }
        foreach ($images as $provider => $imagesProvider) {
            if ($onlyProvider && $onlyProvider !== $provider) {
                continue;
            }
            $files = [];
            foreach ($imagesProvider as $imagesBySku) {
                if (isset($imagesBySku[$sku]) && count($imagesBySku[$sku])) {
                    $files = array_merge($files, $imagesBySku[$sku]);
                }
            }
            $files = array_values(array_unique($files));
            if (count($files)) {
                $product->image()->delete();
                $product->images()->delete();
                $product->imagesVadetoBrands()->delete();
                $i = 0;
                foreach ($files as $file) {
                    $imgName = $sku.'-'.$i.'.webp';
                    if ($i == 0) {
                        $urlLocal = 'catalog/product/'.$imgName;
                        if (mediaManagerSeeder($file, $urlLocal)) {
                            imageManager($urlLocal, 800, $product, $provider);
                            $result['created'] += 1;
                        }
                    } else {
                        $urlLocal = 'catalog/product/gallery/'.$imgName;
                        if (mediaManagerSeeder($file, $urlLocal)) {
                            imagesManager($urlLocal, 800, $product, $provider);
                            $result['created'] += 1;
                        }
                    }
                    $i++;
                }
            }
        }

        return $result;
    }
}

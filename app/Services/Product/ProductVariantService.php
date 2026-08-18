<?php

namespace App\Services\Product;

use App\Models\Image;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Throwable;

class ProductVariantService
{
    /**
     * Guarda y sincroniza todas las opciones y variantes del producto.
     */
    public function saveVariants(Product $product, bool $hasVariants, array $productOptions, array $productVariants): void
    {
        if (!$hasVariants) {
            $product->productVariants()->delete();
            return;
        }

        // Pasamos el $product para asociar las imágenes/metadata contextuales al producto actual
        $valueMap = $this->syncOptionsAndValues($productOptions, $product);
        $savedVariantIds = [];

        foreach ($productVariants as $index => $variantData) {
            $variant = $this->findOrCreateVariant($product, $variantData);

            // Actualizar propiedades de la variante
            $variant->variant_key = $variantData['variant_key'];
            $variant->price = $variantData['price'];
            $variant->price_promotion = $variantData['price_promotion'] ?: null;
            $variant->cost = $variantData['cost'] ?: null;
            $variant->weight_kl = $variantData['weight_kl'] ?: null;
            $variant->height = $variantData['height'] ?: null;
            $variant->width = $variantData['width'] ?: null;
            $variant->length = $variantData['length'] ?: null;
            $variant->is_active = $variantData['is_active'];
            $variant->position = $index + 1;
            $variant->save();

            $savedVariantIds[] = $variant->id;

            // Mapeo limpio hacia la pivote
            $syncData = [];
            foreach ($variantData['option_values'] as $valString) {
                if ($valueData = $this->resolveValueFromMap($valString, $valueMap)) {
                    $syncData[$valueData['id']] = ['metadata' => $valueData['metadata']];
                }
            }
            $variant->productOptionValues()->sync($syncData);

            // Guardar imágenes de galería temporal
            if (!empty($variantData['gallery_images_tmp'])) {
                foreach ($variantData['gallery_images_tmp'] as $imageTmp) {
                    $url = $imageTmp->store('catalog/product/variant/gallery');
                    imagesManager($url, 800, $variant);
                }
            }

            // Sincronizar bodegas
            $warehousesData = [];
            foreach ($variantData['warehouses'] as $warehouseId => $qty) {
                $warehousesData[$warehouseId] = ['quantity' => $qty];
            }
            $variant->productWarehouses()->sync($warehousesData);
        }

        // Eliminar variantes huérfanas
        $product->productVariants()->whereNotIn('id', $savedVariantIds)->delete();
    }

    /**
     * Prepara el array inicial de opciones a partir de la BD.
     */
    public function getOptionsForForm(Product $product): array 
    {
        $hasVariants = $product->productVariants()->where('variant_key', '!=', 'default')->exists();
        $productOptions = [];

        if ($hasVariants) {
            foreach ($product->productVariants as $variant) {
                foreach ($variant->productOptionValues as $pov) {
                    $option = $pov->productOption;
                    $name = $option->name;

                    if (!isset($productOptions[$name])) {
                        $productOptions[$name] = [
                            'name' => $name,
                            'type' => $option->type ?? ProductOption::TYPE_BUTTON,
                            'values' => [],
                        ];
                    }

                    $exists = collect($productOptions[$name]['values'])->contains('value', $pov->value);
                    if (!$exists) {
                       $rawMetadata = $pov->pivot->metadata;
                        $productOptions[$name]['values'][] = [
                            'value' => $pov->value,
                            'metadata' => $rawMetadata, 
                            'metadata_url' => ($rawMetadata && $option->type === ProductOption::TYPE_IMAGE) 
                                ? Storage::url($rawMetadata) 
                                : null,
                        ];
                    }
                }
            }
        }

        return [
            'hasVariants' => $hasVariants,
            'productOptions' => array_values($productOptions),
        ];
    }

    /**
     * Genera la lista combinada de variantes (matemática cartesiana).
     */
    public function generateVariants(Product $product, array $productOptions, array $currentVariants): array {
        if (empty($productOptions)) {
            return [];
        }

        $optionsAssoc = [];
        foreach ($productOptions as $option) {
            $name = $option['name'] ?? '';
            $rawValues = $option['values'] ?? [];

            $values = [];
            foreach ($rawValues as $valItem) {
                $valString = is_array($valItem) ? ($valItem['value'] ?? '') : $valItem;
                if (trim($valString) !== '') {
                    $values[] = trim($valString);
                }
            }

            if (trim($name) === '' || empty($values)) {
                continue;
            }
            
            $optionsAssoc[$name] = $values;
        }

        $combinations = $this->generateVariationCartesian($optionsAssoc);
        $existing = collect($currentVariants)->keyBy('variant_key');

        if ($product->exists) {
            $dbVariants = $product->productVariants()
                ->with(['productOptionValues', 'productWarehouses', 'images'])
                ->get()
                ->toBase()
                ->map(function ($variant) use ($product) {
                    $combo = $variant->productOptionValues->pluck('value')->values()->toArray();
                    return $this->buildVariantArray($product, $variant, $combo);
                })
                ->keyBy('variant_key');

            $existing = $dbVariants->merge($existing);
        }

        $variants = [];
        foreach ($combinations as $combo) {
            $variantKey = collect(array_values($combo))
                ->map(fn ($v) => strtolower(trim($v)))
                ->filter()
                ->sort()
                ->values()
                ->implode('|');

            if ($existing->has($variantKey)) {
                $variants[] = $existing[$variantKey];
            } else {
                $inheritedData = $this->findBestMatchForInheritanceVariant($combo, $existing);
                $variant = array_merge([
                    'variant_key' => $variantKey,
                    'sku' => $this->generateUniqueVariantSku($product, $combo),
                ], $inheritedData);

                $variants[] = $this->buildVariantArray($product, $variant, $combo);
            }
        }

        return $variants;
    }

    public function deleteVariant(Product $product, array $productVariants, int $variantIndex): array
    {
        if (!empty($productVariants[$variantIndex]['id'])) {
            $variant = ProductVariant::find($productVariants[$variantIndex]['id']);
            if ($variant) {
                foreach ($variant->images as $image) {
                    Storage::delete($image->url);
                    $image->delete();
                }
                $variant->delete();
            }
        }

        unset($productVariants[$variantIndex]);
        return array_values($productVariants);
    }

    public function removeVariantGalleryImage(array $variantData, int $imageIndex): array
    {
        $imageData = $variantData['gallery_images'][$imageIndex] ?? null;
        if ($imageData && !empty($imageData['id'])) {
            $image = Image::find($imageData['id']);
            if ($image) {
                Storage::delete($image->url);
                $image->delete();
            }
        }
        unset($variantData['gallery_images'][$imageIndex]);
        $variantData['gallery_images'] = array_values($variantData['gallery_images']);

        return $variantData;
    }

    public function getOptionsCatalog(Product $product): array
    {
        $allOptions = [];
        $variants = $product->productVariants()
            ->with(['productOptionValues.productOption'])
            ->validateVariant()
            ->get();

        foreach ($variants as $variant) {
            foreach ($variant->productOptionValues as $optionValue) {
                $option = $optionValue->productOption;
                $optionId = $option->id;
                $valueId = $optionValue->id;

                if (!isset($allOptions[$optionId])) {
                    $allOptions[$optionId] = [
                        'id'    => $optionId,
                        'name'  => $option->name,
                        'slug'  => $option->slug,
                        'type'  => $option->type ?? ProductOption::TYPE_BUTTON,
                        'values' => [],
                    ];
                }

                if (!isset($allOptions[$optionId]['values'][$valueId])) {
                    $rawMeta = $optionValue->pivot->metadata ?? $optionValue->metadata;
                    $metaUrl = ($option->type === ProductOption::TYPE_IMAGE && $rawMeta) 
                        ? Storage::url($rawMeta) 
                        : $rawMeta;

                    $allOptions[$optionId]['values'][$valueId] = [
                        'id'       => $valueId,
                        'value'    => $optionValue->value,
                        'slug'     => $optionValue->slug,
                        'metadata' => $metaUrl, // Muestra de imagen o código Hex de color
                    ];
                }
            }
        }

        return array_values($allOptions);
    }

    public function getVariantsSummary(Product $product): array
    {
        return $product->productVariants->map(function ($variant) {
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

    public function formatForCart(ProductVariant $variant): array
    {
        $options = [];
        foreach ($variant->productOptionValues as $optionValue) {
            $optionId = $optionValue->productOption->id;
            if (!isset($options[$optionId])) {
                $options[$optionId] = [
                    'id' => $optionId,
                    'option_name' => $optionValue->productOption->name,
                    'option_value' => $optionValue->value,
                ];
            }
        }

        return $options;
    }

    public function getGallery(Product $product, ?ProductVariant $variant = null): array
    {
        if ($variant && $variant->images->count() > 0) {
            return $variant->images->map(fn ($img) => Storage::url($img->url))->toArray();
        }

        return array_merge([$product->imagePreview()], $product->imagesPreview()->toArray());
    }

    /* ---------------- Auxiliares privados ---------------- */

    private function syncOptionsAndValues(array $productOptions, Product $product): array 
    {
        logger('syncOptionsAndValues', $productOptions);
        $valueMap = [];
        foreach ($productOptions as $option) {
            $name = $option['name'] ?? '';
            $type = $option['type'] ?? ProductOption::TYPE_SELECT;
            $values = $option['values'] ?? [];

            if (trim($name) === '' || empty($values)) {
                logger('se saltó: ', $option);
                continue;
            }

            $catalogOption = ProductOption::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'type' => $type]
            );

            $valueMap[$name] = [];
            foreach ($values as $vIndex => $valueData) {
                $val = is_array($valueData) ? ($valueData['value'] ?? '') : $valueData;
                $metadata = is_array($valueData) ? ($valueData['metadata'] ?? null) : null;

                if (trim($val) === '') {
                    continue;
                }

                if ($type === ProductOption::TYPE_IMAGE) {
                    $metadata = $this->resolveSwatchUpload($valueData, $metadata);
                }

                $valueRecord = ProductOptionValue::updateOrCreate(
                    [
                        'product_option_id' => $catalogOption->id,
                        'value' => $val,
                    ],
                    [
                        'position' => $vIndex + 1,
                    ]
                );

                // Guardamos el ID del valor junto con su metadata calculada para la variante
                $valueMap[$name][$val] = [
                    'id' => $valueRecord->id,
                    'metadata' => $metadata,
                ];
            }
        }
        return $valueMap;
    }

    private function resolveSwatchUpload(array $valueData, ?string $fallbackMetadata): ?string
    {
        try {
            $imageFile = $valueData['metadata_image'] ?? null;

            if (is_array($imageFile) && isset($imageFile[0])) {
                $imageFile = $imageFile[0];
            }

            if (is_string($imageFile) && str_starts_with($imageFile, 'livewire-file:')) {
                $imageFile = TemporaryUploadedFile::createFromLivewire($imageFile);
            }

            if ($imageFile instanceof TemporaryUploadedFile && $imageFile->exists()) {
                return $imageFile->store('catalog/product/swatches');
            }
        } catch (Throwable $e) {
            report($e);
            $imageFile = null;
        }
        return $fallbackMetadata;
    }

    private function resolveValueFromMap(string $valString, array $valueMap): ?array
    {
        foreach ($valueMap as $values) {
            if (isset($values[$valString])) {
                return $values[$valString];
            }
        }
        return null;
    }

    private function findOrCreateVariant(Product $product, array $variantData): ProductVariant
    {
        $variant = null;
        if (!empty($variantData['id'])) {
            $variant = ProductVariant::find($variantData['id']);
        }
        if (!$variant && !empty($variantData['variant_key'])) {
            $variant = ProductVariant::where('variant_key', $variantData['variant_key'])
                ->where('product_id', $product->id)
                ->first();
        }

        if (!$variant) {
            $variant = new ProductVariant();
            $variant->product_id = $product->id;
            $finalSku = $variantData['sku'];
            if ($this->skuExistsVariant($finalSku, null)) {
                $finalSku = $this->generateUniqueVariantSku($product, $variantData['option_values'], null);
            }
            $variant->sku = $finalSku;
        } else {
            if ($variant->sku !== $variantData['sku'] && !$this->skuExistsVariant($variantData['sku'], $variant->id)) {
                $variant->sku = $variantData['sku'];
            }
        }

        return $variant;
    }

    public function buildVariantArray(Product $product, $variant, array $combo): array
    {
        $variant = collect($variant);

        return [
            'id' => $variant['id'] ?? null,
            'variant_key' => $variant['variant_key'],
            'option_values' => $combo,
            'sku' => $variant['sku'] ?? null,
            'price' => $variant['price'] ?? $product->price,
            'price_promotion' => $variant['price_promotion'] ?? null,
            'cost' => $variant['cost'] ?? $product->cost,
            'weight_kl' => $variant['weight_kl'] ?? $product->weight_kl,
            'height' => $variant['height'] ?? $product->height,
            'width' => $variant['width'] ?? $product->width,
            'length' => $variant['length'] ?? $product->length,
            'is_active' => $variant['is_active'] ?? true,
            'position' => $variant['position'] ?? 1,
            'warehouses' => isset($variant['product_warehouses'])
                ? collect($variant['product_warehouses'])->pluck('pivot.quantity', 'pivot.product_warehouse_id')->toArray()
                : [],
            'gallery_images' => isset($variant['images'])
                ? collect($variant['images'])->map(fn ($img) => ['id' => $img['id'], 'url' => Storage::url($img['url'])])->toArray()
                : [],
            'gallery_images_tmp' => [],
        ];
    }

    private function findBestMatchForInheritanceVariant(array $newCombo, $existingVariants): array
    {
        $newValues = array_values($newCombo);
        $bestMatch = null;
        $maxMatches = 0;

        foreach ($existingVariants as $variant) {
            $matches = 0;
            foreach ($newValues as $newValue) {
                if (in_array($newValue, $variant['option_values'])) {
                    $matches++;
                }
            }
            if ($matches > $maxMatches && $matches > 0) {
                $maxMatches = $matches;
                $bestMatch = $variant;
            }
        }

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

    private function skuExistsVariant(string $sku, ?int $excludeId = null): bool
    {
        $query = ProductVariant::where('sku', $sku);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->exists();
    }

    private function generateUniqueVariantSku(Product $product, array $combo, ?int $excludeId = null): string
    {
        $variantSkuBase = $product->sku ?? 'PROD';
        $variantSkuSuffix = collect($combo)->map(fn ($v) => strtoupper(substr($v, 0, 3)))->implode('-');
        $variantSku = "{$variantSkuBase}-{$variantSkuSuffix}";

        $originalSku = $variantSku;
        $counter = 1;
        while ($this->skuExistsVariant($variantSku, $excludeId)) {
            $variantSku = "{$originalSku}-{$counter}";
            $counter++;
        }

        return $variantSku;
    }

    private function generateVariationCartesian(array $options): array
    {
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
                    $tmp[] = $combination + [$name => $value];
                }
            }
            $result = $tmp;
        }
        return $result;
    }
}
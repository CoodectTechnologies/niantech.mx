<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ProductVariantService
{
    /* ---------------- Gestión de variantes ---------------- */
    public function saveVariants(Product $product, bool $hasVariants, array $productOptions, array $productVariants, array $galleryImageIds = []): void {
        if(!$hasVariants):
            $product->productVariants()->delete();
            $product->productOptions()->detach();
            return;
        endif;
        $valueMap = $this->syncOptionsAndValues($productOptions, $product);
        $savedVariantIds = [];
        foreach($productVariants as $index => $variantData):
            $variant = $this->findOrCreateVariant($product, $variantData);
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

            $syncData = [];
            $savedVariantIds[] = $variant->id;
            $valueIds = $this->resolveValueIdsForVariant($variantData['option_values'], $valueMap);
            foreach($valueIds as $valueId):
                $meta = $this->findMetadataForValueId($valueId, $valueMap);
                $syncData[$valueId] = $meta ? ['metadata' => $meta] : [];
            endforeach;
            $variant->productOptionValues()->sync($syncData);

            $productImageIds = [];
            foreach($variantData['product_image_ids'] ?? [] as $imageId):
                if(is_string($imageId) && Str::startsWith($imageId, 'tmp:')):
                    $temporaryIndex = (int) Str::after($imageId, 'tmp:');
                    $imageId = $galleryImageIds[$temporaryIndex] ?? null;
                endif;
                if($imageId && $product->images()->whereKey($imageId)->exists()):
                    $productImageIds[] = (int) $imageId;
                endif;
            endforeach;
            $productImageIds = array_values(array_unique($productImageIds));
            $variant->productImages()->sync(
                collect($productImageIds)->values()->mapWithKeys(
                    fn ($imageId, $position) => [$imageId => ['position' => $position]]
                )->all()
            );

            $warehousesData = [];
            foreach($variantData['warehouses'] as $warehouseId => $qty):
                $warehousesData[$warehouseId] = ['quantity' => $qty];
            endforeach;
            $variant->productWarehouses()->sync($warehousesData);
        endforeach;
        $product->productVariants()->whereNotIn('id', $savedVariantIds)->delete();
    }
    public function getOptionsForForm(Product $product): array  {
        $hasVariants = $product->productVariants()->exists();
        $productOptions = [];
        if($hasVariants):
            $optionTypes = $product->productOptions->pluck('pivot.type', 'name')->toArray();
            foreach($product->productVariants as $variant):
                foreach($variant->productOptionValues as $pov):
                    $option = $pov->productOption;
                    $name = $option->name;
                    $type = $optionTypes[$name] ?? ProductOption::TYPE_BUTTON;
                    if(!isset($productOptions[$name])):
                        $productOptions[$name] = [
                            'name' => $name,
                            'type' => $type,
                            'values' => [],
                        ];
                    endif;

                    $exists = collect($productOptions[$name]['values'])->contains('value', $pov->value);
                    if(!$exists):
                       $rawMetadata = $pov->pivot->metadata;
                        $productOptions[$name]['values'][] = [
                            'value' => $pov->value,
                            'metadata' => $rawMetadata, 
                            'metadata_url' => ($rawMetadata && $type === ProductOption::TYPE_IMAGE) 
                                ? Storage::url($rawMetadata) 
                                : null,
                        ];
                    endif;
                endforeach;
            endforeach;
        endif;
        return [
            'hasVariants' => $hasVariants,
            'productOptions' => array_values($productOptions),
        ];
    }
    public function generateVariants(Product $product, array $productOptions, array $currentVariants): array {
        if(empty($productOptions)) return [];
        $optionsAssoc = [];
        foreach($productOptions as $option):
            $name = $option['name'];
            $rawValues = $option['values'];
            $values = [];
            foreach($rawValues as $valItem):
                if($valString = $valItem['value']):
                    $values[] = trim($valString);
                endif;
            endforeach;
            if(trim($name) === '' || empty($values)) continue;
            $optionsAssoc[$name] = $values;
        endforeach;

        $combinations = $this->generateVariationCartesian($optionsAssoc);
        $existing = collect($currentVariants)->keyBy('variant_key');
        if($product->exists):
            $dbVariants = $product->productVariants()
                ->with(['productOptionValues', 'productWarehouses', 'productImages'])
                ->get()
                ->toBase()
                ->map(function ($variant) use ($product) {
                    $combo = $variant->productOptionValues->pluck('value')->values()->toArray();
                    return $this->buildVariantArray($product, $variant, $combo);
                })->keyBy('variant_key');
            $existing = $dbVariants->merge($existing);
        endif;

        $variants = [];
        foreach($combinations as $combo):
            $variantKey = collect(array_values($combo))
                ->map(fn ($v) => strtolower(trim($v)))
                ->filter()
                ->sort()
                ->values()
                ->implode('|');
            if($existing->has($variantKey)):
                $variants[] = $existing[$variantKey];
            else:
                $inheritedData = $this->findBestMatchForInheritanceVariant($combo, $existing);
                $variant = array_merge([
                    'variant_key' => $variantKey,
                    'sku' => $this->generateUniqueVariantSku($product, $combo),
                ], $inheritedData);
                $variants[] = $this->buildVariantArray($product, $variant, $combo);
            endif;
        endforeach;
        return $variants;
    }
    public function deleteVariant(array $productVariants, int $variantIndex): array {
        if(!empty($productVariants[$variantIndex]['id'])):
            $variant = ProductVariant::find($productVariants[$variantIndex]['id']);
            if($variant):
                $variant->delete();
            endif;
        endif;
        unset($productVariants[$variantIndex]);
        return array_values($productVariants);
    }

    /* ---------------- Auxiliares de cátalogo ---------------- */
    public function getOptionsCatalog(Product $product): array {
        $allOptions = [];
        $productOptionTypes = $product->productOptions->pluck('pivot.type', 'id')->toArray();
        $variants = $product->productVariants()
            ->with(['productOptionValues.productOption'])
            ->validateVariant()
            ->get();
        foreach($variants as $variant):
            foreach($variant->productOptionValues as $optionValue):
                $option = $optionValue->productOption;
                $optionId = $option->id;
                $valueId = $optionValue->id;
                $type = $productOptionTypes[$optionId] ?? ProductOption::TYPE_BUTTON;
                if(!isset($allOptions[$optionId])):
                    $allOptions[$optionId] = [
                        'id' => $optionId,
                        'name' => $option->name,
                        'slug' => $option->slug,
                        'type' => $type,
                        'values' => [],
                    ];
                endif;
                if(!isset($allOptions[$optionId]['values'][$valueId])):
                    $rawMeta = $optionValue->pivot->metadata ?? $optionValue->metadata;
                    $metaUrl = ($type === ProductOption::TYPE_IMAGE && $rawMeta) 
                        ? Storage::url($rawMeta) 
                        : $rawMeta;
                    $allOptions[$optionId]['values'][$valueId] = [
                        'id' => $valueId,
                        'value' => $optionValue->value,
                        'slug' => $optionValue->slug,
                        'metadata' => $metaUrl,
                    ];
                endif;
            endforeach;
        endforeach;
        return array_values($allOptions);
    }
    public function getVariantsSummary(Product $product): array {
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
    public function formatForCart(ProductVariant $variant): array {
        $options = [];
        foreach($variant->productOptionValues as $optionValue):
            $optionId = $optionValue->productOption->id;
            if(!isset($options[$optionId])):
                $options[$optionId] = [
                    'id' => $optionId,
                    'option_name' => $optionValue->productOption->name,
                    'option_value' => $optionValue->value,
                ];
            endif;
        endforeach;
        return $options;
    }
    public function getGallery(Product $product, ?ProductVariant $variant = null): array {
        if($variant && $variant->productImages->count() > 0):
            return $variant->productImages->map(fn ($img) => Storage::url($img->url))->toArray();
        endif;
        return array_merge([$product->imagePreview()], $product->imagesPreview()->toArray());
    }

    /* ---------------- Auxiliares privados ---------------- */
    private function syncOptionsAndValues(array $productOptions, Product $product): array  {
        $valueMap = [];
        $productSync = [];
        foreach($productOptions as $pIndex => $option):
            $name = $option['name'] ?? '';
            $type = $option['type'] ?? ProductOption::TYPE_BUTTON;
            $values = $option['values'] ?? [];
            if(trim($name) === '' || empty($values)) continue;

            $catalogOption = ProductOption::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
            $productSync[$catalogOption->id] = [
                'type' => $type,
                'position' => $pIndex + 1,
            ];
            $valueMap[$name] = [];
            foreach($values as $vIndex => $valueData):
                $val = $valueData['value'] ?? '';
                $metadata = $valueData['metadata'] ?? null;
                if(trim($val) === '') continue;

                if($type === ProductOption::TYPE_IMAGE):
                    $metadata = $this->resolveImageUpload($valueData, $metadata);
                endif;
                $valueRecord = ProductOptionValue::updateOrCreate(
                    [
                        'product_option_id' => $catalogOption->id,
                        'value' => $val,
                    ],
                    [
                        'position' => $vIndex + 1,
                    ]
                );
                $valueMap[$name][$val] = [
                    'id' => $valueRecord->id,
                    'metadata' => $metadata,
                ];
            endforeach;
            $product->productOptions()->sync($productSync);
        endforeach;
        return $valueMap;
    }
    private function resolveImageUpload(array $valueData, ?string $fallbackMetadata): ?string {
        $imageFile = $valueData['metadata_image'] ?? null;
        if(is_array($imageFile) && isset($imageFile[0])):
            $imageFile = $imageFile[0];
        endif;
        if(is_string($imageFile) && str_starts_with($imageFile, 'livewire-file:')):
            $imageFile = TemporaryUploadedFile::createFromLivewire(
                Str::after($imageFile, 'livewire-file:')
            );
        endif;
        if($imageFile instanceof TemporaryUploadedFile && $imageFile->isValid() && $imageFile->exists()):
            return $imageFile->store('catalog/product/variation');
        endif;
        return $fallbackMetadata;
    }
    private function findMetadataForValueId(int $valueId, array $valueMap): ?string {
        foreach($valueMap as $values):
            foreach($values as $item):
                if(isset($item['id']) && $item['id'] === $valueId):
                    return $item['metadata'] ?? null;
                endif;
            endforeach;
        endforeach;
        return null;
    }
    private function resolveValueIdsForVariant(array $optionValues, array $valueMap): array {
        $valueIds = [];
        foreach($optionValues as $value):
            foreach($valueMap as $values):
                if(isset($values[$value]['id'])):
                    $valueIds[] = $values[$value]['id'];
                    break;
                endif;
            endforeach;
        endforeach;
        return $valueIds;
    }
    private function findOrCreateVariant(Product $product, array $variantData): ProductVariant {
        $variant = null;
        if(!empty($variantData['id'])):
            $variant = ProductVariant::find($variantData['id']);
        endif;
        if(!$variant && !empty($variantData['variant_key'])):
            $variant = ProductVariant::where('variant_key', $variantData['variant_key'])
                ->where('product_id', $product->id)
                ->first();
        endif;
        if(!$variant):
            $variant = new ProductVariant();
            $variant->product_id = $product->id;
            $finalSku = $variantData['sku'];
            if($this->skuExistsVariant($finalSku, null)):
                $finalSku = $this->generateUniqueVariantSku($product, $variantData['option_values'], null);
            endif;
            $variant->sku = $finalSku;
        else:
            if($variant->sku !== $variantData['sku'] && !$this->skuExistsVariant($variantData['sku'], $variant->id)):
                $variant->sku = $variantData['sku'];
            endif;
        endif;
        return $variant;
    }
    public function buildVariantArray(Product $product, $variant, array $combo): array {
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
            'product_image_ids' => isset($variant['product_images'])
                ? collect($variant['product_images'])->pluck('id')->values()->toArray()
                : [],
        ];
    }
    private function findBestMatchForInheritanceVariant(array $newCombo, $existingVariants): array {
        $newValues = array_values($newCombo);
        $bestMatch = null;
        $maxMatches = 0;
        foreach($existingVariants as $variant):
            $matches = 0;
            foreach($newValues as $newValue):
                if(in_array($newValue, $variant['option_values'])):
                    $matches++;
                endif;
            endforeach;
            if($matches > $maxMatches && $matches > 0):
                $maxMatches = $matches;
                $bestMatch = $variant;
            endif;
        endforeach;
        if($bestMatch):
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
                'product_image_ids' => $bestMatch['product_image_ids'] ?? [],
            ];
        endif;
        return [];
    }
    private function skuExistsVariant(string $sku, ?int $excludeId = null): bool {
        $query = ProductVariant::where('sku', $sku);
        if($excludeId):
            $query->where('id', '!=', $excludeId);
        endif;
        return $query->exists();
    }
    private function generateUniqueVariantSku(Product $product, array $combo, ?int $excludeId = null): string {
        $variantSkuBase = $product->sku ?? 'PROD';
        $variantSkuSuffix = collect($combo)->map(fn ($v) => strtoupper(substr($v, 0, 3)))->implode('-');
        $variantSku = "{$variantSkuBase}-{$variantSkuSuffix}";
        $originalSku = $variantSku;
        $counter = 1;
        while($this->skuExistsVariant($variantSku, $excludeId)):
            $variantSku = "{$originalSku}-{$counter}";
            $counter++;
        endwhile;
        return $variantSku;
    }
    private function generateVariationCartesian(array $options): array {
        $result = [[]];
        foreach($options as $name => $values):
            if(trim($name) === '' || empty($values)) continue;
            $tmp = [];
            foreach($result as $combination):
                foreach($values as $value):
                    if(trim($value) === '') continue;
                    $tmp[] = $combination + [$name => $value];
                endforeach;
            endforeach;
            $result = $tmp;
        endforeach;
        return $result;
    }
}
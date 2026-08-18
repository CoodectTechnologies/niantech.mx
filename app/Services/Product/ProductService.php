<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\Integrations\VadetoBrands\Product\ImageService as VadetoImageService;
use App\Services\Synchronizers\Catalog\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function __construct(
        protected ProductVariantService $variantService
    ) {}

    /**
     * Limpia y castea variables nulas antes de guardar.
     */
    public function sanitizeData(Product $product, array &$categoriesArray, array &$genderArray): void
    {
        $fieldsToNull = ['weight_kl', 'height', 'width', 'length', 'product_brand_id', 'shipping_class_id', 'downloadable', 'price', 'price_promotion', 'cost'];

        foreach ($fieldsToNull as $field) {
            if ($product->{$field} === '') {
                $product->{$field} = null;
            }
        }

        if (isset($categoriesArray[0]) && $categoriesArray[0] == __('Without categories')) {
            $categoriesArray = [];
        }

        if (isset($genderArray[0]) && $genderArray[0] == __('Without gender')) {
            $genderArray = [];
        }
    }

    /**
     * Persiste los datos principales del producto.
     */
    public function saveProduct(
        Product $product,
        array $dtoData,
        callable $saveTranslationsCallback
    ): Product {
        if (!$product->exists) {
            $product->user_id = Auth::id();
        }

        $this->saveTechnicalDatasheet($product, $dtoData['technicalDatasheetTmp'] ?? null);
        $this->saveFileDigital($product, $dtoData['fileDigitalTmp'] ?? null);

        $saveTranslationsCallback($product);
        $product->save();

        // Relaciones e imágenes generales
        $this->saveWarehouses($product, $dtoData['catalogProductWarehousesArray'] ?? []);
        $this->saveCategories($product, $dtoData['catalogCategoryArray'] ?? []);
        $this->saveGenders($product, $dtoData['catalogGenderArray'] ?? []);
        $this->saveMainImage($product, $dtoData['imageTmp'] ?? null);
        $this->saveGalleryImages($product, $dtoData['imagesTmp'] ?? []);
        $this->saveImagesBrands($product, $dtoData['imagesTmpBrands'] ?? []);

        // Guardar variantes vía el servicio secundario
        $this->variantService->saveVariants(
            $product,
            $dtoData['hasVariants'] ?? false,
            $dtoData['productOptions'] ?? [],
            $dtoData['productVariants'] ?? []
        );

        return $product;
    }

    public function saveWarehouses(Product $product, array $warehousesArray): void
    {
        $syncData = [];
        foreach ($warehousesArray as $warehouseId => $quantity) {
            if ($quantity) {
                $syncData[$warehouseId] = ['quantity' => $quantity];
            }
        }
        $product->productWarehouses()->sync($syncData);
    }

    public function saveCategories(Product $product, array $categoriesArray): void
    {
        $validCategoryIds = array_filter($categoriesArray, function ($id) {
            return is_numeric($id) && $id > 0 && ProductCategory::where('id', $id)->exists();
        });

        $product->productCategories()->sync($validCategoryIds);
    }

    public function saveGenders(Product $product, array $genderArray): void
    {
        $product->productGenders()->sync($genderArray);
    }

    public function saveMainImage(Product $product, $imageTmp): void
    {
        if ($imageTmp) {
            $url = $imageTmp->store('catalog/product');
            imageManager($url, 800, $product);
        }
    }

    public function saveGalleryImages(Product $product, array $imagesTmp): void
    {
        if ($imagesTmp) {
            foreach ($imagesTmp as $imgTmp) {
                $url = $imgTmp->store('catalog/product/gallery');
                imagesManager($url, 800, $product);
            }
        }
    }

    public function saveImagesBrands(Product $product, array $imagesTmpBrands): void
    {
        if (count($imagesTmpBrands)) {
            $productController = new ProductController();
            $productController->image(product: $product, onlyProvider: 'imagesVadetoBrands');
        }
    }

    public function saveTechnicalDatasheet(Product $product, $technicalDatasheetTmp): void
    {
        if ($technicalDatasheetTmp) {
            $url = $technicalDatasheetTmp->store('product/technical-datasheet');
            if ($product->technical_datasheet && Storage::exists($product->technical_datasheet)) {
                Storage::delete($product->technical_datasheet);
            }
            $product->technical_datasheet = $url;
        }
    }

    public function saveFileDigital(Product $product, $fileDigitalTmp): void
    {
        if ($fileDigitalTmp) {
            $url = $fileDigitalTmp->store('product/file-digital');
            if ($product->file_digital && Storage::exists($product->file_digital)) {
                Storage::delete($product->file_digital);
            }
            $product->file_digital = $url;
        }
    }

    public function removeMainImage(Product $product): void
    {
        if ($product->image) {
            if (Storage::exists($product->image->url)) {
                Storage::delete($product->image->url);
            }
            $product->image()->delete();
            $product->image = null;
        }
    }

    public function removeTechnicalDatasheet(Product $product): void
    {
        if ($product->technical_datasheet) {
            if (Storage::exists($product->technical_datasheet)) {
                Storage::delete($product->technical_datasheet);
            }
            $product->technical_datasheet = null;
            $product->update();
        }
    }

    public function removeFileDigital(Product $product): void
    {
        if ($product->file_digital) {
            if (Storage::exists($product->file_digital)) {
                Storage::delete($product->file_digital);
            }
            $product->file_digital = null;
            $product->update();
        }
    }

    public function fetchBrandImages(Product $product): array
    {
        if (
            config('services.vadeto_brands.status') &&
            config('services.vadeto_brands.download_image_product') &&
            ($product->sku && $product->provider_id)
        ) {
            $brand = $product->productBrand->name ?? null;
            $sku = $product->sku ?? null;
            $imageService = new VadetoImageService();
            $imagesBrands = $imageService->getAll($brand, language(), $sku);
            $files = [];

            foreach ($imagesBrands as $imagesBySku) {
                if (isset($imagesBySku[$sku]) && count($imagesBySku[$sku])) {
                    $files = array_merge($files, $imagesBySku[$sku]);
                }
            }

            return array_values(array_unique($files));
        }

        return [];
    }
}
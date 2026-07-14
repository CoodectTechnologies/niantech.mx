<?php

namespace App\Imports\Admin\Product;

use App\Models\Product;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductWordpressImport implements ToCollection, WithHeadingRow
{
    /**
     * @param  Collection  $collection
     */
    public function collection(Collection $rows) {
        foreach ($rows as $row) {
            try {
                $name = $row['nombre'];
                $sku = $row['sku'];
                $type = $row['tipo'];
                $detail = $row['descripcion_corta'];
                $description = (isset($row['descripcion']) ? str_replace(['\n', "'", '"'], '', $row['descripcion']) : '');
                $featured = $row['esta_destacado'];
                $status = ($row['visibilidad_en_el_catalogo'] && $row['publicado'] == 1) ? Product::STATUS_PUBLISHED : Product::STATUS_DRAFT;
                $quantity = $row['existencias'] ? null : intval($row['inventario']); // null es sin limite de existencias
                $weightKl = isset($row['peso_kl']) ? $row['peso_kl'] : null; // PESO KL
                $width = (isset($row['ancho_cm']) ? $row['ancho_cm'] : null) ?? (isset($row['anchura_cm']) ? $row['anchura_cm'] : null);
                $length = $row['longitud_cm'];
                $height = $row['altura_cm'];
                $price = $row['precio_normal'];
                $categories = $row['categorias'];
                $images = $row['imagenes'];

                // Validate
                if (! $name) {
                    continue;
                }
                $productExists = Product::where('name', $name);
                if ($sku) {
                    $productExists = $productExists->orWhere('sku', $sku);
                }
                $productExists = $productExists->first();
                if ($productExists) {
                    continue;
                }
                if ($type != 'simple') {
                    continue;
                }

                $product = Product::create([
                    'currency_id' => 1,
                    'sku' => $sku,
                    'name' => $name,
                    'price' => $price,
                    'featured' => $featured,
                    'status' => $status,
                    'detail' => $detail,
                    'description' => $description,
                    'weight_kl' => $weightKl,
                    'height' => $height,
                    'width' => $width,
                    'length' => $length,
                    'type' => Product::TYPE_PHYSICAL,
                ]);
                $this->saveCategories($product, $categories);
                $this->saveImages($product, $images);
            } catch (Exception $e) {
                report($e);
            }
        }
    }

    private function saveCategories($product, $categories) {
        if ($product && $categories) {
            $results = $this->formatCategory($categories);
            foreach ($results as $result) {
                $category = $this->createCategory($result, $parentId = null, $product);
                foreach ($result as $value) {
                    if (is_array($value)) {
                        $this->mapCategory($value, $category->id, $product);
                    }
                }
            }
        }
    }
    private function formatCategory($categories) {
        $categories = explode(', ', $categories);
        $results = [];
        foreach ($categories as $category) {
            $levels = explode(' > ', $category);
            $currentLevel = &$results;

            foreach ($levels as $level) {
                if (! isset($currentLevel[$level])) {
                    $currentLevel[$level] = ['name' => $level, 'description' => ''];
                }
                $currentLevel = &$currentLevel[$level];
            }
        }

        return $results;
    }
    private function mapCategory($categoryArray, $parentId, $product) {
        $category = $this->createCategory($categoryArray, $parentId, $product);
        foreach ($categoryArray as $value) {
            if (is_array($value)) {
                $this->mapCategory($value, $category->id, $product);
            }
        }
    }
    private function createCategory($categoryArray, $parentId, $product) {
        $category = ProductCategory::where('name', $categoryArray['name'])->first();
        if (! $category) {
            $category = ProductCategory::create([
                'name' => $categoryArray['name'],
                'description' => $categoryArray['description'],
                'parent_id' => $parentId,
            ]);
        }
        $product->productCategories()->attach($category->id);

        return $category;
    }
    private function saveImages($product, $images) {
        if ($product && $images) {
            $imagesArray = explode(',', $images);
            foreach ($imagesArray as $key => $image) {
                $extension = explode('.', $image);
                $extension = end($extension);
                $location = 'public/catalog/product';
                $date = str_replace(':', '-', date('h:i:s')).rand(1, 100);
                $name = filter_var($product->name, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
                $name = str_replace(['\\', '/', ' ', '"', "'", ':', '.', ';', '#', '&', '?'], '-', $name).rand(1, 100);
                $url = $location.$date.$name.$extension;
                $contentImage = file_get_contents($image);
                Storage::put($url, $contentImage);
                if ($key == 0) {
                    imageManager($url, 600, $product);
                } else {
                    imagesManager($url, 600, $product);
                }
            }
        }
    }
}

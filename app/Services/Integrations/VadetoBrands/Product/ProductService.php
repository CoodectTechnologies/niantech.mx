<?php

namespace App\Services\Integrations\VadetoBrands\Product;

use App\DTO\Integrations\VadetoBrands\Product\ProductDTO;
use App\DTO\Integrations\VadetoBrands\Product\TemplateDTO;
use App\Integrations\VadetoBrands;

class ProductService
{
    protected VadetoBrands $vadetoBrands;

    public function __construct() {
        $this->vadetoBrands = new VadetoBrands;
    }
    public function getAll(): array {
        $result = [];
        $rawProducts = $this->vadetoBrands->getProducts();
        foreach ($rawProducts['productos'] ?? [] as $language => $productsByLanguage) {
            foreach ($productsByLanguage as $sku => $productData) {
                $productDto = ProductDTO::handle($productData, $language, $sku);
                $productData = $productDto->toArray();
                if (! isset($result[$productDto->sku])) {
                    $result[$productDto->sku] = $productData;
                } else {
                    $result[$productDto->sku] = array_replace_recursive($result[$productDto->sku], $productData);
                }
            }
        }

        return $result;
    }
    public function getAllTemplates(): array {
        $result = [];
        $rawTemplates = $this->vadetoBrands->getTemplates();
        foreach ($rawTemplates['plantillas'] ?? [] as $language => $templatesByLanguage) {
            foreach ($templatesByLanguage as $sku => $template) {
                $templateDto = TemplateDTO::handle($template, $language, $sku);
                $templateData = $templateDto->toArray();
                if (! isset($result[$templateDto->sku])) {
                    $result[$templateDto->sku] = $templateData;
                } else {
                    $result[$templateDto->sku] = array_replace_recursive($result[$templateDto->sku], $templateData);
                }
            }
        }

        return $result;
    }
    public function getAllContent() {
        $products = $this->getAll();
        $templates = $this->getAllTemplates();
        foreach ($templates as $sku => $templateData) {
            if (isset($products[$sku])) {
                $products[$sku]['description'] = array_replace_recursive($products[$sku]['description'] ?? [], $templateData['description']);
            }
        }

        return $products;
    }
}

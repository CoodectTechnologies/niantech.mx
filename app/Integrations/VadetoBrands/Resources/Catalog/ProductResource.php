<?php

namespace App\Integrations\VadetoBrands\Resources\Catalog;

use App\Integrations\VadetoBrands\Client\Catalog\ProductClient;
use App\Integrations\VadetoBrands\Dto\Catalog\ProductDto;
use App\Integrations\VadetoBrands\Dto\Catalog\TemplateDto;

class ProductResource
{
    protected ProductClient $productClient;

    public function __construct() {
        $this->productClient = new ProductClient;
    }
    public function getAll(): array {
        $result = [];
        $rawProducts = $this->productClient->getProducts();
        foreach ($rawProducts['productos'] ?? [] as $language => $productsByLanguage) {
            foreach ($productsByLanguage as $sku => $productData) {
                $productDto = ProductDto::handle($productData, $language, $sku);
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
        $rawTemplates = $this->productClient->getTemplates();
        foreach ($rawTemplates['plantillas'] ?? [] as $language => $templatesByLanguage) {
            foreach ($templatesByLanguage as $sku => $template) {
                $templateDto = TemplateDto::handle($template, $language, $sku);
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

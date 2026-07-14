<?php

namespace App\DTO\Integrations\VadetoBrands\Product;

use App\Integrations\VadetoBrands;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductDTO
{
    public function __construct(
        public readonly string $sku,
        public readonly string $provider,
        public readonly array $name,
        public readonly array $nameCommercial,
        public readonly array $attributes,
        public readonly array $characteristics,
        public readonly array $categories,
        public readonly float $width,
        public readonly float $height,
        public readonly float $length,
        public readonly float $weight,
        public readonly array $metaTitle,
        public readonly array $metaDescription,
        public readonly array $metaKeywords
    ) {}

    public static function handle(array $product, string $language, string $sku): self {
        try {
            $language = str_replace('-', '_', $language);
            $sku = strval(trim($sku));

            return new self(
                sku: $sku,
                provider: VadetoBrands::$code,
                name: [$language => ($product['nombre'] ?? '')],
                nameCommercial: [$language => ($product['nombre_comercial'] ?? '')],
                attributes: AttributeDTO::handle($product['propiedades'] ?? [], $language),
                characteristics: AttributeDTO::handle($product['caracteristicas'] ?? [], $language),
                categories: [$language => ($product['categorias'] ?? [])],
                width: floatval($product['units']['width'] ?? 0),
                height: floatval($product['units']['height'] ?? 0),
                length: floatval($product['units']['length'] ?? 0),
                weight: floatval($product['units']['weight'] ?? 0),
                metaTitle: [$language => ($product['seo']['title'] ?? '')],
                metaDescription: [$language => ($product['seo']['description'] ?? '')],
                metaKeywords: [$language => ($product['seo']['keywords'] ?? '')]
            );
        } catch (Throwable $e) {
            Log::channel('vadeto_brands')->error('Error handling product DTO: '.$e->getMessage(), [
                'product' => $product,
                'sku' => $sku,
                'language' => $language,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
    public function toArray(): array {
        return [
            'sku' => $this->sku,
            'provider' => $this->provider,
            'name' => $this->name,
            'name_commercial' => $this->nameCommercial,
            'attributes' => $this->attributes,
            'characteristics' => $this->characteristics,
            'categories' => $this->categories,
            'width' => $this->width,
            'height' => $this->height,
            'length' => $this->length,
            'weight' => $this->weight,
            'metaTitle' => $this->metaTitle,
            'metaDescription' => $this->metaDescription,
            'metaKeywords' => $this->metaKeywords,
        ];
    }
}

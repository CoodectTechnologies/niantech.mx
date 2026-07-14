<?php

namespace App\DTO\Integrations\Odoo\Product;

use App\Integrations\Odoo;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductDTO
{
    public function __construct(
        public readonly string $sku,
        public readonly string $provider,
        public readonly string $providerId,
        public readonly array $name,
        public readonly array $nameCommercial,
        public readonly float $cost,
        public readonly float $price,
        public readonly ?string $currency,
        public readonly ?string $brand,
        public readonly float $width,
        public readonly float $weight,
        public readonly float $height,
        public readonly float $volume,
        public readonly ?string $detail,
        public readonly ?string $description,
        public readonly array $categories
    ) {}

    public static function handle(array $product): self {
        try {
            return new self(
                sku: trim($product['default_code'] ?? ''),
                provider: Odoo::$code,
                providerId: $product['id'],
                name: [config('translatable.fallback') => $product['name'] ?? ''],
                nameCommercial: [config('translatable.fallback') => ''],
                cost: round($product['standard_price'] ?? 0, 2),
                price: round($product['list_price'] ?? 0, 2),
                currency: $product['currency_id'][1] ?? null,
                brand: null,
                width: 0,
                weight: round($product['weight'] ?? 0, 2),
                height: 0,
                volume: round($product['volume'] ?? 0, 2),
                detail: $product['description'] ?? null,
                description: $product['description_sale'] ?? null,
                categories: ProductCategoryDTO::handle($product['categ_id'])
            );
        } catch (Throwable $e) {
            Log::channel('odoo.general')->error('Error handling product DTO: '.$e->getMessage(), [
                'product' => $product,
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
            'provider_id' => $this->providerId,
            'name' => $this->name,
            'name_commercial' => $this->nameCommercial,
            'cost' => $this->cost,
            'price' => $this->price,
            'currency' => $this->currency,
            'brand' => $this->brand,
            'width' => $this->width,
            'weight' => $this->weight,
            'height' => $this->height,
            'volume' => $this->volume,
            'detail' => $this->detail,
            'description' => $this->description,
            'categories' => $this->categories,
        ];
    }
}

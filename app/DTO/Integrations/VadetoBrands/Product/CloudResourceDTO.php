<?php

namespace App\DTO\Integrations\VadetoBrands\Product;

use Illuminate\Support\Facades\Log;
use Throwable;

class CloudResourceDTO
{
    public function __construct(
        public readonly string $brand,
        public readonly string $language,
        public readonly string $sku,
        public readonly array $resources
    ) {}

    public static function handle(array $response, string $brand, string $language, string|null $sku): self {
        try {
            return new self(
                brand: strtoupper(trim($brand)),
                language: str_replace('-', '_', trim($language)),
                sku: strval(trim($sku)),
                resources: $response['resources'] ?? []
            );
        } catch (Throwable $e) {
            Log::channel('vadeto_brands')->error('Error handling cloud resources DTO: '.$e->getMessage(), [
                'brand' => $brand,
                'language' => $language,
                'sku' => $sku,
                'response' => $response,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
    public function toArray(): array {
        return $this->resources;
    }
}
